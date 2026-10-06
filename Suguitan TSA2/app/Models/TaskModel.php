<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * TaskModel
 *
 * Represents the `tasks` table.
 *
 * Deleting is SOFT: the is_archived flag is set to 1 and the row stays in
 * the table. Every query that feeds a listing therefore has to exclude
 * archived rows, which is why that condition lives here in the model
 * instead of being remembered separately in each controller.
 */
class TaskModel extends Model
{
    protected $table      = 'tasks';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = ['title', 'status', 'task_date', 'is_archived', 'created_at'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';   // this table has no updated_at column

    /**
     * Get the tasks scheduled for today that have not been archived.
     *
     * Two where() clauses: one filters by date, one hides archived rows.
     * Used by the Welcome page. Returns an array of task records.
     */
    public function getTodaysTasks()
    {
        return $this->where('task_date', date('Y-m-d'))
                    ->where('is_archived', 0)
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }

    /**
     * Get every task that has not been archived, oldest date first.
     *
     * No date filter, but the archived rows are still excluded.
     * Used by the Task List page. Returns an array of task records.
     */
    public function getAllTasks()
    {
        return $this->where('is_archived', 0)
                    ->orderBy('task_date', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }

    /**
     * Get the tasks that HAVE been archived.
     *
     * This is what makes the soft delete visible: the rows are still here
     * and can be brought back. Returns an array of task records.
     */
    public function getArchivedTasks()
    {
        return $this->where('is_archived', 1)
                    ->orderBy('task_date', 'DESC')
                    ->findAll();
    }

    /**
     * Count today's non-archived tasks carrying a given status.
     *
     * Takes a status string such as 'done' or 'pending'.
     * Returns an integer, used for the summary on the Welcome page.
     */
    public function countTodayByStatus(string $status)
    {
        return $this->where('task_date', date('Y-m-d'))
                    ->where('is_archived', 0)
                    ->where('status', $status)
                    ->countAllResults();
    }

    /**
     * Archive one task — the soft delete.
     *
     * Takes the task id. Sets is_archived to 1 so the row disappears from
     * the listings while staying in the table, which means a mistaken
     * delete can be undone and old records are not lost.
     * Returns true on success.
     */
    public function archive(int $id)
    {
        return $this->update($id, ['is_archived' => 1]);
    }

    /**
     * Bring an archived task back into the listings.
     *
     * Takes the task id. Returns true on success.
     */
    public function restore(int $id)
    {
        return $this->update($id, ['is_archived' => 0]);
    }
}
