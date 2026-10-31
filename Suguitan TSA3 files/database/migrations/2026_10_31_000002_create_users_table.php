<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the users table — one row, shown on the Profile page.
 *
 * IMPORTANT: delete Laravel's own migration first, or both files try to
 * create a table called "users":
 *   rm database/migrations/0001_01_01_000000_create_users_table.php
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Unique, as the activity requires.
            $table->string('username', 50)->unique();

            $table->string('full_name', 100);

            // Also made unique. The activity does not ask for it, but two
            // accounts sharing an email address is never something I want,
            // and ->unique() costs nothing here.
            $table->string('email', 100)->unique();

            $table->timestamps();
        });

        // No password column: nothing in this activity logs in. Adding one
        // "just in case" would mean storing a hash nothing ever checks.
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
