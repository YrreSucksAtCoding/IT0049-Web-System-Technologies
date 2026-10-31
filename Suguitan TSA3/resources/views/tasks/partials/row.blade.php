{{--
    resources/views/tasks/partials/row.blade.php

    One task row. Both the Welcome page and the Task List page @include
    this, so a task looks the same on both and only one file has to change
    if that ever stops being true.

    Neither page prints the date here: the Welcome page is all one day, and
    the Task List puts the date in a heading above each group.

    Expects: $task  (a Task model)
--}}
<div class="task">
    <div class="task-title">{{ $task->title }}</div>

    {{-- statusClass() is a method on the Task model, so the colour rule is
         decided in one place rather than repeated in each view. --}}
    <span class="badge {{ $task->statusClass() }}">{{ ucfirst($task->status) }}</span>
</div>
