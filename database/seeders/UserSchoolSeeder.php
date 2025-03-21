<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserSchool;
use Illuminate\Database\Seeder;

class UserSchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all school type users
        $schoolUsers = User::where('type', 'school')->get();

        foreach ($schoolUsers as $user) {
            UserSchool::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'website' => 'https://www.' . strtolower(str_replace(' ', '', $user->name)) . '.com',
                'description' => "Welcome to {$user->name}! We offer a variety of dance classes for all ages and skill levels. Our experienced instructors are dedicated to helping you achieve your dance goals.",
            ]);
        }
    }
} 