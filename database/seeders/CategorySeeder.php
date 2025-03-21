<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Ballet',
            'Contemporary',
            'Jazz',
            'Tap',
            'Hip Hop',
            'Ballroom',
            'Latin',
            'Folk',
            'Irish',
            'Flamenco',
            'Bharatanatyam',
            'Kathak',
            'Bollywood',
            'Accessories',
            'Shoes',
            'Costumes',
            'Practice Wear',
            'Performance Wear',
        ];

        foreach ($categories as $categoryName) {
            Category::create([
                'name' => $categoryName,
            ]);
        }
    }
} 