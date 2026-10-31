{{--
    resources/views/layouts/app.blade.php

    The shared layout: header, navigation and all the styling, written once.
    Every page starts with @extends('layouts.app') and fills in @yield.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} | Tasks for Today</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: -apple-system, "Segoe UI", Arial, sans-serif;
            background: #f5f7fa;
            color: #1f2933;
        }

        header {
            background: #14616b;
            color: #fff;
            padding: 20px 24px 16px;
        }

        header h1 { margin: 0; font-size: 21px; letter-spacing: .2px; }
        header p  { margin: 5px 0 0; font-size: 13px; opacity: .85; }

        nav { background: #0f4c54; padding: 0 24px; }

        nav a {
            display: inline-block;
            padding: 12px 16px;
            color: #cfe6e8;
            text-decoration: none;
            font-size: 14px;
        }

        nav a:hover  { background: #176d78; color: #fff; }
        nav a.active { background: #f5f7fa; color: #0f4c54; font-weight: 600; }

        main { max-width: 860px; margin: 26px auto; padding: 0 16px; }

        .card {
            background: #fff;
            border: 1px solid #dfe5ec;
            border-radius: 10px;
            padding: 22px 26px;
            margin-bottom: 20px;
        }

        h2 { margin: 0 0 4px; font-size: 19px; }

        .sub { color: #62707f; font-size: 13px; margin: 0 0 18px; }

        .summary {
            display: inline-block;
            background: #e8f4f5;
            color: #0f4c54;
            border-radius: 20px;
            padding: 5px 14px;
            font-size: 13px;
            margin-bottom: 16px;
        }

        /* one task row, used by both task pages */
        .task {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 14px;
            border: 1px solid #e5e9ef;
            border-radius: 8px;
            margin-bottom: 8px;
            background: #fff;
        }

        .task:hover { border-color: #b9c6d3; }

        .task-title { font-size: 15px; }

        .task-date { color: #7a8794; font-size: 12px; margin-top: 3px; }

        .badge {
            font-size: 12px;
            padding: 4px 11px;
            border-radius: 20px;
            white-space: nowrap;
        }

        .badge-pending { background: #fdf0d8; color: #8a5a00; }
        .badge-doing   { background: #dde8fb; color: #1c4b9b; }
        .badge-done    { background: #dcf2e3; color: #166434; }

        .day-heading {
            font-size: 14px;
            font-weight: 600;
            color: #14616b;
            margin: 20px 0 10px;
            padding-bottom: 6px;
            border-bottom: 1px solid #e5e9ef;
        }

        .day-heading:first-of-type { margin-top: 0; }

        .empty {
            text-align: center;
            color: #7a8794;
            padding: 30px 10px;
            font-size: 14px;
        }

        table.detail { width: 100%; border-collapse: collapse; font-size: 14px; }

        table.detail th, table.detail td {
            text-align: left;
            padding: 11px 12px;
            border-bottom: 1px solid #e9edf2;
        }

        table.detail th { width: 170px; background: #f2f6f8; font-size: 13px; }

        footer {
            text-align: center;
            color: #8a949e;
            font-size: 12px;
            padding: 26px 0;
        }
    </style>
</head>
<body>

    <header>
        <h1>Tasks for Today</h1>
        <p>IT0049 &mdash; Web System Technologies &middot; TSA3 (Laravel + Eloquent)</p>
    </header>

    {{-- route() builds each URL from the name in routes/web.php;
         request()->routeIs() highlights the page you are on. --}}
    <nav>
        <a href="{{ route('welcome') }}"     class="{{ request()->routeIs('welcome') ? 'active' : '' }}">Today</a>
        <a href="{{ route('tasks.index') }}" class="{{ request()->routeIs('tasks.index') ? 'active' : '' }}">All Tasks</a>
        <a href="{{ route('profile') }}"     class="{{ request()->routeIs('profile') ? 'active' : '' }}">Profile</a>
        <a href="{{ route('about') }}"       class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        Yrre Suguitan &middot; Laravel {{ app()->version() }}
    </footer>

</body>
</html>
