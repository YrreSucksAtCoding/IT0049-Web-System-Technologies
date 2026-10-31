{{--
    resources/views/tasks/today.blade.php

    The Welcome page. Shows ONLY tasks dated today.

    Shown by: TaskController::today()   Route: GET /
    Receives: $title, $tasks, $date, $done

    The filtering already happened in the model's today() scope. This view
    does not decide what "today" means — it only prints what it was given.
--}}
@extends('layouts.app')

@section('content')

    <div class="card">
        <h2>Today's Tasks</h2>
        <p class="sub">{{ $date->format('l, F j, Y') }}</p>

        @if ($tasks->isNotEmpty())
            <div class="summary">
                {{ $done }} of {{ $tasks->count() }} done
            </div>
        @endif

        @forelse ($tasks as $task)
            {{-- no date shown: every task on this page is today's --}}
            @include('tasks.partials.row', ['task' => $task])
        @empty
            <div class="empty">
                Nothing scheduled for today.<br>
                <a href="{{ route('tasks.index') }}">See all tasks</a>
            </div>
        @endforelse
    </div>

@endsection
