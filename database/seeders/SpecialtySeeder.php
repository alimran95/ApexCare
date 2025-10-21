<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialties = [
            'General Medicine',
            'Gynaecology',
            'Renal Medicine',
            'Thyroid and Hormone',
            'Cardiology',
            'Dermatology',
            'Orthopedics',
            'Neurology',
            'Pediatrics',
            'Ophthalmology',
            'ENT (Ear, Nose, Throat)',
            'Psychiatry',
            'Gastroenterology',
            'Pulmonology',
            'Urology'
        ];

        foreach ($specialties as $specialty) {
            Specialty::firstOrCreate(['name' => $specialty]);
        }

        $this->command->info('Medical specialties created successfully!');
    }
}
