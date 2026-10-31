{{--
    resources/views/customers/index.blade.php

    Shown by: CustomerController::index()   Route: GET /customers
    Receives: $title, $customers

    The ONLY change from TFA5 is the square brackets becoming arrows.
    $customers used to be an array of arrays; it is now a Collection of
    Customer objects, so a field is read with ->full_name instead of
    ['full_name']. The @foreach, the table and the counter are untouched.

    (Array syntax actually still works, because an Eloquent model
    implements ArrayAccess — but the arrow is the normal way to read a
    model, so I switched rather than relying on that.)
--}}
@extends('layouts.app')

@section('content')

    <div class="card">
        <h2>Customer Accounts</h2>

        <p>Customers registered in the POS system, loaded from the database.</p>

        <table>
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Full Name</th>
                    <th>Email Address</th>
                    <th>Phone Number</th>
                </tr>
            </thead>

            <tbody>
                {{-- @forelse is @foreach with a built-in "nothing found" case,
                     which matters now: an empty array was impossible before,
                     an empty table is not. --}}
                @forelse ($customers as $customer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $customer->full_name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No customers yet. Run <code>php artisan db:seed</code>.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- count() works on a Collection the same as on an array. --}}
        <p class="count">Total customers: {{ count($customers) }}</p>
    </div>

@endsection
