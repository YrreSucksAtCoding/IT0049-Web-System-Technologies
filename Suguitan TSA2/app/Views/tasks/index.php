<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="card">
        <div class="toolbar">
            <div>
                <h2>All Tasks</h2>
                <p class="sub">
                    Every task in the table that has not been archived.
                    Total: <?= count($tasks) ?>. Today's rows are highlighted.
                </p>
            </div>
            <?php if (session()->get('logged_in') === true): ?>
                <div class="toolbar-actions">
                    <a href="<?= site_url('tasks/new') ?>" class="btn btn-primary">+ New Task</a>
                    <a href="<?= site_url('tasks/archived') ?>" class="btn btn-light">Archived</a>
                </div>
            <?php endif; ?>
        </div>

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
                        <?php if (session()->get('logged_in') === true): ?><th>Actions</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
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

                            <?php if (session()->get('logged_in') === true): ?>
                                <td>
                                    <div class="row-actions">
                                        <a class="btn btn-light btn-small"
                                           href="<?= site_url('tasks/edit/' . $task['id']) ?>">Edit</a>

                                        <!--
                                            Delete is a POST form with a CSRF token, not a link.
                                            A plain link can be triggered by anything that loads
                                            the URL. It archives the row rather than removing it.
                                        -->
                                        <form action="<?= site_url('tasks/delete/' . $task['id']) ?>" method="post">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-danger btn-small">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>
    </div>

<?= $this->endSection() ?>
