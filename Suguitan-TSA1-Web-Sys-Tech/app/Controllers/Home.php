<?php

namespace App\Controllers;

use App\Models\TaskModel;

/**
 * Home controller
 *
 * The Welcome page: a dashboard showing only the tasks scheduled
 * for today, plus a small count of how they are going.
 */
class Home extends BaseController
{
    /**
     * Welcome page.
     *
     * Route: GET /
     * Asks TaskModel for today's tasks only (filtered by task_date),
     * plus counts for the summary line.
     * Returns the rendered HTML of app/Views/tasks/today.php
     */
    public function index()
    {
        $taskModel = new TaskModel();

        // Only today's rows — this is the filtered query.
        $tasks = $taskModel->getTodaysTasks();

        $data = [
            'title'       => 'Today',
            'today'       => date('l, F j, Y'),   // e.g. Friday, September 26, 2026
            'tasks'       => $tasks,
            'doneCount'   => $taskModel->countTodayByStatus('done'),
            'totalCount'  => count($tasks),
        ];

        return view('tasks/today', $data);
    }
}
