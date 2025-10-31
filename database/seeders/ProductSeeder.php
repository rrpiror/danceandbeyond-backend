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
        
        for ($i = 1; $i <= 10; $i++) {
            $user = $users->random();
            $brand = $brands->random();
            $category = $categories->random();
            $condition = $conditions->random();
            $productName = $productNames[array_rand($productNames)];
            $description = $descriptions[array_rand($descriptions)];
            
            $price = rand(1000, 20000) / 100; // Random price between 10 and 200
            $type = rand(0, 1) === 0 ? 'sale' : 'hire';
            $isFeatured = rand(0, 10) === 0; // 10% chance of being featured
            
            $product =Product::create([
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
            

            $unsplashImages = [
                'https://plus.unsplash.com/premium_photo-1714226830923-03396831c4f0?q=80&w=3540&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'https://plus.unsplash.com/premium_photo-1675186049419-d48f4b28fe7c?q=80&w=3687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?q=80&w=3220&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=3604&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'https://images.unsplash.com/photo-1445205170230-053b83016050?q=80&w=3542&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'https://images.unsplash.com/photo-1564584217132-2271feaeb3c5?q=80&w=3540&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?q=80&w=3472&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'https://plus.unsplash.com/premium_photo-1673125287084-e90996bad505?q=80&w=3648&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'https://plus.unsplash.com/premium_photo-1675186049409-f9f8f60ebb5e?q=80&w=3687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'https://images.unsplash.com/photo-1467043237213-65f2da53396f?q=80&w=2134&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'https://plus.unsplash.com/premium_photo-1675186049419-d48f4b28fe7c?q=80&w=3687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?q=80&w=3220&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
            ];

            //add 3 random images to the product from unsplash
            for ($j = 0; $j < 3; $j++) {
                $product->addMediaFromUrl($unsplashImages[array_rand($unsplashImages)])
                    ->usingFileName(uniqid() . '.jpg')
                    ->toMediaCollection('images');
            }

            // Add unavailability durations for some products (30% chance)
            if (rand(0, 9) < 3) {
                $numDurations = rand(1, 3); // 1 to 3 unavailability durations
                for ($k = 0; $k < $numDurations; $k++) {
                    $startDate = now()->addDays(rand(1, 60));
                    $endDate = $startDate->copy()->addDays(rand(1, 14));
                    
                    $product->unavailabilityDurations()->create([
                        'start_date' => $startDate->format('Y-m-d'),
                        'end_date' => $endDate->format('Y-m-d'),
                    ]);
                }
            }
        }
    }
} 