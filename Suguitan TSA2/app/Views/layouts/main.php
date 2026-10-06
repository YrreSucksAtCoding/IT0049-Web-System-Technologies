<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= esc($title) ?> | Tasks for Today</title>

    <style>
        * { box-sizing: border-box; }
        body { font-family: -apple-system, "Segoe UI", Arial, sans-serif; margin: 0;
               background: #f0f2f5; color: #1f2430; }

        header { background: #1f3a5f; color: #fff; padding: 16px 24px;
                 display: flex; justify-content: space-between; align-items: center;
                 flex-wrap: wrap; gap: 10px; }
        header h1 { margin: 0; font-size: 20px; letter-spacing: 0.2px; }
        header .tag { margin: 4px 0 0; font-size: 13px; color: #b9c7da; }
        .session-bar { display: flex; align-items: center; gap: 12px; font-size: 13px; }
        .session-bar .who { color: #b9c7da; }
        .session-bar form { margin: 0; }

        nav { background: #2b4f7e; padding: 0 24px; display: flex; flex-wrap: wrap; }
        nav a { color: #dce5f0; text-decoration: none; padding: 12px 16px; font-size: 14px; }
        nav a:hover { background: #38609a; color: #fff; }

        main { max-width: 900px; margin: 24px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 8px; padding: 24px; margin-bottom: 20px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        h2 { margin: 0 0 6px; font-size: 22px; }
        .sub { margin: 0 0 18px; color: #6b7382; font-size: 14px; }

        table { border-collapse: collapse; width: 100%; }
        th, td { border-bottom: 1px solid #e4e7ec; padding: 11px 10px; text-align: left;
                 font-size: 14px; vertical-align: middle; }
        th { background: #f7f8fa; font-weight: 600; color: #475061; }
        tr.is-today td { background: #fffbe9; }

        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px;
                 font-size: 12px; font-weight: 600; white-space: nowrap; }
        .badge-done        { background: #d8f3e3; color: #1b7a48; }
        .badge-in_progress { background: #dbe8fb; color: #23538f; }
        .badge-pending     { background: #fde8d8; color: #9a4f16; }

        .summary { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 18px; }
        .stat { background: #f7f8fa; border: 1px solid #e4e7ec; border-radius: 6px;
                padding: 12px 18px; min-width: 110px; }
        .stat b { display: block; font-size: 22px; }
        .stat span { font-size: 12px; color: #6b7382; }

        .btn { display: inline-block; padding: 8px 16px; border-radius: 5px; font-size: 14px;
               text-decoration: none; border: 0; cursor: pointer; font-family: inherit; }
        .btn-primary { background: #2b4f7e; color: #fff; }
        .btn-primary:hover { background: #38609a; }
        .btn-light { background: #eceff3; color: #2b3a4d; border: 1px solid #d3d9e0; }
        .btn-light:hover { background: #e0e5ea; }
        .btn-danger { background: #fdecea; color: #a93226; border: 1px solid #f0bdb7; }
        .btn-danger:hover { background: #f9dcd8; }
        .btn-small { padding: 4px 11px; font-size: 13px; }
        .btn-logout { background: #3b5e8c; color: #fff; padding: 5px 14px; font-size: 13px; }
        .btn-logout:hover { background: #4a6f9f; }
        .row-actions { display: flex; gap: 6px; }
        .row-actions form { margin: 0; }
        .toolbar { display: flex; justify-content: space-between; align-items: flex-start;
                   flex-wrap: wrap; gap: 12px; margin-bottom: 18px; }
        .toolbar-actions { display: flex; gap: 8px; flex-wrap: wrap; }

        .field { margin-bottom: 16px; max-width: 460px; }
        .field label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 5px; }
        .field input[type="text"], .field input[type="date"],
        .field input[type="password"], .field select {
            width: 100%; padding: 9px 10px; border: 1px solid #ccd1d4; border-radius: 5px;
            font-size: 14px; font-family: inherit; background: #fff; }
        .field .hint { font-size: 12px; color: #7f8c8d; margin-top: 4px; font-weight: 400; }
        .form-actions { margin-top: 20px; }

        .alert { padding: 12px 16px; border-radius: 5px; margin-bottom: 18px; font-size: 14px; }
        .alert-success { background: #d8f3e3; color: #1b7a48; border: 1px solid #aedcc4; }
        .alert-error   { background: #fdecea; color: #a93226; border: 1px solid #f0bdb7; }
        .alert ul { margin: 6px 0 0; padding-left: 20px; }

        .empty { color: #6b7382; font-style: italic; }
        .login-card { max-width: 420px; }
        dl { margin: 0; }
        dt { font-size: 12px; text-transform: uppercase; letter-spacing: 0.06em;
             color: #6b7382; margin-top: 14px; }
        dd { margin: 4px 0 0; font-size: 16px; }

        footer { text-align: center; padding: 20px; font-size: 12px; color: #8a91a0; }
    </style>
</head>
<body>

    <header>
        <div>
            <h1>Tasks for Today Management System</h1>
            <p class="tag">IT0049 &mdash; Web System Technologies</p>
        </div>

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
        <a href="<?= site_url('/') ?>">Today</a>
        <a href="<?= site_url('tasks') ?>">All Tasks</a>
        <a href="<?= site_url('profile') ?>">Profile</a>
        <a href="<?= site_url('about') ?>">About</a>
        <?php if (session()->get('logged_in') === true): ?>
            <a href="<?= site_url('tasks/archived') ?>">Archived</a>
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

    <footer>Tasks for Today Management System &mdash; TSA2</footer>

</body>
</html>
