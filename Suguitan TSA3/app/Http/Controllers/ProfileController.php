<?php

namespace App\Http\Controllers;

use App\Models\User;

/**
 * ProfileController
 *
 * Shows the single user record from the users table.
 */
class ProfileController extends Controller
{
    /**
     * Profile page.
     *
     * Route: GET /profile
     * Query: SELECT * FROM users ORDER BY id ASC LIMIT 1
     *
     * first() returns one model or null. It is used instead of all()
     * because this page displays one record, and because a null is easy
     * for the view to handle with @if — which is what happens if the
     * seeder has not been run yet.
     */
    public function index()
    {
        $user = User::first();

        return view('profile.index', [
            'title' => 'Profile',
            'user'  => $user,
        ]);
    }
}
