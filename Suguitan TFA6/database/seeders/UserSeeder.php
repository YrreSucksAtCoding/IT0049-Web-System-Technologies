<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Fills the users table with the five staff accounts from TFA5.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['username' => 'admin01',   'full_name' => 'Yrre Suguitan', 'role' => 'Administrator', 'password' => 'admin123'],
            ['username' => 'cashier01', 'full_name' => 'Bea Lopez',     'role' => 'Cashier',       'password' => 'cash123'],
            ['username' => 'cashier02', 'full_name' => 'Ken Tolentino', 'role' => 'Cashier',       'password' => 'cash456'],
            ['username' => 'stock01',   'full_name' => 'Rina Bautista', 'role' => 'Stock Clerk',   'password' => 'stock123'],
            ['username' => 'manager01', 'full_name' => 'Carlo Mendoza', 'role' => 'Manager',       'password' => 'mgr123'],
        ];

        foreach ($users as $user) {
            // Hash::make() before the row is written. A seeder is still a
            // place where passwords are stored, so plain text here would be
            // the same mistake as plain text anywhere else.
            $user['password'] = Hash::make($user['password']);

            User::create($user);
        }
    }
}
