<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= esc($title) ?> | Simple POS</title>

    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f4f4; color: #222; }

        header { background: #2c3e50; color: #fff; padding: 16px 24px;
                 display: flex; justify-content: space-between; align-items: center;
                 flex-wrap: wrap; gap: 10px; }
        header h1 { margin: 0; font-size: 20px; }
        .session-bar { display: flex; align-items: center; gap: 12px; font-size: 13px; }
        .session-bar .who { color: #bdc9d4; }
        .session-bar form { margin: 0; }

        nav { background: #34495e; padding: 10px 24px; }
        nav a { color: #fff; text-decoration: none; margin-right: 18px; font-size: 14px; }
        nav a:hover { text-decoration: underline; }

        main { background: #fff; margin: 24px; padding: 24px; border-radius: 6px; }
        h2 { margin-top: 0; }
        .sub { color: #7f8c8d; font-size: 14px; margin-top: -6px; }

        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 14px;
                 vertical-align: middle; }
        th { background: #ecf0f1; }

        .avatar { width: 48px; height: 48px; border-radius: 50%; object-fit: cover;
                  display: block; background: #e4e7ec; }

        .btn { display: inline-block; padding: 8px 16px; border-radius: 4px; font-size: 14px;
               text-decoration: none; border: 0; cursor: pointer; font-family: inherit; }
        .btn-primary { background: #2980b9; color: #fff; }
        .btn-primary:hover { background: #2471a3; }
        .btn-light { background: #ecf0f1; color: #2c3e50; border: 1px solid #ccd1d4; }
        .btn-light:hover { background: #dfe4e6; }
        .btn-small { padding: 4px 12px; font-size: 13px; }
        .btn-logout { background: #46617f; color: #fff; padding: 5px 14px; font-size: 13px; }
        .btn-logout:hover { background: #567a9e; }
        .toolbar { display: flex; justify-content: space-between; align-items: center;
                   flex-wrap: wrap; gap: 12px; margin-bottom: 16px; }

        .field { margin-bottom: 16px; max-width: 460px; }
        .field label { display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px; }
        .field input[type="text"], .field input[type="email"],
        .field input[type="password"], .field input[type="file"] {
            width: 100%; padding: 9px 10px; border: 1px solid #ccd1d4; border-radius: 4px;
            font-size: 14px; font-family: inherit; }
        .field .hint { font-size: 12px; color: #7f8c8d; margin-top: 4px; font-weight: normal; }
        .form-actions { margin-top: 20px; }

        .alert { padding: 12px 16px; border-radius: 4px; margin-bottom: 18px; font-size: 14px; }
        .alert-success { background: #d8f3e3; color: #1b7a48; border: 1px solid #aedcc4; }
        .alert-error   { background: #fdecea; color: #a93226; border: 1px solid #f0bdb7; }
        .alert ul { margin: 6px 0 0; padding-left: 20px; }

        .current-avatar { display: flex; align-items: center; gap: 12px; margin-bottom: 8px; }
        .current-avatar img { width: 64px; height: 64px; border-radius: 50%; object-fit: cover; }

        .login-card { max-width: 420px; }

        footer { text-align: center; padding: 16px; font-size: 12px; color: #777; }
    </style>
</head>
<body>

    <header>
        <h1>Simple POS System</h1>

        <?php if (session()->get('logged_in') === true): ?>
            <div class="session-bar">
                <span class="who">Signed in as <?= esc(session()->get('full_name')) ?></span>
                <!-- logout is a POST form, not a link: a plain GET link can be
                     triggered by anything that merely loads the URL -->
                <form action="<?= site_url('logout') ?>" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-logout">Log out</button>
                </form>
            </div>
        <?php endif; ?>
    </header>

    <nav>
        <a href="<?= site_url('/') ?>">Home</a>
        <a href="<?= site_url('about') ?>">About</a>
        <?php if (session()->get('logged_in') === true): ?>
            <a href="<?= site_url('customers') ?>">Customer Accounts</a>
            <a href="<?= site_url('users') ?>">User Accounts</a>
        <?php else: ?>
            <a href="<?= site_url('login') ?>">Login</a>
        <?php endif; ?>
    </nav>

    <main>
        <?php if (session()->getFlashdata('message')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('message')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-error">
                <strong>Please fix the following:</strong>
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </main>

    <footer>
        IT0049 &mdash; Web System Technologies | TFA4
    </footer>

</body>
</html>
