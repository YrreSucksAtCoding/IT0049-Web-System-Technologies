<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\TaskModel;

/**
 * Profile controller
 *
 * Shows the single demo user stored in the users table.
 * Public — the activity keeps this page readable by anyone.
 */
class Profile extends BaseController
{
    /**
     * Profile page.
     *
     * Route: GET /profile (public)
     * Asks UserModel for the one demo record and TaskModel for a couple of
     * totals. The password hash is never passed to the view.
     * Returns the rendered HTML of app/Views/profile/index.php
     */
    public function index()
    {
        $userModel = new UserModel();
        $taskModel = new TaskModel();

        $user = $userModel->getDemoUser();

        return view('profile/index', [
            'title'      => 'Profile',
            'user'       => $user,
            'totalTasks' => count($taskModel->getAllTasks()),
            'todayTasks' => count($taskModel->getTodaysTasks()),
        ]);
    }
}
