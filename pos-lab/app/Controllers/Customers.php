<?php

namespace App\Controllers;

/**
 * Customers controller
 *
 * Shows the Customer Accounts page.
 * There is no database yet, so the records come from a static PHP array.
 */
class Customers extends BaseController
{
    /**
     * Customer Accounts page.
     *
     * Route: GET /customers
     * Builds a static array of customer records (our temporary "table"),
     * then passes it to the view so the view can loop through it.
     * Returns the rendered HTML of app/Views/customers/index.php
     */
    public function index()
    {
        // Temporary data source. Each item is one customer record,
        // just like one row would be in a database table.
        $customers = [
            ['full_name' => 'Ana Reyes',        'email' => 'ana.reyes@email.com',     'phone' => '0917-111-2233'],
            ['full_name' => 'Mark Villanueva',  'email' => 'mark.v@email.com',        'phone' => '0918-222-3344'],
            ['full_name' => 'Jenny Cruz',       'email' => 'jenny.cruz@email.com',    'phone' => '0919-333-4455'],
            ['full_name' => 'Paolo Santos',     'email' => 'paolo.santos@email.com',  'phone' => '0920-444-5566'],
            ['full_name' => 'Liza Domingo',     'email' => 'liza.domingo@email.com',  'phone' => '0921-555-6677'],
        ];

        // Send the page title and the whole array to the view.
        $data = [
            'title'     => 'Customer Accounts',
            'customers' => $customers,
        ];

        return view('customers/index', $data);
    }
}
