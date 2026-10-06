<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="card">
        <h2>About This System</h2>
        <p class="sub">Technical Summative Assessment 2</p>

        <p>
            The Tasks for Today Management System is a small internal tool for
            tracking daily to-do items. It was built with CodeIgniter 4 and MySQL
            as part of IT0049 &mdash; Web System Technologies.
        </p>

        <dl>
            <dt>Developer</dt>
            <dd>Yrre Suguitan</dd>

            <dt>Section</dt>
            <dd>&mdash;</dd>

            <dt>Professor</dt>
            <dd>&mdash;</dd>

            <dt>Built With</dt>
            <dd>CodeIgniter 4, MySQL, PHP</dd>
        </dl>

        <h3 style="margin-top: 28px;">What this version adds</h3>

        <ul>
            <li><strong>Full CRUD for tasks</strong> &mdash; New and Edit forms with
                server-side validation, and an archive action.</li>
            <li><strong>Authentication</strong> &mdash; reading stays open to anyone,
                but creating, editing and deleting a task requires a login. The check
                is a CodeIgniter Filter applied to a route group, so it is written
                once rather than repeated in every method.</li>
            <li><strong>Soft deletion</strong> &mdash; "Delete" sets an
                <code>is_archived</code> flag instead of removing the row. The task
                disappears from the Welcome and Task List pages, but the record
                survives and can be restored.</li>
        </ul>

        <h3 style="margin-top: 28px;">How it works</h3>
        <p>
            Routes map each URL to a controller method, the controllers ask a Model
            for the records they need, and the views turn those records into HTML.
        </p>
        <p>
            The Today page and the All Tasks page read the same <code>tasks</code>
            table through the same <code>TaskModel</code>. Today adds a
            <code>where('task_date', ...)</code> clause; All Tasks does not. Both
            add <code>where('is_archived', 0)</code>, which is what makes an
            archived task vanish from the listings without being deleted.
        </p>
    </div>

<?= $this->endSection() ?>
