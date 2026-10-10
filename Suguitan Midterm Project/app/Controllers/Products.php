<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Libraries\ImageUploader;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Products controller
 *
 * Product management: list, add, edit, archive, restore.
 * All routes are inside the 'auth' filtered group.
 *
 * "Delete" archives the row. A product named on a past sale cannot be
 * removed without breaking the foreign key, and removing it would erase
 * what that sale was for.
 */
class Products extends BaseController
{
    /** Folder under public/uploads where product images are stored. */
    private const FOLDER = 'products';

    /**
     * Validation rules for the product form.
     *
     * Takes the id being edited, or null when adding. The image rules are
     * added only when a file was actually chosen, which keeps the upload
     * optional without letting a bad file through unchecked.
     * Returns the rules array used by $this->validate().
     */
    private function rules(?int $id = null): array
    {
        $uniqueName = $id === null
            ? 'is_unique[products.name]'
            : 'is_unique[products.name,id,' . $id . ']';

        $rules = [
            'name'           => 'required|min_length[2]|max_length[100]|' . $uniqueName,
            'price'          => 'required|decimal|greater_than[0]',
            'stock_quantity' => 'required|is_natural',   // zero is allowed, negative is not
        ];

        $image = $this->request->getFile('image');

        if (ImageUploader::wasSubmitted($image)) {
            $rules['image'] = ImageUploader::rulesFor('image');
        }

        return $rules;
    }

    /**
     * Product list.
     *
     * Route: GET /products
     * Returns the rendered HTML of app/Views/products/index.php
     */
    public function index()
    {
        return view('products/index', [
            'title'    => 'Products',
            'products' => (new ProductModel())->getActive(),
        ]);
    }

    /**
     * Blank New Product form.
     *
     * Route: GET /products/new
     * Returns the rendered HTML of app/Views/products/form.php
     */
    public function create()
    {
        return view('products/form', [
            'title'   => 'New Product',
            'heading' => 'Add a Product',
            'product' => null,
            'action'  => site_url('products/store'),
        ]);
    }

    /**
     * Save a brand new product.
     *
     * Route: POST /products/store
     * A failed validation redirects back with the errors AND the values
     * the user typed, so nothing they entered is lost.
     */
    public function store()
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'           => $this->request->getPost('name'),
            'price'          => $this->request->getPost('price'),
            'stock_quantity' => $this->request->getPost('stock_quantity'),
            'is_archived'    => 0,
        ];

        $image = ImageUploader::store($this->request->getFile('image'), self::FOLDER);

        if ($image !== null) {
            $data['image'] = $image;   // the filename only, never the path
        }

        (new ProductModel())->insert($data);

        return redirect()->to(site_url('products'))->with('message', 'Product added.');
    }

    /**
     * Edit form, pre-filled with the existing product.
     *
     * Route: GET /products/edit/5
     * Returns the rendered HTML of app/Views/products/form.php
     */
    public function edit(int $id)
    {
        $product = (new ProductModel())->find($id);

        if ($product === null) {
            throw PageNotFoundException::forPageNotFound('No product with id ' . $id);
        }

        return view('products/form', [
            'title'   => 'Edit Product',
            'heading' => 'Edit ' . $product['name'],
            'product' => $product,
            'action'  => site_url('products/update/' . $id),
        ]);
    }

    /**
     * Save changes to an existing product.
     *
     * Route: POST /products/update/5
     * The id goes to update(), so the row changes rather than a second
     * copy of it being inserted. An empty file input keeps the old image.
     */
    public function update(int $id)
    {
        $productModel = new ProductModel();
        $product      = $productModel->find($id);

        if ($product === null) {
            throw PageNotFoundException::forPageNotFound('No product with id ' . $id);
        }

        if (! $this->validate($this->rules($id))) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'           => $this->request->getPost('name'),
            'price'          => $this->request->getPost('price'),
            'stock_quantity' => $this->request->getPost('stock_quantity'),
        ];

        $image = ImageUploader::store(
            $this->request->getFile('image'),
            self::FOLDER,
            $product['image'] ?? null
        );

        if ($image !== null) {
            $data['image'] = $image;
        }

        $productModel->update($id, $data);

        return redirect()->to(site_url('products'))->with('message', 'Product updated.');
    }

    /**
     * Archive a product — the soft delete.
     *
     * Route: POST /products/delete/5
     * Nothing is removed. The row leaves the product list but any sale
     * that names it stays readable.
     */
    public function delete(int $id)
    {
        $productModel = new ProductModel();

        if ($productModel->find($id) === null) {
            throw PageNotFoundException::forPageNotFound('No product with id ' . $id);
        }

        $productModel->archive($id);

        return redirect()->to(site_url('products'))
            ->with('message', 'Product archived. Past sales still show it.');
    }

    /**
     * Archived products.
     *
     * Route: GET /products/archived
     * Returns the rendered HTML of app/Views/products/archived.php
     */
    public function archived()
    {
        return view('products/archived', [
            'title'    => 'Archived Products',
            'products' => (new ProductModel())->getArchived(),
        ]);
    }

    /**
     * Put an archived product back on the list.
     *
     * Route: POST /products/restore/5
     */
    public function restore(int $id)
    {
        (new ProductModel())->restore($id);

        return redirect()->to(site_url('products/archived'))->with('message', 'Product restored.');
    }
}
