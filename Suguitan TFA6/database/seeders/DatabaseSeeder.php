<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * The seeder Laravel runs by default. It does no work itself — it just
 * calls the two seeders in order, so one command fills both tables.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CustomerSeeder::class,
            UserSeeder::class,
        ]);
    }
}
