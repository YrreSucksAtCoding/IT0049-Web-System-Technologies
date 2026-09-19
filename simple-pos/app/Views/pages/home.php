<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <h2>Welcome</h2>

    <p>
        This is my Point-of-Sale system for IT0049. It has four pages, and the
        Customer Accounts and User Accounts pages now read their records from a
        real MySQL database through CodeIgniter Models.
    </p>

    <p>Use the links above to move between the pages.</p>

<?= $this->endSection() ?>
