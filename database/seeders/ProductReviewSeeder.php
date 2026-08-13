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
                'user_id' => $userIds[random_int(0, count($userIds) - 1)],
                'product_id' => $productIds[random_int(0, count($productIds) - 1)],
                'rating' => random_int(10, 50) / 10,
                'description' => fake()->realText(200),
            ]);
        }
    }
} 
