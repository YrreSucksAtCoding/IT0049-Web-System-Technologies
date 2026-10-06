<?php

namespace App\Controllers;

use App\Models\TaskModel;

/**
 * Home controller
 *
 * The Welcome page: a dashboard of the tasks scheduled for today.
 * Public — anyone can read it, logged in or not.
 */
class Home extends BaseController
{
    /**
     * Welcome page.
     *
     * Route: GET / (public)
     * Asks TaskModel for today's tasks only. Archived tasks are excluded
     * by the model, so a "deleted" task disappears from here too.
     * Returns the rendered HTML of app/Views/tasks/today.php
     */
    public function index()
    {
        $taskModel = new TaskModel();
        $tasks     = $taskModel->getTodaysTasks();

        return view('tasks/today', [
            'title'      => 'Today',
            'today'      => date('l, F j, Y'),
            'tasks'      => $tasks,
            'doneCount'  => $taskModel->countTodayByStatus('done'),
            'totalCount' => count($tasks),
        ]);
    }
}
