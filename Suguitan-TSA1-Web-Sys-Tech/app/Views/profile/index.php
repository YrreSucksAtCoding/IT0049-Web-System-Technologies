<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="card">
        <h2>Profile</h2>
        <p class="sub">The demo user account stored in the users table.</p>

        <?php if (empty($user)): ?>

            <p class="empty">No user record found. Make sure the database was imported.</p>

        <?php else: ?>

            <!-- One record, not a list — the model used first() instead of findAll() -->
            <dl>
                <dt>Full Name</dt>
                <dd><?= esc($user['full_name']) ?></dd>

                <dt>Username</dt>
                <dd><?= esc($user['username']) ?></dd>

                <dt>Email</dt>
                <dd><?= esc($user['email']) ?></dd>

                <dt>Member Since</dt>
                <dd><?= esc(date('F j, Y', strtotime($user['created_at']))) ?></dd>
            </dl>

            <div class="summary" style="margin-top: 24px;">
                <div class="stat">
                    <b><?= $totalTasks ?></b>
                    <span>tasks total</span>
                </div>
                <div class="stat">
                    <b><?= $todayTasks ?></b>
                    <span>due today</span>
                </div>
            </div>

        <?php endif; ?>
    </div>

<?= $this->endSection() ?>
