<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="card">
        <div class="toolbar">
            <div>
                <h2>Archived Staff</h2>
                <p class="sub">
                    An archived account cannot sign in &mdash; the login refuses it even
                    with the correct password &mdash; but the sales it recorded stay in
                    the history.
                </p>
            </div>
            <div class="toolbar-actions">
                <a href="<?= site_url('staff') ?>" class="btn btn-light">Back to staff</a>
            </div>
        </div>

        <?php if (empty($staff)): ?>
            <p class="empty">Nothing archived.</p>
        <?php else: ?>
            <table>
                <thead><tr><th>#</th><th>Username</th><th>Full Name</th><th></th></tr></thead>
                <tbody>
                    <?php foreach ($staff as $member): ?>
                        <tr>
                            <td><?= esc($member['id']) ?></td>
                            <td><?= esc($member['username']) ?></td>
                            <td><?= esc($member['full_name']) ?></td>
                            <td>
                                <form action="<?= site_url('staff/restore/' . $member['id']) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-light btn-small">Restore</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

<?= $this->endSection() ?>
