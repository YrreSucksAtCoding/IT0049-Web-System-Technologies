<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the users table — the staff who operate the POS.
 *
 * IMPORTANT: Laravel ships with its own users migration at
 * database/migrations/0001_01_01_000000_create_users_table.php.
 * Delete that file before running migrate, or both files will try to
 * create a table called "users" and the second one fails.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // The activity requires username to be unique. Without this a
            // login lookup could return two rows for the same credentials.
            $table->string('username', 50)->unique();

            // No length given on purpose: the column defaults to 255.
            // A bcrypt hash is exactly 60 characters, so a short column
            // would truncate it and every login would fail silently.
            $table->string('password');

            $table->string('full_name', 100);

            // Shown in the Role column of my Blade view.
            $table->string('role', 30)->default('Cashier');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
