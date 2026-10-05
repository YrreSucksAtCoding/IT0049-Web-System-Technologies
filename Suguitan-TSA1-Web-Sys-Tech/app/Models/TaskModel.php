<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * TaskModel
 *
 * Represents the `tasks` table.
 * Both the Welcome page and the Task List page read from this one model —
 * only the query is different, which is the whole point of the activity.
 */
class TaskModel extends Model
{
    // Which table this model talks to.
    protected $table = 'tasks';

    // The primary key column.
    protected $primaryKey = 'id';

    // Records come back as associative arrays, so views use $task['title'].
    protected $returnType = 'array';

    // Columns insert() and update() are allowed to write to.
    protected $allowedFields = ['title', 'status', 'task_date', 'created_at'];

    // Let CodeIgniter fill created_at automatically on insert.
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';   // this table has no updated_at column

    /**
     * Get only the tasks scheduled for today.
     *
     * Uses a Query Builder where() clause comparing task_date to the
     * server's current date. Used by the Welcome page.
     * Returns an array of task records.
     */
    public function getTodaysTasks()
    {
        return $this->where('task_date', date('Y-m-d'))
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }

    /**
     * Get every task, no date filter.
     *
     * Ordered by date so the list reads as a timeline. Used by the
     * Task List page.
     * Returns an array of task records.
     */
    public function getAllTasks()
    {
        return $this->orderBy('task_date', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }

    /**
     * Count how many of today's tasks carry a given status.
     *
     * Takes a status string such as 'done' or 'pending'.
     * Returns an integer, used for the small summary on the Welcome page.
     */
    public function countTodayByStatus(string $status)
    {
        return $this->where('task_date', date('Y-m-d'))
                    ->where('status', $status)
                    ->countAllResults();
    }
}
