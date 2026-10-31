{{--
    resources/views/profile/index.blade.php

    The Profile page. Shows the single record from the users table.

    Shown by: ProfileController::index()   Route: GET /profile
    Receives: $title, $user (a User model, or null)
--}}
@extends('layouts.app')

@section('content')

    <div class="card">
        <h2>Profile</h2>
        <p class="sub">The user record stored in the database.</p>

        {{-- The controller used first(), which returns null on an empty
             table. Checking for it here means an unseeded database shows a
             message instead of "Attempt to read property on null". --}}
        @if ($user)
            <table class="detail">
                <tbody>
                    <tr><th>Username</th>   <td>{{ $user->username }}</td></tr>
                    <tr><th>Full Name</th>  <td>{{ $user->full_name }}</td></tr>
                    <tr><th>Email</th>      <td>{{ $user->email }}</td></tr>
                    <tr><th>Registered</th> <td>{{ $user->created_at->format('F j, Y') }}</td></tr>
                </tbody>
            </table>
        @else
            <div class="empty">
                No user record found.<br>
                Run <code>php artisan db:seed</code>.
            </div>
        @endif
    </div>

@endsection
