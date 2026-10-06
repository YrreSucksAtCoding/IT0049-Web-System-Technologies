<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <h2>About This Project</h2>

    <p>
        A CodeIgniter 4 Point-of-Sale application built for IT0049 &mdash;
        Web System Technologies. It started as routes, controllers and views,
        gained a MySQL database, and now handles forms, validation and file
        upload.
    </p>

    <ul>
        <li><strong>Route</strong> &mdash; decides which controller method runs for a URL.</li>
        <li><strong>Controller</strong> &mdash; validates input, asks a Model for data, picks the view.</li>
        <li><strong>Model</strong> &mdash; wraps one database table and runs the query.</li>
        <li><strong>View</strong> &mdash; turns the records into the HTML you see.</li>
    </ul>

    <h3>What is new in this version</h3>

    <ul>
        <li>New and Edit forms for customer and user accounts.</li>
        <li>Server-side validation. A rejected form comes back with the error
            messages <em>and</em> everything the user already typed.</li>
        <li>Profile picture upload for user accounts: JPG or PNG up to 2 MB,
            resized to a 200&times;200 thumbnail, stored in
            <code>public/uploads/avatars</code>.</li>
        <li>Only the filename is saved in the <code>avatar</code> column.
            The listing page adds the folder back and falls back to a
            placeholder when no picture was uploaded.</li>
    </ul>

<?= $this->endSection() ?>
