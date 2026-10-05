<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="card">
        <h2>Tasks for Today</h2>
        <p class="sub"><?= esc($today) ?></p>

        <div class="summary">
            <div class="stat">
                <b><?= $totalCount ?></b>
                <span>scheduled today</span>
            </div>
            <div class="stat">
                <b><?= $doneCount ?></b>
                <span>already done</span>
            </div>
            <div class="stat">
                <b><?= $totalCount - $doneCount ?></b>
                <span>still open</span>
            </div>
        </div>

        <?php if (empty($tasks)): ?>

            <p class="empty">No tasks scheduled for today.</p>

        <?php else: ?>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Task</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Only today's rows: TaskModel filtered them with where('task_date', today) -->
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?= esc($task['id']) ?></td>
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
