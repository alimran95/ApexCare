<?php

namespace Database\Seeders;

use App\Models\Clinic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clinics = [
            [
                'name' => 'ApexCare Main Clinic',
                'address' => '123 Main Street, Dhaka 1000',
                'contact_phone' => '+880 2 1234567'
            ],
            [
                'name' => 'ApexCare Gulshan Branch',
                'address' => '456 Gulshan Avenue, Dhaka 1212',
                'contact_phone' => '+880 2 9876543'
            ],
            [
                'name' => 'ApexCare Dhanmondi Center',
                'address' => '789 Dhanmondi Road, Dhaka 1205',
                'contact_phone' => '+880 2 5555666'
            ],
            [
                'name' => 'ApexCare Chittagong Branch',
                'address' => '321 CDA Avenue, Chittagong 4000',
                'contact_phone' => '+880 31 777888'
            ]
        ];

        foreach ($clinics as $clinic) {
            Clinic::firstOrCreate(
                ['name' => $clinic['name']],
                $clinic
            );
        }

        $this->command->info('Sample clinics created successfully!');
    }
}
