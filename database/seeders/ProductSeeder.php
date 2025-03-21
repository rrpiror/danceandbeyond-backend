<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $brands = Brand::all();
        $categories = Category::all();
        $conditions = Condition::all();
        
        $productNames = [
            'Ballet Shoes',
            'Pointe Shoes',
            'Jazz Shoes',
            'Tap Shoes',
            'Dance Leotard',
            'Dance Tights',
            'Ballet Tutu',
            'Dance Skirt',
            'Dance Shorts',
            'Dance Top',
            'Warm-up Booties',
            'Leg Warmers',
            'Dance Bag',
            'Performance Costume',
            'Practice Wear Set',
        ];
        
        $descriptions = [
            'Perfect for beginners and professionals alike.',
            'Comfortable and durable for long practice sessions.',
            'Stylish and functional for all your dance needs.',
            'High-quality material that will last for years.',
            'Designed with dancers in mind for maximum comfort and flexibility.',
            'Ideal for performances and competitions.',
            'Great for practice sessions and classes.',
            'Lightweight and breathable material.',
            'Elegant design for a professional look.',
            'Versatile piece for various dance styles.',
        ];
        
        // Create 50 products
        for ($i = 1; $i <= 50; $i++) {
            $user = $users->random();
            $brand = $brands->random();
            $category = $categories->random();
            $condition = $conditions->random();
            $productName = $productNames[array_rand($productNames)];
            $description = $descriptions[array_rand($descriptions)];
            
            $price = rand(1000, 20000) / 100; // Random price between 10 and 200
            $type = rand(0, 1) === 0 ? 'sale' : 'hire';
            $isFeatured = rand(0, 10) === 0; // 10% chance of being featured
            
            Product::create([
                'user_id' => $user->id,
                'brand_id' => $brand->id,
                'category_id' => $category->id,
                'condition_id' => $condition->id,
                'name' => "$brand->name $productName " . Str::random(4),
                'description' => $description,
                'price' => $price,
                'type' => $type,
                'is_featured' => $isFeatured,
            ]);
        }
    }
} 