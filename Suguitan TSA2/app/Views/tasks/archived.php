<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="card">
        <div class="toolbar">
            <div>
                <h2>Archived Tasks</h2>
                <p class="sub">
                    These rows are still in the database with is_archived set to 1.
                    They are hidden from the Welcome and Task List pages, not deleted.
                </p>
            </div>
            <div class="toolbar-actions">
                <a href="<?= site_url('tasks') ?>" class="btn btn-light">Back to All Tasks</a>
            </div>
        </div>

        <?php if (empty($tasks)): ?>

            <p class="empty">Nothing archived yet.</p>

        <?php else: ?>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?= esc($task['id']) ?></td>
                            <td><?= esc(date('M d, Y', strtotime($task['task_date']))) ?></td>
                            <td><?= esc($task['title']) ?></td>
                            <td>
                                <span class="badge badge-<?= esc($task['status']) ?>">
                                    <?= esc(ucwords(str_replace('_', ' ', $task['status']))) ?>
                                </span>
                            </td>
                            <td>
                                <!-- the row was never removed, so it can simply be un-flagged -->
                                <form action="<?= site_url('tasks/restore/' . $task['id']) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-light btn-small">Restore</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>
    </div>

<?= $this->endSection() ?>
