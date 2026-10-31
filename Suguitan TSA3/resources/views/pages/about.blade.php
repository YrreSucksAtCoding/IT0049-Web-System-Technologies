{{--
    resources/views/pages/about.blade.php

    The static page. No model, no query — the controller passes only a title.

    Shown by: PageController::about()   Route: GET /about
--}}
@extends('layouts.app')

@section('content')

    <div class="card">
        <h2>About This Project</h2>
        <p class="sub">Tasks for Today Management System</p>

        <p>
            A small task-management application built for IT0049 &mdash; Web
            System Technologies. It is the Laravel rebuild of TSA1: the same
            four pages and the same data, with migrations, a seeder and
            Eloquent models replacing the raw SQL of the original.
        </p>

        <table class="detail">
            <tbody>
                <tr><th>Developer</th>  <td>Yrre Suguitan</td></tr>
                <tr><th>Subject</th>    <td>IT0049 &mdash; Web System Technologies</td></tr>
                <tr><th>Activity</th>   <td>TSA3 &mdash; Tasks for Today Management System in Laravel</td></tr>
                <tr><th>Framework</th>  <td>Laravel (PHP) with Eloquent ORM</td></tr>
                <tr><th>Database</th>   <td>MySQL &mdash; tasks and users</td></tr>
                <tr><th>Section</th>    <td></td></tr>
                <tr><th>Professor</th>  <td></td></tr>
            </tbody>
        </table>

        <h3 style="font-size:15px; margin-top:24px;">How a page is built</h3>

        <p>
            A URL in <code>routes/web.php</code> points to a controller method.
            That method asks an Eloquent model for the records it needs and
            returns a Blade view. The Welcome page and the Task List page use
            the same model and differ by a single query scope &mdash;
            <code>Task::today()</code> &mdash; so the rule for what counts as
            today is written once and used in both places.
        </p>
    </div>

@endsection
