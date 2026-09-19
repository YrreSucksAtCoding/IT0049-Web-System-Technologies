<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <h2>About This Project</h2>

    <p>
        This project is a four-page CodeIgniter 4 application. It started as a
        routing, controller, and view exercise, and now it is backed by a MySQL
        database.
    </p>

    <ul>
        <li><strong>Route</strong> &mdash; decides which controller method runs for a URL.</li>
        <li><strong>Controller</strong> &mdash; asks a Model for the data and picks the view.</li>
        <li><strong>Model</strong> &mdash; wraps one database table and runs the query.</li>
        <li><strong>View</strong> &mdash; turns the records into the HTML you see.</li>
    </ul>

    <p>
        The static PHP arrays from the first version are gone. The controllers now
        call <code>CustomerModel</code> and <code>UserModel</code>, which use
        CodeIgniter's Query Builder instead of raw SQL. The views did not change
        much &mdash; they still loop through a list of records with a foreach.
    </p>

<?= $this->endSection() ?>
