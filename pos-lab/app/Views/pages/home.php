<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <h2>Welcome</h2>

    <p>
        This is the first version of my Point-of-Sale system for IT0049.
        It has four pages and no database yet &mdash; the records on the
        Customer Accounts and User Accounts pages come from static PHP arrays.
    </p>

    <p>Use the links above to move between the pages.</p>

<?= $this->endSection() ?>
