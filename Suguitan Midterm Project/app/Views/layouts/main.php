<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= esc($title) ?> | POS System</title>

    <style>
        * { box-sizing: border-box; }
        body { font-family: -apple-system, "Segoe UI", Arial, sans-serif; margin: 0;
               background: #eef1f5; color: #1f2430; }

        header { background: #1b3a5c; color: #fff; padding: 14px 24px;
                 display: flex; justify-content: space-between; align-items: center;
                 flex-wrap: wrap; gap: 10px; }
        header h1 { margin: 0; font-size: 19px; letter-spacing: 0.2px; }
        header .tag { margin: 3px 0 0; font-size: 12px; color: #9fb3c8; }
        .session-bar { display: flex; align-items: center; gap: 12px; font-size: 13px; }
        .session-bar .who { color: #c3d2e0; }
        .session-bar img { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; }
        .session-bar form { margin: 0; }

        nav { background: #27547f; padding: 0 24px; display: flex; flex-wrap: wrap; }
        nav a { color: #dce7f1; text-decoration: none; padding: 12px 15px; font-size: 14px; }
        nav a:hover { background: #336a9f; color: #fff; }
        nav a.sell { background: #1f7a4d; color: #fff; font-weight: 600; }
        nav a.sell:hover { background: #24915b; }

        main { max-width: 1040px; margin: 24px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 8px; padding: 24px; margin-bottom: 20px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        h2 { margin: 0 0 6px; font-size: 22px; }
        h3 { margin: 0 0 10px; font-size: 17px; }
        .sub { margin: 0 0 18px; color: #6b7382; font-size: 14px; }

        table { border-collapse: collapse; width: 100%; }
        th, td { border-bottom: 1px solid #e4e7ec; padding: 10px; text-align: left;
                 font-size: 14px; vertical-align: middle; }
        th { background: #f6f8fa; font-weight: 600; color: #475061; }
        td.num, th.num { text-align: right; font-variant-numeric: tabular-nums; }

        .thumb { width: 44px; height: 44px; border-radius: 6px; object-fit: cover;
                 display: block; background: #e8ebf0; }
        .avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover;
                  display: block; background: #e8ebf0; }

        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px;
                 font-size: 12px; font-weight: 600; white-space: nowrap; }
        .badge-ok   { background: #d8f3e3; color: #1b7a48; }
        .badge-low  { background: #fde8d8; color: #9a4f16; }
        .badge-out  { background: #fdecea; color: #a93226; }
        .badge-none { background: #eef1f5; color: #6b7382; }

        .stats { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 4px; }
        .stat { background: #f6f8fa; border: 1px solid #e4e7ec; border-radius: 7px;
                padding: 14px 18px; min-width: 130px; flex: 1; }
        .stat b { display: block; font-size: 24px; font-variant-numeric: tabular-nums; }
        .stat span { font-size: 12px; color: #6b7382; }

        .btn { display: inline-block; padding: 8px 16px; border-radius: 5px; font-size: 14px;
               text-decoration: none; border: 0; cursor: pointer; font-family: inherit; }
        .btn-primary { background: #27547f; color: #fff; }
        .btn-primary:hover { background: #336a9f; }
        .btn-sell { background: #1f7a4d; color: #fff; }
        .btn-sell:hover { background: #24915b; }
        .btn-light { background: #eceff3; color: #2b3a4d; border: 1px solid #d3d9e0; }
        .btn-light:hover { background: #e0e5ea; }
        .btn-danger { background: #fdecea; color: #a93226; border: 1px solid #f0bdb7; }
        .btn-danger:hover { background: #f9dcd8; }
        .btn-small { padding: 4px 11px; font-size: 13px; }
        .btn-logout { background: #35648f; color: #fff; padding: 5px 14px; font-size: 13px; }
        .btn-logout:hover { background: #4379a8; }
        .row-actions { display: flex; gap: 6px; }
        .row-actions form { margin: 0; }
        .toolbar { display: flex; justify-content: space-between; align-items: flex-start;
                   flex-wrap: wrap; gap: 12px; margin-bottom: 18px; }
        .toolbar-actions { display: flex; gap: 8px; flex-wrap: wrap; }

        .field { margin-bottom: 16px; max-width: 460px; }
        .field label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 5px; }
        .field input[type="text"], .field input[type="email"], .field input[type="number"],
        .field input[type="password"], .field input[type="file"], .field select {
            width: 100%; padding: 9px 10px; border: 1px solid #ccd1d4; border-radius: 5px;
            font-size: 14px; font-family: inherit; background: #fff; }
        .field .hint { font-size: 12px; color: #7f8c8d; margin-top: 4px; font-weight: 400; }
        .form-actions { margin-top: 20px; }
        .current-image { display: flex; align-items: center; gap: 12px; margin-bottom: 8px; }
        .current-image img { width: 72px; height: 72px; border-radius: 6px; object-fit: cover; }

        .alert { padding: 12px 16px; border-radius: 5px; margin-bottom: 18px; font-size: 14px; }
        .alert-success { background: #d8f3e3; color: #1b7a48; border: 1px solid #aedcc4; }
        .alert-error   { background: #fdecea; color: #a93226; border: 1px solid #f0bdb7; }
        .alert ul { margin: 6px 0 0; padding-left: 20px; }

        .empty { color: #6b7382; font-style: italic; }
        .login-wrap { max-width: 420px; margin: 60px auto; }
        .muted { color: #6b7382; }

        footer { text-align: center; padding: 20px; font-size: 12px; color: #8a91a0; }
    </style>
</head>
<body>

    <header>
        <div>
            <h1>Point-of-Sale System</h1>
            <p class="tag">IT0049 &mdash; Web System Technologies | Midterm Project</p>
        </div>

        <?php if (session()->get('logged_in') === true): ?>
            <div class="session-bar">
                <img src="<?= esc(\App\Libraries\ImageUploader::url('avatars', session()->get('avatar'), 'avatar-placeholder.png'), 'attr') ?>" alt="">
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

    <?php if (session()->get('logged_in') === true): ?>
        <nav>
            <a href="<?= site_url('/') ?>">Dashboard</a>
            <a href="<?= site_url('products') ?>">Products</a>
            <a href="<?= site_url('customers') ?>">Customers</a>
            <a href="<?= site_url('staff') ?>">Staff</a>
            <a href="<?= site_url('sales') ?>">Sales History</a>
            <a href="<?= site_url('sales/new') ?>" class="sell">Record Sale</a>
        </nav>
    <?php endif; ?>

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

    <footer>Point-of-Sale System &mdash; Midterm Project</footer>

</body>
</html>
