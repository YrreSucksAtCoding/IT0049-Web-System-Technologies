<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- $title is sent by every controller -->
    <title><?= esc($title) ?> | Simple POS</title>

    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f4f4; color: #222; }
        header { background: #2c3e50; color: #fff; padding: 16px 24px; }
        header h1 { margin: 0; font-size: 20px; }
        nav { background: #34495e; padding: 10px 24px; }
        nav a { color: #fff; text-decoration: none; margin-right: 18px; font-size: 14px; }
        nav a:hover { text-decoration: underline; }
        main { background: #fff; margin: 24px; padding: 24px; border-radius: 6px; }
        h2 { margin-top: 0; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 14px; }
        th { background: #ecf0f1; }
        footer { text-align: center; padding: 16px; font-size: 12px; color: #777; }
    </style>
</head>
<body>

    <header>
        <h1>Simple POS System</h1>
    </header>

    <!-- Navigation links to all four pages. site_url() builds the full URL. -->
    <nav>
        <a href="<?= site_url('/') ?>">Home</a>
        <a href="<?= site_url('about') ?>">About</a>
        <a href="<?= site_url('customers') ?>">Customer Accounts</a>
        <a href="<?= site_url('users') ?>">User Accounts</a>
    </nav>

    <main>
        <!-- Each page's own content gets dropped in here -->
        <?= $this->renderSection('content') ?>
    </main>

    <footer>
        IT0049 &mdash; Web System Technologies | TFA1
    </footer>

</body>
</html>
