<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use App\Models\OrderItemColour;
use App\Models\ProductColour;
use Illuminate\Database\Seeder;

class OrderItemColourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orderItems = OrderItem::all();
        
        foreach ($orderItems as $orderItem) {
            // Get available colours for this product
            $productColours = ProductColour::where('product_id', $orderItem->product_id)->get();
            
            if ($productColours->count() > 0) {
                // Randomly select one of the available colours
                $productColour = $productColours->random();
                
                OrderItemColour::create([
                    'order_item_id' => $orderItem->id,
                    'colour_id' => $productColour->colour_id,
                ]);
            }
        }
    }
} 