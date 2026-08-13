<?php

namespace Database\Seeders;

use App\Models\FulfillmentOption;
use App\Models\Product;
use App\Models\ProductFulfillmentOption;
use Illuminate\Database\Seeder;

class ProductFulfillmentOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $fulfillmentOptions = FulfillmentOption::all();
        $optionCount = $fulfillmentOptions->count();

        foreach ($products as $product) {
            // Each product gets 1-3 fulfillment options
            $numOptions = random_int(1, 2);
            $usedOptionIds = [];

            for ($i = 0; $i < $numOptions; $i++) {
                // Get a random option that hasn't been used for this product yet
                do {
                    $optionId = $fulfillmentOptions[random_int(0, $optionCount - 1)]->id;
                } while (in_array($optionId, $usedOptionIds));

                $usedOptionIds[] = $optionId;

                ProductFulfillmentOption::create([
                    'product_id' => $product->id,
                    'fulfillment_option_id' => $optionId,
                ]);
            }
        }
    }
}
