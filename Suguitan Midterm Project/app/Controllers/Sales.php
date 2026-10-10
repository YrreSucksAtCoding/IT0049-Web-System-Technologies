<?php

namespace App\Controllers;

use App\Models\SaleModel;
use App\Models\ProductModel;
use App\Models\CustomerModel;

/**
 * Sales controller
 *
 * Recording a sale and viewing the history. Both require a login — the
 * routes sit inside the 'auth' filtered group.
 *
 * Recording a sale is the only place in this system where two tables have
 * to change together: a row is added to `sales` and `products.stock_quantity`
 * goes down. Those two writes are wrapped in a transaction so the stock can
 * never drop without a sale being recorded, or the other way round.
 */
class Sales extends BaseController
{
    /**
     * Sales history.
     *
     * Route: GET /sales
     * The model joins the product, customer and staff names in one query
     * rather than looking each up per row.
     * Returns the rendered HTML of app/Views/sales/index.php
     */
    public function index()
    {
        $saleModel = new SaleModel();

        return view('sales/index', [
            'title'   => 'Sales History',
            'sales'   => $saleModel->getHistory(),
            'revenue' => $saleModel->getTotalRevenue(),
            'units'   => $saleModel->getTotalUnitsSold(),
        ]);
    }

    /**
     * Record Sale form.
     *
     * Route: GET /sales/new
     * The product dropdown is built from getSellable(), so it never offers
     * an archived product or one with zero stock.
     * Returns the rendered HTML of app/Views/sales/new.php
     */
    public function create()
    {
        return view('sales/new', [
            'title'     => 'Record Sale',
            'products'  => (new ProductModel())->getSellable(),
            'customers' => (new CustomerModel())->getActive(),
        ]);
    }

    /**
     * Record the sale and take the units off stock.
     *
     * Route: POST /sales/store
     *
     * The checks run in order, cheapest first:
     *   1. the form fields are present and sane
     *   2. the product exists and is not archived
     *   3. there is enough stock
     * Only then are the two writes made, inside a transaction.
     */
    public function store()
    {
        $rules = [
            // is_not_unique proves the id actually exists in the table,
            // so a hand-posted id cannot create a sale for a missing product.
            'product_id'  => 'required|is_natural_no_zero|is_not_unique[products.id]',
            'customer_id' => 'permit_empty|is_natural_no_zero|is_not_unique[customers.id]',
            'quantity'    => 'required|is_natural_no_zero|less_than_equal_to[9999]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $productModel = new ProductModel();

        $productId  = (int) $this->request->getPost('product_id');
        $quantity   = (int) $this->request->getPost('quantity');
        $customerId = $this->request->getPost('customer_id');
        $customerId = ($customerId === null || $customerId === '') ? null : (int) $customerId;

        $product = $productModel->find($productId);

        if ($product === null || (int) $product['is_archived'] === 1) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'That product is no longer available.');
        }

        // The stock check the activity asks for. Rejected with a message
        // that says what is actually available, so the cashier can adjust
        // instead of guessing.
        if ($quantity > (int) $product['stock_quantity']) {
            return redirect()->back()
                ->withInput()
                ->with('error', sprintf(
                    'Not enough stock. %s has only %d left, but %d were requested.',
                    $product['name'],
                    (int) $product['stock_quantity'],
                    $quantity
                ));
        }

        $total = round((float) $product['price'] * $quantity, 2);

        // Both writes or neither. Without this, a failure between them
        // would leave stock reduced with no sale to show for it.
        $db = db_connect();
        $db->transStart();

        (new SaleModel())->insert([
            'product_id'  => $productId,
            'customer_id' => $customerId,
            'sold_by'     => session()->get('user_id'),   // from the session, never the form
            'quantity'    => $quantity,
            'total_price' => $total,
        ]);

        $productModel->decreaseStock($productId, $quantity);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'The sale could not be saved. Nothing was changed.');
        }

        return redirect()->to(site_url('sales'))
            ->with('message', sprintf(
                'Sale recorded: %d x %s for %s. Stock is now %d.',
                $quantity,
                $product['name'],
                number_format($total, 2),
                (int) $product['stock_quantity'] - $quantity
            ));
    }
}
