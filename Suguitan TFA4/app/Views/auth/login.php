<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="login-card">
        <h2>Sign in</h2>
        <p class="sub">Staff accounts only. Customer and user records require a login.</p>

        <?php if (! empty($loggedOut)): ?>
            <div class="alert alert-success">You have been logged out.</div>
        <?php endif; ?>

        <form action="<?= site_url('login/attempt') ?>" method="post">
            <?= csrf_field() ?>

            <div class="field">
                <label for="username">Username</label>
                <!-- the username is kept on a failed attempt, the password never is -->
                <input type="text" id="username" name="username"
                       value="<?= esc(old('username')) ?>" autofocus>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Sign in</button>
            </div>
        </form>
    </div>

<?= $this->endSection() ?>
