<?php

namespace App\Controllers;

use App\Models\TaskModel;

/**
 * Tasks controller
 *
 * The full Task List page. Same table as the Welcome page,
 * but with no date filter — every record is shown.
 */
class Tasks extends BaseController
{
    /**
     * Task List page.
     *
     * Route: GET /tasks
     * Asks TaskModel for every task, ordered by date, and passes the
     * list to the view.
     * Returns the rendered HTML of app/Views/tasks/index.php
     */
    public function index()
    {
        $taskModel = new TaskModel();

        $data = [
            'title' => 'All Tasks',
            // No where() this time — the unfiltered query.
            'tasks' => $taskModel->getAllTasks(),
            'today' => date('Y-m-d'),   // lets the view highlight today's rows
        ];

        return view('tasks/index', $data);
    }
}
