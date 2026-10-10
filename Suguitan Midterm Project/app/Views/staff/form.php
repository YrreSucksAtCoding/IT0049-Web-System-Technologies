<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="card">
        <h2><?= esc($heading) ?></h2>
        <p class="sub">The password is stored as a bcrypt hash, never as typed.</p>

        <form action="<?= esc($action, 'attr') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="field">
                <label for="username">Username</label>
                <input type="text" id="username" name="username"
                       value="<?= esc(old('username', $member['username'] ?? '')) ?>">
                <div class="hint">Used to sign in. Must not already be taken.</div>
            </div>

            <div class="field">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name"
                       value="<?= esc(old('full_name', $member['full_name'] ?? '')) ?>">
            </div>

            <div class="field">
                <label for="password">
                    Password
                    <?php if ($member !== null): ?><span class="hint">(leave blank to keep the current one)</span><?php endif; ?>
                </label>
                <!-- never pre-filled: what is stored is a hash, not a password -->
                <input type="password" id="password" name="password">
                <div class="hint">At least 6 characters.</div>
            </div>

            <div class="field">
                <label for="password_confirm">Confirm Password</label>
                <input type="password" id="password_confirm" name="password_confirm">
            </div>

            <div class="field">
                <label for="avatar">Avatar <span class="hint">(optional)</span></label>

                <?php if (! empty($member['avatar'])): ?>
                    <div class="current-image">
                        <img src="<?= esc(\App\Libraries\ImageUploader::url('avatars', $member['avatar'], 'avatar-placeholder.png'), 'attr') ?>" alt="Current avatar">
                        <span class="hint">Current avatar. Choosing a new file replaces it.</span>
                    </div>
                <?php endif; ?>

                <input type="file" id="avatar" name="avatar" accept=".jpg,.jpeg,.png,.webp">
                <div class="hint">JPG, PNG or WebP, 2 MB maximum. Resized to 200&times;200.</div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <?= $member === null ? 'Add Staff Member' : 'Save Changes' ?>
                </button>
                <a href="<?= site_url('staff') ?>" class="btn btn-light">Cancel</a>
            </div>
        </form>
    </div>

<?= $this->endSection() ?>
