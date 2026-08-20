<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Counter;
use App\Models\Institution;
use App\Models\ServiceType;

class CounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institution = Institution::first();
        
        $counters = [
            ['name' => 'Loket 1', 'number' => 1, 'service_code' => 'A'],
            ['name' => 'Loket 2', 'number' => 2, 'service_code' => 'A'],
            ['name' => 'Loket 3', 'number' => 3, 'service_code' => 'B'],
            ['name' => 'Loket 4', 'number' => 4, 'service_code' => 'B'],
            ['name' => 'Loket 5', 'number' => 5, 'service_code' => 'C'],
        ];

        foreach ($counters as $c) {
            $serviceType = ServiceType::where('code', $c['service_code'])->first();
            
            Counter::create([
                'institution_id' => $institution->id,
                'service_type_id' => $serviceType->id,
                'name' => $c['name'],
                'number' => $c['number'],
                'status' => 'active',
            ]);
        }
    }
}
