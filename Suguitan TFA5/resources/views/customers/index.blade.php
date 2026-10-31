{{--
    resources/views/customers/index.blade.php

    Lists the customer records.

    Shown by: CustomerController::index()   Route: GET /customers
    Receives: $title, $customers (array of records)
--}}
@extends('layouts.app')

@section('content')

    <div class="card">
        <h2>Customer Accounts</h2>

        <p>Customers registered in the POS system.</p>

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
                {{--
                    @foreach walks the array the controller passed in.
                    $loop->iteration is Blade's built-in counter, so I do not
                    have to keep my own $i variable.
                --}}
                @foreach ($customers as $customer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $customer['full_name'] }}</td>
                        <td>{{ $customer['email'] }}</td>
                        <td>{{ $customer['phone'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- count() works inside Blade because Blade compiles to plain PHP. --}}
        <p class="count">Total customers: {{ count($customers) }}</p>
    </div>

@endsection
