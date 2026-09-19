<?php

namespace App\Controllers;

use App\Models\UserModel;

/**
 * Users controller
 *
 * Shows the User Accounts page (the staff who operate the POS).
 * The static array from TFA1 is gone — records now come from
 * the `users` table through UserModel.
 */
class Users extends BaseController
{
    /**
     * User Accounts page.
     *
     * Route: GET /users
     * Asks UserModel for every user record and passes the list
     * to the view, which loops through it exactly as before.
     * Returns the rendered HTML of app/Views/users/index.php
     */
    public function index()
    {
        // Create the model. This opens the database connection for us.
        $userModel = new UserModel();

        $data = [
            'title' => 'User Accounts',
            // Query Builder through the model, not raw SQL.
            'users' => $userModel->getAllUsers(),
        ];

        return view('users/index', $data);
    }
}
