<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <h2><?= esc($heading) ?></h2>

    <!--
        enctype="multipart/form-data" is required for a file input.
        Without it the browser sends only the filename as text and
        $this->request->getFile('avatar') comes back empty.
    -->
    <form action="<?= esc($action, 'attr') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="field">
            <label for="username">Username</label>
            <input type="text" id="username" name="username"
                   value="<?= esc(old('username', $user['username'] ?? '')) ?>">
            <div class="hint">Letters, numbers and simple punctuation. Must not already be taken.</div>
        </div>

        <div class="field">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name"
                   value="<?= esc(old('full_name', $user['full_name'] ?? '')) ?>">
        </div>

        <div class="field">
            <label for="avatar">Profile Picture <span class="hint">(optional)</span></label>

            <?php if (! empty($user['avatar'])): ?>
                <!-- show what is currently stored so the user knows what they are replacing -->
                <div class="current-avatar">
                    <img src="<?= esc(base_url('uploads/avatars/' . $user['avatar']), 'attr') ?>" alt="Current avatar">
                    <span class="hint">Current picture. Choosing a new file replaces it.</span>
                </div>
            <?php endif; ?>

            <input type="file" id="avatar" name="avatar" accept=".jpg,.jpeg,.png">
            <div class="hint">JPG or PNG, 2 MB maximum. Leave empty to keep the current picture.</div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?= $user === null ? 'Add User' : 'Save Changes' ?>
            </button>
            <a href="<?= site_url('users') ?>" class="btn btn-light">Cancel</a>
        </div>
    </form>

<?= $this->endSection() ?>
