<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <h2>About This Project</h2>

    <p>
        This project is a four-page CodeIgniter 4 application built to practice
        the MVC pattern: routes, controllers, and views.
    </p>

    <ul>
        <li><strong>Route</strong> &mdash; decides which controller method runs for a URL.</li>
        <li><strong>Controller</strong> &mdash; prepares the data and picks the view.</li>
        <li><strong>View</strong> &mdash; turns that data into the HTML you see.</li>
    </ul>

    <p>
        The data layer (a real database) comes in the next module. For now,
        static PHP arrays act as temporary tables.
    </p>

<?= $this->endSection() ?>
