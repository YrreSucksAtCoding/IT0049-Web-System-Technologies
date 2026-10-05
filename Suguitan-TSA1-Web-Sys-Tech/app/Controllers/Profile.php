<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\TaskModel;

/**
 * Profile controller
 *
 * Shows the single demo user stored in the users table.
 */
class Profile extends BaseController
{
    /**
     * Profile page.
     *
     * Route: GET /profile
     * Asks UserModel for the one demo record, and TaskModel for a
     * couple of totals so the profile shows some activity.
     * Returns the rendered HTML of app/Views/profile/index.php
     */
    public function index()
    {
        $userModel = new UserModel();
        $taskModel = new TaskModel();

        $data = [
            'title'      => 'Profile',
            // first() gives one record, not a list.
            'user'       => $userModel->getDemoUser(),
            'totalTasks' => $taskModel->countAllResults(),
            'todayTasks' => count($taskModel->getTodaysTasks()),
        ];

        return view('profile/index', $data);
    }
}
