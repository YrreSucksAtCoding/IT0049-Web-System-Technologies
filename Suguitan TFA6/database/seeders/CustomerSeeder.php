<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

/**
 * Fills the customers table with the same five records that were hard-coded
 * in my TFA5 controller. The data has not changed — only where it lives.
 */
class CustomerSeeder extends Seeder
{
    /**
     * Runs on `php artisan db:seed`.
     */
    public function run(): void
    {
        $customers = [
            ['full_name' => 'Ana Reyes',       'email' => 'ana.reyes@email.com',    'phone' => '0917-111-2233'],
            ['full_name' => 'Mark Villanueva', 'email' => 'mark.v@email.com',       'phone' => '0918-222-3344'],
            ['full_name' => 'Jenny Cruz',      'email' => 'jenny.cruz@email.com',   'phone' => '0919-333-4455'],
            ['full_name' => 'Paolo Santos',    'email' => 'paolo.santos@email.com', 'phone' => '0920-444-5566'],
            ['full_name' => 'Liza Domingo',    'email' => 'liza.domingo@email.com', 'phone' => '0921-555-6677'],
        ];

        // Seeding through the MODEL rather than DB::table() on purpose:
        // Customer::create() fills created_at and updated_at for me, while
        // the query builder goes straight to SQL and would leave them null.
        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        // No id values anywhere above, so auto-increment assigns them and
        // this seeder can be re-run on any empty table.
    }
}
