<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceType;
use App\Models\Institution;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institution = Institution::first();

        $services = [
            ['name' => 'Customer Service', 'code' => 'A', 'color' => '#8b5cf6', 'icon' => 'user-group', 'sort_order' => 1],
            ['name' => 'Teller / Kasir', 'code' => 'B', 'color' => '#3b82f6', 'icon' => 'banknotes', 'sort_order' => 2],
            ['name' => 'Informasi', 'code' => 'C', 'color' => '#10b981', 'icon' => 'information-circle', 'sort_order' => 3],
            ['name' => 'Administrasi', 'code' => 'D', 'color' => '#f59e0b', 'icon' => 'document-text', 'sort_order' => 4],
            ['name' => 'Pengaduan', 'code' => 'E', 'color' => '#ef4444', 'icon' => 'chat-bubble-left-ellipsis', 'sort_order' => 5],
        ];

        foreach ($services as $service) {
            ServiceType::create(array_merge($service, ['institution_id' => $institution->id]));
        }
    }
}
