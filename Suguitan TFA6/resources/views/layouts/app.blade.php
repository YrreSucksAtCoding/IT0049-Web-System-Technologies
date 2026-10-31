{{--
    resources/views/layouts/app.blade.php

    The shared layout. Every page extends this file so the header, the
    navigation bar and the styling are written only once.

    CodeIgniter equivalent: the layout I used with $this->extend() and
    $this->renderSection(). Blade does the same thing with @extends and
    @yield, just with a shorter syntax.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- $title is passed in by every controller. {{ }} escapes it automatically. --}}
    <title>{{ $title }} | Simple POS (Laravel)</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: -apple-system, "Segoe UI", Arial, sans-serif;
            background: #f4f6f8;
            color: #22272e;
        }

        header {
            background: #1f3a5f;
            color: #fff;
            padding: 18px 24px;
        }

        header h1 { margin: 0; font-size: 20px; }
        header p  { margin: 4px 0 0; font-size: 13px; opacity: .8; }

        nav {
            background: #2b4f7e;
            padding: 0 24px;
        }

        nav a {
            display: inline-block;
            padding: 12px 16px;
            color: #dce6f2;
            text-decoration: none;
            font-size: 14px;
        }

        nav a:hover { background: #3a6397; color: #fff; }

        /* The link for the page you are already on is highlighted. */
        nav a.active { background: #f4f6f8; color: #1f3a5f; font-weight: 600; }

        main {
            max-width: 900px;
            margin: 24px auto;
            padding: 0 16px;
        }

        .card {
            background: #fff;
            border: 1px solid #dde3ea;
            border-radius: 8px;
            padding: 20px 24px;
        }

        h2 { margin-top: 0; font-size: 18px; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th, td {
            text-align: left;
            padding: 10px 12px;
            border-bottom: 1px solid #e5e9ef;
        }

        th { background: #eef2f7; font-size: 13px; }

        tbody tr:hover { background: #f8fafc; }

        .count { color: #5a6570; font-size: 13px; margin-top: 14px; }

        footer {
            text-align: center;
            color: #7a848e;
            font-size: 12px;
            padding: 24px 0;
        }
    </style>
</head>
<body>

    <header>
        <h1>Simple POS System</h1>
        <p>IT0049 &mdash; Web System Technologies &middot; TFA6 (Laravel + Eloquent)</p>
    </header>

    {{--
        Navigation. route('home') builds the URL from the route NAME set in
        routes/web.php, so if a URL ever changes I only edit that one file.

        request()->routeIs('home') is true when the current page is that
        route, which is how the active link gets highlighted.
    --}}
    <nav>
        <a href="{{ route('home') }}"      class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        <a href="{{ route('about') }}"     class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a>
        <a href="{{ route('customers') }}" class="{{ request()->routeIs('customers') ? 'active' : '' }}">Customer Accounts</a>
        <a href="{{ route('users') }}"     class="{{ request()->routeIs('users') ? 'active' : '' }}">User Accounts</a>
    </nav>

    <main>
        {{-- Each page's own markup gets dropped in here. --}}
        @yield('content')
    </main>

    <footer>
        Yrre Suguitan &middot; Laravel {{ app()->version() }}
    </footer>

</body>
</html>
