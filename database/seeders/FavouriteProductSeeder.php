<?php

namespace Database\Seeders;

use App\Models\FavouriteProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class FavouriteProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();
        
        // Create 30 favourite product entries
        for ($i = 0; $i < 30; $i++) {
            $user = $users->random();
            $product = $products->random();
            
            // Check if this user-product combination already exists
            $exists = FavouriteProduct::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->exists();
            
            if (!$exists) {
                FavouriteProduct::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                ]);
            }
        }
    }
} 