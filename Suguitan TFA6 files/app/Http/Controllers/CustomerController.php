<?php

namespace App\Http\Controllers;

use App\Models\Customer;

/**
 * CustomerController
 *
 * The TFA5 version of this file held a static array of five customers.
 * That array is gone. The records now come from the database through the
 * Customer model, and the view it hands them to did not have to change.
 */
class CustomerController extends Controller
{
    /**
     * Customer Accounts page.
     *
     * Route: GET /customers
     * Fetches every customer through Eloquent, then passes the collection
     * to the Blade view.
     * Returns the rendered resources/views/customers/index.blade.php
     */
    public function index()
    {
        // Customer::orderBy(...)->get() runs:
        //     SELECT * FROM customers ORDER BY full_name ASC
        // and returns a Collection of Customer objects.
        // Customer::all() would work too, but then the order is whatever
        // MySQL feels like returning.
        $customers = Customer::orderBy('full_name')->get();

        return view('customers.index', [
            'title'     => 'Customer Accounts',
            'customers' => $customers,
        ]);
    }
}
