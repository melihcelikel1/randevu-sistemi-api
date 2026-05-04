<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Seed default dental services.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Diş Muayenesi',
                'description' => 'Genel kontrol ve teşhis',
                'duration' => 30,
                'price' => 500,
            ],
            [
                'name' => 'Diş Beyazlatma',
                'description' => 'Profesyonel beyazlatma işlemi',
                'duration' => 45,
                'price' => 1200,
            ],
            [
                'name' => 'Dolgu',
                'description' => 'Çürük temizliği ve kompozit dolgu',
                'duration' => 40,
                'price' => 900,
            ],
            [
                'name' => 'Kanal Tedavisi',
                'description' => 'Kanal temizliği ve kapatma işlemi',
                'duration' => 60,
                'price' => 1800,
            ],
            [
                'name' => 'İmplant',
                'description' => 'Tek diş implant uygulaması',
                'duration' => 90,
                'price' => 7500,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['name' => $service['name']],
                $service
            );
        }
    }
}
