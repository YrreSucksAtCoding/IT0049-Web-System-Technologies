<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="card">
        <h2><?= esc($heading) ?></h2>
        <p class="sub">Title and date are required.</p>

        <!--
            One form serves both Add and Edit.
            $task is null when adding, so each old() call falls back to an
            empty string. old() returns what the user typed if validation
            just failed, which is what stops a rejected form wiping their work.
        -->
        <form action="<?= esc($action, 'attr') ?>" method="post">
            <?= csrf_field() ?>

            <div class="field">
                <label for="title">Task Title</label>
                <input type="text" id="title" name="title"
                       value="<?= esc(old('title', $task['title'] ?? '')) ?>">
            </div>

            <div class="field">
                <label for="task_date">Date</label>
                <input type="date" id="task_date" name="task_date"
                       value="<?= esc(old('task_date', $task['task_date'] ?? date('Y-m-d'))) ?>">
                <div class="hint">A task dated today appears on the Welcome page.</div>
            </div>

            <div class="field">
                <label for="status">Status</label>
                <?php $current = old('status', $task['status'] ?? 'pending'); ?>
                <select id="status" name="status">
                    <option value="pending"     <?= $current === 'pending'     ? 'selected' : '' ?>>Pending</option>
                    <option value="in_progress" <?= $current === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="done"        <?= $current === 'done'        ? 'selected' : '' ?>>Done</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <?= $task === null ? 'Add Task' : 'Save Changes' ?>
                </button>
                <a href="<?= site_url('tasks') ?>" class="btn btn-light">Cancel</a>
            </div>
        </form>
    </div>

<?= $this->endSection() ?>
