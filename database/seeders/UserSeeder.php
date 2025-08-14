<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'test@test.test',
            'phone_number' => '+1234567890',
            'email_verified' => true,
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'admin',
            'type' => 'individual',
            'status' => 'active',
        ]);

        // Create school users
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Dance School $i",
                'email' => "school$i@example.com",
                'phone_number' => '+44123456' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'email_verified' => true,
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'type' => 'school',
                'status' => 'active',
            ]);
        }

        // Create individual users
        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'name' => "User $i",
                'email' => "user$i@example.com",
                'phone_number' => '+1555' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'email_verified' => true,
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'type' => 'individual',
                'status' => 'active',
            ]);
        }
    }
} 