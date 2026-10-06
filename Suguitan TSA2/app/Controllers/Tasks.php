<?php

namespace App\Controllers;

use App\Models\TaskModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Tasks controller
 *
 * index() is public — anyone can read the task list.
 * Everything else changes data and sits behind the 'auth' filter, which is
 * applied to the route group in app/Config/Routes.php.
 */
class Tasks extends BaseController
{
    /**
     * Validation rules for the task form.
     *
     * The activity requires title and task_date; status is restricted to
     * the three values the badges know how to draw.
     * Returns the rules array used by $this->validate().
     */
    private function rules(): array
    {
        return [
            'title'     => 'required|min_length[3]|max_length[150]',
            'task_date' => 'required|valid_date[Y-m-d]',
            'status'    => 'required|in_list[pending,in_progress,done]',
        ];
    }

    /**
     * Task List page — every task that has not been archived.
     *
     * Route: GET /tasks (public)
     * Returns the rendered HTML of app/Views/tasks/index.php
     */
    public function index()
    {
        $taskModel = new TaskModel();

        return view('tasks/index', [
            'title' => 'All Tasks',
            'tasks' => $taskModel->getAllTasks(),
            'today' => date('Y-m-d'),
        ]);
    }

    /**
     * Blank New Task form.
     *
     * Route: GET /tasks/new (requires login)
     * Passing task = null makes the shared form render empty.
     * Returns the rendered HTML of app/Views/tasks/form.php
     */
    public function create()
    {
        return view('tasks/form', [
            'title'   => 'New Task',
            'heading' => 'Add a Task',
            'task'    => null,
            'action'  => site_url('tasks/store'),
        ]);
    }

    /**
     * Save a brand new task.
     *
     * Route: POST /tasks/store (requires login)
     * Validates first; a failure redirects back with the errors AND the
     * values the user typed, so nothing they entered is lost.
     */
    public function store()
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        (new TaskModel())->insert([
            'title'       => $this->request->getPost('title'),
            'status'      => $this->request->getPost('status'),
            'task_date'   => $this->request->getPost('task_date'),
            'is_archived' => 0,
        ]);

        return redirect()->to(site_url('tasks'))
            ->with('message', 'Task added successfully.');
    }

    /**
     * Edit form, pre-filled with the existing task.
     *
     * Route: GET /tasks/edit/5 (requires login)
     * Throws a 404 when the id does not exist, rather than letting the
     * view crash on a null record.
     * Returns the rendered HTML of app/Views/tasks/form.php
     */
    public function edit(int $id)
    {
        $task = (new TaskModel())->find($id);

        if ($task === null) {
            throw PageNotFoundException::forPageNotFound('No task with id ' . $id);
        }

        return view('tasks/form', [
            'title'   => 'Edit Task',
            'heading' => 'Edit Task #' . $id,
            'task'    => $task,
            'action'  => site_url('tasks/update/' . $id),
        ]);
    }

    /**
     * Save changes to an existing task.
     *
     * Route: POST /tasks/update/5 (requires login)
     * The id is passed to update() so the row is changed rather than a
     * second copy of it being inserted.
     */
    public function update(int $id)
    {
        $taskModel = new TaskModel();

        if ($taskModel->find($id) === null) {
            throw PageNotFoundException::forPageNotFound('No task with id ' . $id);
        }

        if (! $this->validate($this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $taskModel->update($id, [
            'title'     => $this->request->getPost('title'),
            'status'    => $this->request->getPost('status'),
            'task_date' => $this->request->getPost('task_date'),
        ]);

        return redirect()->to(site_url('tasks'))
            ->with('message', 'Task updated successfully.');
    }

    /**
     * "Delete" a task — a SOFT delete.
     *
     * Route: POST /tasks/delete/5 (requires login)
     * Nothing is removed from the database. The is_archived flag is set,
     * which takes the task out of the Welcome and Task List pages while
     * keeping the record, so a mistake can be undone.
     */
    public function delete(int $id)
    {
        $taskModel = new TaskModel();

        if ($taskModel->find($id) === null) {
            throw PageNotFoundException::forPageNotFound('No task with id ' . $id);
        }

        $taskModel->archive($id);

        return redirect()->to(site_url('tasks'))
            ->with('message', 'Task archived. It is still in the database and can be restored.');
    }

    /**
     * Archived tasks.
     *
     * Route: GET /tasks/archived (requires login)
     * Shows the rows hidden by the soft delete, which is what makes the
     * difference between archiving and deleting visible.
     * Returns the rendered HTML of app/Views/tasks/archived.php
     */
    public function archived()
    {
        return view('tasks/archived', [
            'title' => 'Archived Tasks',
            'tasks' => (new TaskModel())->getArchivedTasks(),
        ]);
    }

    /**
     * Bring an archived task back.
     *
     * Route: POST /tasks/restore/5 (requires login)
     * Clears the is_archived flag so the task reappears in the listings.
     */
    public function restore(int $id)
    {
        $taskModel = new TaskModel();

        if ($taskModel->find($id) === null) {
            throw PageNotFoundException::forPageNotFound('No task with id ' . $id);
        }

        $taskModel->restore($id);

        return redirect()->to(site_url('tasks/archived'))
            ->with('message', 'Task restored.');
    }
}
