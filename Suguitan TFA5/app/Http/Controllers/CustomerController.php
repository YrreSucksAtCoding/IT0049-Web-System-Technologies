<?php

namespace App\Http\Controllers;

/**
 * CustomerController
 *
 * Shows the Customer Accounts page.
 * There is no database yet, so the records come from a static PHP array —
 * the same temporary data source used in TFA1, just in a Laravel controller.
 */
class CustomerController extends Controller
{
    /**
     * Customer Accounts page.
     *
     * Route: GET /customers
     * Builds a static array of customer records (our temporary "table"),
     * then hands it to the Blade view, which loops it with @foreach.
     * Returns the rendered resources/views/customers/index.blade.php
     */
    public function index()
    {
        // Temporary data source. Each item is one customer record, the same
        // shape a row from the database will have later.
        $customers = [
            ['full_name' => 'Ana Reyes',       'email' => 'ana.reyes@email.com',    'phone' => '0917-111-2233'],
            ['full_name' => 'Mark Villanueva', 'email' => 'mark.v@email.com',       'phone' => '0918-222-3344'],
            ['full_name' => 'Jenny Cruz',      'email' => 'jenny.cruz@email.com',   'phone' => '0919-333-4455'],
            ['full_name' => 'Paolo Santos',    'email' => 'paolo.santos@email.com', 'phone' => '0920-444-5566'],
            ['full_name' => 'Liza Domingo',    'email' => 'liza.domingo@email.com', 'phone' => '0921-555-6677'],
        ];

        return view('customers.index', [
            'title'     => 'Customer Accounts',
            'customers' => $customers,
        ]);
    }
}
