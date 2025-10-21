<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if it doesn't exist
        $admin = User::firstOrNew(['email' => 'admin@apexcare.com']);
        
        if (!$admin->exists) {
            $admin->fill([
                'name' => 'Admin User',
                'email' => 'admin@apexcare.com',
                'password' => Hash::make('password'), // Change this in production!
                'role' => 'admin',
                'phone' => '1234567890',
                'is_active' => true,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ])->save();

            $this->command->info('Admin user created successfully!');
            $this->command->warn('Please change the default password after first login!');
        } else {
            $this->command->info('Admin user already exists.');
        }
    }
}
