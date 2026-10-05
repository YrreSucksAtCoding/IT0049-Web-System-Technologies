<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- $title is sent by every controller -->
    <title><?= esc($title) ?> | Tasks for Today</title>

    <style>
        * { box-sizing: border-box; }
        body { font-family: -apple-system, "Segoe UI", Arial, sans-serif; margin: 0;
               background: #f0f2f5; color: #1f2430; }

        header { background: #1f3a5f; color: #fff; padding: 18px 24px; }
        header h1 { margin: 0; font-size: 20px; letter-spacing: 0.2px; }
        header p  { margin: 4px 0 0; font-size: 13px; color: #b9c7da; }

        nav { background: #2b4f7e; padding: 0 24px; display: flex; flex-wrap: wrap; }
        nav a { color: #dce5f0; text-decoration: none; padding: 12px 16px; font-size: 14px; }
        nav a:hover { background: #38609a; color: #fff; }

        main { max-width: 900px; margin: 24px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 8px; padding: 24px; margin-bottom: 20px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        h2 { margin: 0 0 6px; font-size: 22px; }
        .sub { margin: 0 0 18px; color: #6b7382; font-size: 14px; }

        table { border-collapse: collapse; width: 100%; }
        th, td { border-bottom: 1px solid #e4e7ec; padding: 11px 10px; text-align: left; font-size: 14px; }
        th { background: #f7f8fa; font-weight: 600; color: #475061; }
        tr.is-today td { background: #fffbe9; }

        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px;
                 font-size: 12px; font-weight: 600; }
        .badge-done        { background: #d8f3e3; color: #1b7a48; }
        .badge-in_progress { background: #dbe8fb; color: #23538f; }
        .badge-pending     { background: #fde8d8; color: #9a4f16; }

        .summary { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 18px; }
        .stat { background: #f7f8fa; border: 1px solid #e4e7ec; border-radius: 6px;
                padding: 12px 18px; min-width: 110px; }
        .stat b { display: block; font-size: 22px; }
        .stat span { font-size: 12px; color: #6b7382; }

        .empty { color: #6b7382; font-style: italic; }
        dl { margin: 0; }
        dt { font-size: 12px; text-transform: uppercase; letter-spacing: 0.06em;
             color: #6b7382; margin-top: 14px; }
        dd { margin: 4px 0 0; font-size: 16px; }

        footer { text-align: center; padding: 20px; font-size: 12px; color: #8a91a0; }
    </style>
</head>
<body>

    <header>
        <h1>Tasks for Today Management System</h1>
        <p>IT0049 &mdash; Web System Technologies</p>
    </header>

    <!-- Links to all four pages. site_url() builds the full URL. -->
    <nav>
        <a href="<?= site_url('/') ?>">Today</a>
        <a href="<?= site_url('tasks') ?>">All Tasks</a>
        <a href="<?= site_url('profile') ?>">Profile</a>
        <a href="<?= site_url('about') ?>">About</a>
    </nav>

    <main>
        <!-- Each page's own content lands here -->
        <?= $this->renderSection('content') ?>
    </main>

    <footer>Tasks for Today Management System &mdash; TSA1</footer>

</body>
</html>
