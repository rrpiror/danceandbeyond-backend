<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductColour;
use App\Models\ProductSize;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $colours = ProductColour::all();
        $sizes = ProductSize::all();
        $colourCount = $colours->count();
        $sizeCount = $sizes->count();

        foreach ($products as $product) {
            // Each product gets 2-5 variants (colour/size combinations)
            $numVariants = rand(2, 5);
            $usedCombinations = [];

            for ($i = 0; $i < $numVariants; $i++) {
                // Get a random colour and size combination that hasn't been used
                $attempts = 0;
                do {
                    $colourId = $colours[rand(0, $colourCount - 1)]->id;
                    $sizeId = $sizes[rand(0, $sizeCount - 1)]->id;
                    $combination = "{$colourId}-{$sizeId}";
                    $attempts++;
                } while (in_array($combination, $usedCombinations) && $attempts < 20);

                if ($attempts >= 20) {
                    continue; // Skip if we can't find a unique combination
                }

                $usedCombinations[] = $combination;

                ProductVariant::create([
                    'product_id' => $product->id,
                    'colour_id' => $colourId,
                    'size_id' => $sizeId,
                    'quantity' => rand(1, 10),
                ]);
            }
        }
    }
}
