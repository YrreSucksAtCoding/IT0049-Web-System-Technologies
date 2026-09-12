<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <h2>User Accounts</h2>
    <p>Total users: <?= count($users) ?></p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            <?php $row = 1; ?>

            <!-- Loop through the static array sent by Users::index() -->
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $row++ ?></td>
                    <td><?= esc($user['username']) ?></td>
                    <td><?= esc($user['full_name']) ?></td>
                    <td><?= esc($user['role']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?= $this->endSection() ?>
