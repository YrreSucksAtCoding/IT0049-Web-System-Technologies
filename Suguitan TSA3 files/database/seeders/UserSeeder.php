<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seeds exactly one user — the record the Profile page displays.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username'  => 'yrre',
            'full_name' => 'Yrre Suguitan',
            'email'     => 'yrresuguitan00@gmail.com',
        ]);
    }
}
