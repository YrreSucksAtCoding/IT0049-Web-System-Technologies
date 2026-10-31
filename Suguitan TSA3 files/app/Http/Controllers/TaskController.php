<?php

namespace App\Http\Controllers;

use App\Models\Task;

/**
 * TaskController
 *
 * Serves the two pages that list tasks. Both read through the same model;
 * the only difference is whether the today() scope is applied.
 */
class TaskController extends Controller
{
    /**
     * Welcome page — TODAY'S TASKS ONLY.
     *
     * Route: GET /
     * Query: SELECT * FROM tasks
     *        WHERE DATE(task_date) = <today>
     *        ORDER BY task_date, title
     *
     * The filtering is the scope on the model, not a condition written out
     * here, so "today" means the same thing everywhere in the application.
     */
    public function today()
    {
        $tasks = Task::today()->ordered()->get();

        return view('tasks.today', [
            'title' => 'Today',
            'tasks' => $tasks,
            'date'  => today(),

            // Counts for the small summary strip at the top of the page.
            'done'  => $tasks->where('status', 'completed')->count(),
        ]);
    }

    /**
     * Task List page — EVERY TASK, ordered by date.
     *
     * Route: GET /tasks
     * Query: SELECT * FROM tasks ORDER BY task_date, title
     *
     * groupBy() here runs on the Collection, not in SQL: the rows are
     * already loaded, and grouping them in PHP lets the view print one
     * heading per date without a second query.
     */
    public function index()
    {
        $tasks = Task::ordered()->get();

        // One group per date, keyed by a plain Y-m-d string.
        $byDate = $tasks->groupBy(fn ($task) => $task->task_date->toDateString());

        return view('tasks.index', [
            'title'    => 'All Tasks',
            'tasks'    => $tasks,
            'byDate'   => $byDate,
            'total'    => $tasks->count(),
            'dayCount' => $byDate->count(),
        ]);
    }
}
