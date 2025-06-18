<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use App\Models\OrderItemSize;
use App\Models\ProductSize;
use Illuminate\Database\Seeder;

class OrderItemSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orderItems = OrderItem::all();
        
        foreach ($orderItems as $orderItem) {
            // Get available sizes for this product
            $productSizes = ProductSize::where('product_id', $orderItem->product_id)->get();
            
            if ($productSizes->count() > 0) {
                // Randomly select one of the available sizes
                $productSize = $productSizes->random();
                
                OrderItemSize::create([
                    'order_item_id' => $orderItem->id,
                    'size_id' => $productSize->size_id,
                    'quantity' => $productSize->quantity,
                ]);
            }
        }
    }
} 