<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Task model.
 *
 * Both task pages read through this class. The difference between them is
 * one scope, not two different queries written in two controllers.
 */
class Task extends Model
{
    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'status',
        'task_date',
    ];

    /**
     * Casting task_date to 'date' means it comes back as a Carbon object
     * instead of a plain string, so the view can call
     * $task->task_date->format('M j, Y') and ->isToday() directly.
     */
    protected function casts(): array
    {
        return [
            'task_date' => 'date',
        ];
    }

    /**
     * Scope: only tasks dated today.
     *
     * Used by the Welcome page as Task::today()->get().
     *
     * whereDate() compares the DATE part only, so this keeps working even
     * if the column is ever changed to a datetime. today() is Laravel's
     * helper for "midnight this morning" on the app's configured timezone.
     *
     * Putting this on the model rather than in the controller means the
     * definition of "today's tasks" lives in exactly one place.
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('task_date', today());
    }

    /**
     * Scope: the order both pages display tasks in — earliest date first,
     * then alphabetically inside a date so the list is stable.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('task_date')->orderBy('title');
    }

    /**
     * Helper used by the views to colour the status badge.
     * Returning a class name rather than a colour keeps the styling in the
     * stylesheet where it belongs.
     */
    public function statusClass(): string
    {
        return match (strtolower($this->status)) {
            'completed'   => 'badge-done',
            'in progress' => 'badge-doing',
            default       => 'badge-pending',
        };
    }
}
