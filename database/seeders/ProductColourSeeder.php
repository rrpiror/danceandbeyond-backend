<?php

namespace Database\Seeders;

use App\Models\Colour;
use App\Models\Product;
use App\Models\ProductColour;
use Illuminate\Database\Seeder;

class ProductColourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $colours = Colour::all();
        $colourCount = $colours->count();
        
        foreach ($products as $product) {
            // Each product gets 1-3 colours
            $numColours = rand(1, 3);
            $usedColourIds = [];
            
            for ($i = 0; $i < $numColours; $i++) {
                // Get a random colour that hasn't been used for this product yet
                do {
                    $colourId = $colours[rand(0, $colourCount - 1)]->id;
                } while (in_array($colourId, $usedColourIds));
                
                $usedColourIds[] = $colourId;
                
                ProductColour::create([
                    'product_id' => $product->id,
                    'colour_id' => $colourId,
                ]);
            }
        }
    }
} 