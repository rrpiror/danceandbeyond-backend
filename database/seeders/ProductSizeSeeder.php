<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Size;
use Illuminate\Database\Seeder;

class ProductSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $sizes = Size::all();
        $sizeCount = $sizes->count();
        
        foreach ($products as $product) {
            // Each product gets 1-4 sizes
            $numSizes = rand(1, 4);
            $usedSizeIds = [];
            
            for ($i = 0; $i < $numSizes; $i++) {
                // Get a random size that hasn't been used for this product yet
                do {
                    $sizeId = $sizes[rand(0, $sizeCount - 1)]->id;
                } while (in_array($sizeId, $usedSizeIds));
                
                $usedSizeIds[] = $sizeId;
                
                ProductSize::create([
                    'product_id' => $product->id,
                    'size_id' => $sizeId,
                    'quantity' => rand(1, 10),
                ]);
            }
        }
    }
} 