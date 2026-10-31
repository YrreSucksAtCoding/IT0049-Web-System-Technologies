<?php

namespace App\Http\Controllers;

use App\Models\User;

/**
 * UserController
 *
 * Same change as CustomerController: the static array of staff accounts
 * is replaced by a query through the User model.
 */
class UserController extends Controller
{
    /**
     * User Accounts page.
     *
     * Route: GET /users
     * Returns the rendered resources/views/users/index.blade.php
     */
    public function index()
    {
        // SELECT * FROM users ORDER BY username ASC
        $users = User::orderBy('username')->get();

        return view('users.index', [
            'title' => 'User Accounts',
            'users' => $users,
        ]);
    }
}
