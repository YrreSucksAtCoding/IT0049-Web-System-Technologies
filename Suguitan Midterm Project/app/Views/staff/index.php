<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="card">
        <div class="toolbar">
            <div>
                <h2>Staff</h2>
                <p class="sub">Accounts that can sign in and record sales. Total: <?= count($staff) ?>.</p>
            </div>
            <div class="toolbar-actions">
                <a href="<?= site_url('staff/new') ?>" class="btn btn-primary">+ New Staff Member</a>
                <a href="<?= site_url('staff/archived') ?>" class="btn btn-light">Archived</a>
            </div>
        </div>

        <?php if (empty($staff)): ?>

            <p class="empty">No staff accounts.</p>

        <?php else: ?>

            <table>
                <thead>
                    <tr><th>Avatar</th><th>#</th><th>Username</th><th>Full Name</th><th>Added</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($staff as $member): ?>
                        <tr>
                            <td>
                                <img class="avatar"
                                     src="<?= esc(\App\Libraries\ImageUploader::url('avatars', $member['avatar'], 'avatar-placeholder.png'), 'attr') ?>"
                                     alt="<?= esc($member['username'], 'attr') ?>">
                            </td>
                            <td><?= esc($member['id']) ?></td>
                            <td>
                                <?= esc($member['username']) ?>
                                <?php if ((int) $member['id'] === (int) session()->get('user_id')): ?>
                                    <span class="badge badge-ok">you</span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($member['full_name']) ?></td>
                            <td><?= esc(date('M d, Y', strtotime($member['created_at']))) ?></td>
                            <td>
                                <div class="row-actions">
                                    <a class="btn btn-light btn-small"
                                       href="<?= site_url('staff/edit/' . $member['id']) ?>">Edit</a>

                                    <?php if ((int) $member['id'] !== (int) session()->get('user_id')): ?>
                                        <form action="<?= site_url('staff/delete/' . $member['id']) ?>" method="post">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-danger btn-small">Delete</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>
    </div>

<?= $this->endSection() ?>
