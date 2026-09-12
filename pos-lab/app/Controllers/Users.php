<?php

namespace App\Controllers;

/**
 * Users controller
 *
 * Shows the User Accounts page (the staff who log into the POS).
 * Same idea as Customers: a static PHP array stands in for a database table.
 */
class Users extends BaseController
{
    /**
     * User Accounts page.
     *
     * Route: GET /users
     * Builds a static array of staff records, then passes it to the view
     * so the view can loop through it with a foreach.
     * Returns the rendered HTML of app/Views/users/index.php
     */
    public function index()
    {
        // Temporary data source: one item = one user/staff account.
        $users = [
            ['username' => 'admin01',   'full_name' => 'Yrre Suguitan',    'role' => 'Administrator'],
            ['username' => 'cashier01', 'full_name' => 'Bea Lopez',        'role' => 'Cashier'],
            ['username' => 'cashier02', 'full_name' => 'Ken Tolentino',    'role' => 'Cashier'],
            ['username' => 'stock01',   'full_name' => 'Rina Bautista',    'role' => 'Stock Clerk'],
            ['username' => 'manager01', 'full_name' => 'Carlo Mendoza',    'role' => 'Manager'],
        ];

        $data = [
            'title' => 'User Accounts',
            'users' => $users,
        ];

        return view('users/index', $data);
    }
}
