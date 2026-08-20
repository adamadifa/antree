<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Institution;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Institutions
        $this->call(InstitutionSeeder::class);
        $institution = Institution::first();

        // 2. Service Types
        $this->call(ServiceTypeSeeder::class);

        // 3. Counters
        $this->call(CounterSeeder::class);

        // 4. Admin User
        User::factory()->create([
            'name' => 'Admin Antree',
            'email' => 'admin@antree.local',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'institution_id' => $institution->id,
        ]);

        // 5. Operator Users
        for ($i = 1; $i <= 5; $i++) {
            User::factory()->create([
                'name' => "Operator Loket $i",
                'email' => "operator$i@antree.local",
                'password' => bcrypt('password'),
                'role' => 'operator',
                'institution_id' => $institution->id,
            ]);
        }
    }
}
