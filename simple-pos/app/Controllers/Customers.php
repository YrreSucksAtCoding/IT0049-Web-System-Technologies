<?php

namespace App\Controllers;

use App\Models\CustomerModel;

/**
 * Customers controller
 *
 * Shows the Customer Accounts page.
 * In TFA1 the records came from a static PHP array.
 * Now they come from the `customers` table through CustomerModel.
 */
class Customers extends BaseController
{
    /**
     * Customer Accounts page.
     *
     * Route: GET /customers
     * Asks CustomerModel for every customer record, then passes
     * that list to the view — the same shape the static array had.
     * Returns the rendered HTML of app/Views/customers/index.php
     */
    public function index()
    {
        // Create the model. This opens the database connection for us.
        $customerModel = new CustomerModel();

        $data = [
            'title'     => 'Customer Accounts',
            // findAll() through the model, no raw SQL anywhere.
            'customers' => $customerModel->getAllCustomers(),
        ];

        return view('customers/index', $data);
    }
}
