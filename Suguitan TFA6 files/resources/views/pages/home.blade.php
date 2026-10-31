{{--
    resources/views/pages/home.blade.php

    The landing page. @extends picks the layout, and everything inside
    @section('content') replaces the @yield('content') in that layout.

    Shown by: PageController::index()   Route: GET /
--}}
@extends('layouts.app')

@section('content')

    <div class="card">
        <h2>Welcome to the Simple POS System</h2>

        <p>
            This is the Laravel version of the four-page POS site I first built
            in CodeIgniter 4. The pages and the data are the same &mdash; only
            the framework changed.
        </p>

        <p>Use the menu above to move around:</p>

        <ul>
            <li><strong>About</strong> &mdash; what this project is and who made it</li>
            <li><strong>Customer Accounts</strong> &mdash; the people who buy from the store</li>
            <li><strong>User Accounts</strong> &mdash; the staff who operate the POS</li>
        </ul>

        <p class="count">
            The records on the two list pages now come from a MySQL database.
            Migrations build the tables, a seeder fills them, and Eloquent
            models fetch them &mdash; the static arrays from TFA5 are gone.
        </p>
    </div>

@endsection
