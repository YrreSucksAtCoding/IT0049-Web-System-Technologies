<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="card">
        <div class="toolbar">
            <div>
                <h2>Tasks for Today</h2>
                <p class="sub"><?= esc($today) ?></p>
            </div>
            <?php if (session()->get('logged_in') === true): ?>
                <div class="toolbar-actions">
                    <a href="<?= site_url('tasks/new') ?>" class="btn btn-primary">+ New Task</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="summary">
            <div class="stat"><b><?= $totalCount ?></b><span>scheduled today</span></div>
            <div class="stat"><b><?= $doneCount ?></b><span>already done</span></div>
            <div class="stat"><b><?= $totalCount - $doneCount ?></b><span>still open</span></div>
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
                        <?php if (session()->get('logged_in') === true): ?><th></th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <!-- today's rows only, and archived ones are already excluded by the model -->
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?= esc($task['id']) ?></td>
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
