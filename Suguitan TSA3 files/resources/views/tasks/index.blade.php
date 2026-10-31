{{--
    resources/views/tasks/index.blade.php

    The Task List page. Shows EVERY task, ordered by date.

    Shown by: TaskController::index()   Route: GET /tasks
    Receives: $title, $tasks, $byDate, $total, $dayCount

    $byDate is the same tasks grouped by date in the controller, so this
    page can print a heading per day without running another query.
--}}
@extends('layouts.app')

@section('content')

    <div class="card">
        <h2>All Tasks</h2>
        <p class="sub">Every task in the database, earliest date first.</p>

        @if ($total > 0)
            <div class="summary">
                {{ $total }} task{{ $total === 1 ? '' : 's' }}
                across {{ $dayCount }} day{{ $dayCount === 1 ? '' : 's' }}
            </div>
        @endif

        @forelse ($byDate as $day => $dayTasks)
            @php
                // $day is a plain Y-m-d string (it was the grouping key),
                // so it is turned back into a date object for formatting.
                $date = \Carbon\Carbon::parse($day);
            @endphp

            <div class="day-heading">
                {{ $date->format('l, F j, Y') }}

                @if ($date->isToday())
                    &mdash; Today
                @elseif ($date->isYesterday())
                    &mdash; Yesterday
                @elseif ($date->isTomorrow())
                    &mdash; Tomorrow
                @endif
            </div>

            @foreach ($dayTasks as $task)
                @include('tasks.partials.row', ['task' => $task])
            @endforeach
        @empty
            <div class="empty">
                No tasks in the database.<br>
                Run <code>php artisan db:seed</code>.
            </div>
        @endforelse
    </div>

@endsection
