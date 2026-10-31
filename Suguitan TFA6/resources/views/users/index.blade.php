{{--
    resources/views/users/index.blade.php

    Shown by: UserController::index()   Route: GET /users
    Receives: $title, $users

    Same change as the customers view: ['role'] became ->role.
    The password column is never printed here, and $hidden on the User
    model keeps the hash out of the data the page is built from anyway.
--}}
@extends('layouts.app')

@section('content')

    <div class="card">
        <h2>User Accounts</h2>

        <p>Staff members who can operate the POS system, loaded from the database.</p>

        <table>
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Role</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->role }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No users yet. Run <code>php artisan db:seed</code>.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <p class="count">Total users: {{ count($users) }}</p>
    </div>

@endsection
