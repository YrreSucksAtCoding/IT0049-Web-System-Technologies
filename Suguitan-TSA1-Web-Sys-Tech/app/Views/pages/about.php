<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="card">
        <h2>About This System</h2>
        <p class="sub">Technical Summative Assessment 1</p>

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

        <h3 style="margin-top: 28px;">How it works</h3>
        <p>
            The application follows the MVC pattern. Routes map each URL to a
            controller method, the controllers ask a Model for the records they
            need, and the views turn those records into HTML.
        </p>
        <p>
            The Today page and the All Tasks page both read from the same
            <code>tasks</code> table through the same <code>TaskModel</code>.
            The only difference is the query: the Today page adds a
            <code>where('task_date', ...)</code> clause for the current date,
            while the All Tasks page runs without that filter.
        </p>
    </div>

<?= $this->endSection() ?>
