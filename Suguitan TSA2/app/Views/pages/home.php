<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <h2>Welcome</h2>

    <p>
        This is my Point-of-Sale system for IT0049. Customer and user accounts
        are stored in a MySQL database, and both can now be created and edited
        through validated forms. User accounts also support a profile picture
        upload.
    </p>

    <p>Use the links above to move between the pages.</p>

<?= $this->endSection() ?>
