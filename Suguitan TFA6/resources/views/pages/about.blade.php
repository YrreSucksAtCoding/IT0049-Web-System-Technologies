{{--
    resources/views/pages/about.blade.php

    Shown by: PageController::about()   Route: GET /about
--}}
@extends('layouts.app')

@section('content')

    <div class="card">
        <h2>About This Project</h2>

        <p>
            A Simple Point of Sale system built for IT0049 &mdash; Web System
            Technologies. This activity (TFA6) replaces the static arrays from
            TFA5 with a real MySQL database, using migrations, a seeder and
            Eloquent models.
        </p>

        <h3 style="font-size:15px;">Student Information</h3>

        <table>
            <tbody>
                <tr><th style="width:170px;">Name</th><td>Yrre Suguitan</td></tr>
                <tr><th>Subject</th><td>IT0049 &mdash; Web System Technologies</td></tr>
                <tr><th>Activity</th><td>TFA6 &mdash; From Arrays to Eloquent</td></tr>
                <tr><th>Framework</th><td>Laravel (PHP)</td></tr>
            </tbody>
        </table>

        <h3 style="font-size:15px;">How a page gets built here</h3>

        <p>
            A URL is listed in <code>routes/web.php</code>, which points to one
            method in a controller under <code>app/Http/Controllers/</code>.
            That method asks an Eloquent model for the records and returns a Blade
            view from <code>resources/views/</code>. The view is only markup
            &mdash; it never queries the database itself.
        </p>
    </div>

@endsection
