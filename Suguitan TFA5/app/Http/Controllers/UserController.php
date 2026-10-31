<?php

namespace App\Http\Controllers;

/**
 * UserController
 *
 * Shows the User Accounts page — the staff who operate the POS.
 * Same idea as CustomerController: a static PHP array stands in for a
 * database table until the next activity replaces it.
 */
class UserController extends Controller
{
    /**
     * User Accounts page.
     *
     * Route: GET /users
     * Builds a static array of staff records and hands it to the Blade
     * view, which loops it with @foreach.
     * Returns the rendered resources/views/users/index.blade.php
     */
    public function index()
    {
        // Temporary data source: one item = one user/staff account.
        $users = [
            ['username' => 'admin01',   'full_name' => 'Yrre Suguitan', 'role' => 'Administrator'],
            ['username' => 'cashier01', 'full_name' => 'Bea Lopez',     'role' => 'Cashier'],
            ['username' => 'cashier02', 'full_name' => 'Ken Tolentino', 'role' => 'Cashier'],
            ['username' => 'stock01',   'full_name' => 'Rina Bautista', 'role' => 'Stock Clerk'],
            ['username' => 'manager01', 'full_name' => 'Carlo Mendoza', 'role' => 'Manager'],
        ];

        return view('users.index', [
            'title' => 'User Accounts',
            'users' => $users,
        ]);
    }
}
