<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the tasks table.
 *
 * Same schema as TSA1, described as PHP instead of hand-written SQL.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->string('title', 150);

            // The activity requires a default. A task created without a
            // status is a task nobody has started, so 'pending' is right.
            $table->string('status', 20)->default('pending');

            // DATE, not string and not datetime.
            //   - a string cannot be compared or sorted correctly
            //   - a datetime would make "is this task today?" depend on the
            //     time of day, and these tasks have no time of day
            $table->date('task_date');

            // Required by the activity, and Eloquent expects them anyway.
            $table->timestamps();

            // The Welcome page filters on task_date on every request, so it
            // is worth an index even on a table this small.
            $table->index('task_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
