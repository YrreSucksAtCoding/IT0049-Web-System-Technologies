<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="card">
        <h2>All Tasks</h2>
        <p class="sub">
            Every record in the tasks table, ordered by date.
            Total: <?= count($tasks) ?>. Today's rows are highlighted.
        </p>

        <?php if (empty($tasks)): ?>

            <p class="empty">No tasks found. Make sure the database was imported.</p>

        <?php else: ?>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Task</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- No date filter here — this is the unfiltered query -->
                    <?php foreach ($tasks as $task): ?>
                        <tr class="<?= $task['task_date'] === $today ? 'is-today' : '' ?>">
                            <td><?= esc($task['id']) ?></td>
                            <td><?= esc(date('M d, Y', strtotime($task['task_date']))) ?></td>
                            <td><?= esc($task['title']) ?></td>
                            <td>
                                <span class="badge badge-<?= esc($task['status']) ?>">
                                    <?= esc(ucwords(str_replace('_', ' ', $task['status']))) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>
    </div>

<?= $this->endSection() ?>
