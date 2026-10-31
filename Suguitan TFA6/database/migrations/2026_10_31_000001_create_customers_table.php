<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the customers table.
 *
 * This is the file that replaces the static $customers array I used in
 * TFA5. Instead of writing CREATE TABLE in phpMyAdmin, the structure is
 * described here in PHP, so `php artisan migrate` can build it on any
 * machine and `php artisan migrate:rollback` can undo it.
 */
return new class extends Migration
{
    /**
     * Build the table. Runs on `php artisan migrate`.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, named "id".
            // Eloquent looks for a key called "id" by default, so using
            // the helper saves me from configuring $primaryKey later.
            $table->id();

            $table->string('full_name', 100);

            // unique() also creates the index, so no separate index line.
            $table->string('email', 100)->unique();

            // STRING, not integer: "0917-111-2233" has dashes and a
            // leading zero, and neither survives a numeric column.
            $table->string('phone', 20);

            // created_at and updated_at. Eloquent writes to these on every
            // save unless the model opts out, so the columns have to exist.
            $table->timestamps();
        });
    }

    /**
     * Undo it. Runs on `php artisan migrate:rollback`.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
