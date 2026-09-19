<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <h2>User Accounts</h2>
    <p>Total users: <?= count($users) ?> <span class="src">(from the database)</span></p>

    <?php if (empty($users)): ?>

        <p>No user records found. Make sure the database was imported.</p>

    <?php else: ?>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Date Added</th>
                </tr>
            </thead>
            <tbody>
                <!-- Same foreach as TFA1. Only the source of $users changed. -->
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= esc($user['id']) ?></td>
                        <td><?= esc($user['username']) ?></td>
                        <td><?= esc($user['full_name']) ?></td>
                        <td><?= esc(date('M d, Y', strtotime($user['created_at']))) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

<?= $this->endSection() ?>
