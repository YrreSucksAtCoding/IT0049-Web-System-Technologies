{{--
    resources/views/users/index.blade.php

    Lists the staff accounts.

    Shown by: UserController::index()   Route: GET /users
    Receives: $title, $users (array of records)
--}}
@extends('layouts.app')

@section('content')

    <div class="card">
        <h2>User Accounts</h2>

        <p>Staff members who can operate the POS system.</p>

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
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user['username'] }}</td>
                        <td>{{ $user['full_name'] }}</td>
                        <td>{{ $user['role'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class="count">Total users: {{ count($users) }}</p>
    </div>

@endsection
