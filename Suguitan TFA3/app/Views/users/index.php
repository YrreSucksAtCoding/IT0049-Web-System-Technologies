<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="toolbar">
        <div>
            <h2>User Accounts</h2>
            <p>Total users: <?= count($users) ?></p>
        </div>
        <a href="<?= site_url('users/new') ?>" class="btn btn-primary">+ New User</a>
    </div>

    <?php if (empty($users)): ?>

        <p>No user records yet. Use the New User button to add one.</p>

    <?php else: ?>

        <table>
            <thead>
                <tr>
                    <th>Avatar</th>
                    <th>#</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Date Added</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <?php
                        // The database stores only a filename. The folder is added
                        // here. When the column is empty we fall back to the
                        // placeholder image instead of showing a broken picture.
                        $avatarUrl = ! empty($user['avatar'])
                            ? base_url('uploads/avatars/' . $user['avatar'])
                            : base_url('img/avatar-placeholder.png');
                    ?>
                    <tr>
                        <td>
                            <img class="avatar" src="<?= esc($avatarUrl, 'attr') ?>"
                                 alt="Avatar of <?= esc($user['username'], 'attr') ?>">
                        </td>
                        <td><?= esc($user['id']) ?></td>
                        <td><?= esc($user['username']) ?></td>
                        <td><?= esc($user['full_name']) ?></td>
                        <td><?= esc(date('M d, Y', strtotime($user['created_at']))) ?></td>
                        <td>
                            <a class="btn btn-light btn-small"
                               href="<?= site_url('users/edit/' . $user['id']) ?>">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

<?= $this->endSection() ?>
