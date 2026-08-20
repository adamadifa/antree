<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institution;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Institution::create([
            'name' => 'Pusat Layanan Publik Kota Jayakarta',
            'app_name' => 'Antree',
            'address' => 'Jl. Merdeka No. 123, Jayakarta',
            'phone' => '021-12345678',
            'email' => 'support@antree.local',
            'operating_hours' => 'Senin - Jumat: 08:00 - 16:00, Sabtu: 08:00 - 12:00',
            'footer_text' => 'Antree - Professional Queue Management',
            'is_active' => true,
        ]);
    }
}
