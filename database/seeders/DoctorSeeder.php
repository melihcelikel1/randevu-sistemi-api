<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = [
            ['name' => 'Dr. Ayse Kaya', 'specialty' => 'Ortodonti'],
            ['name' => 'Dr. Mehmet Demir', 'specialty' => 'Endodonti'],
            ['name' => 'Dr. Elif Yilmaz', 'specialty' => 'Protetik Dis Tedavisi'],
        ];

        foreach ($doctors as $doctor) {
            Doctor::updateOrCreate(
                ['name' => $doctor['name']],
                $doctor
            );
        }
    }
}
