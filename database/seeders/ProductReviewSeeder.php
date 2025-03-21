<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductReview;
use App\Models\User;
use App\Models\Product;

class ProductReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users and products IDs to randomly assign reviews
        $userIds = User::pluck('id')->toArray();
        $productIds = Product::pluck('id')->toArray();

        if (empty($userIds) || empty($productIds)) {
            $this->command->info('Please seed users and products first.');
            return;
        }

        // Create 50 random product reviews
        for ($i = 0; $i < 50; $i++) {
            ProductReview::create([
                'user_id' => fake()->randomElement($userIds),
                'product_id' => fake()->randomElement($productIds),
                'rating' => fake()->randomFloat(1, 1, 5),
                'description' => fake()->realText(200),
            ]);
        }
    }
} 