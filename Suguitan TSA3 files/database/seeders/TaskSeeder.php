<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

/**
 * Seeds 10 tasks across 4 different dates.
 *
 * The activity requires at least 8 tasks on at least 3 dates, one of which
 * must be today. The dates are calculated relative to today() rather than
 * typed in, so the Welcome page still has something to show whenever this
 * seeder is run — a hard-coded date would be wrong by tomorrow.
 */
class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $today     = today();
        $yesterday = today()->subDay();
        $tomorrow  = today()->addDay();
        $later     = today()->addDays(2);

        $tasks = [
            // --- today (4) : what the Welcome page shows -----------------
            ['title' => 'Finish the TSA3 Laravel rebuild',      'status' => 'in progress', 'task_date' => $today],
            ['title' => 'Read the Laravel docs on query scopes','status' => 'completed',   'task_date' => $today],
            ['title' => 'Submit the IT0049 activity online',    'status' => 'pending',     'task_date' => $today],
            ['title' => 'Push the project to GitHub',           'status' => 'pending',     'task_date' => $today],

            // --- yesterday (2) ------------------------------------------
            ['title' => 'Create the tsa3_tasks database',       'status' => 'completed',   'task_date' => $yesterday],
            ['title' => 'Plan the tasks table schema',          'status' => 'completed',   'task_date' => $yesterday],

            // --- tomorrow (3) -------------------------------------------
            ['title' => 'Set up hosting for the project',       'status' => 'pending',     'task_date' => $tomorrow],
            ['title' => 'Review for the IT0049 quiz',           'status' => 'pending',     'task_date' => $tomorrow],
            ['title' => 'Check the group capstone timeline',    'status' => 'pending',     'task_date' => $tomorrow],

            // --- two days out (1) ---------------------------------------
            ['title' => 'Write the README for the repository',  'status' => 'pending',     'task_date' => $later],
        ];

        // Seeded through the model so created_at and updated_at are filled.
        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
