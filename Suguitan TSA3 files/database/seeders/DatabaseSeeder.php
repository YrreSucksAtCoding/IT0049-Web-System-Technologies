<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Runs both seeders with one command.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TaskSeeder::class,
        ]);
    }
}
