<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::all();
        $products = Product::all();
        
        foreach ($orders as $order) {
            // Each order gets 1-3 items
            $numItems = rand(1, 3);
            $orderTotal = 0;
            $usedProductIds = [];
            
            for ($i = 0; $i < $numItems; $i++) {
                // Get a random product that hasn't been used for this order yet
                do {
                    $product = $products->random();
                } while (in_array($product->id, $usedProductIds));
                
                $usedProductIds[] = $product->id;
                
                $quantity = rand(1, 3);
                $isHire = $product->type === 'hire';
                
                // Create product snapshot
                $productSnapshot = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'type' => $product->type,
                    'brand' => [
                        'id' => $product->brand_id,
                        'name' => $product->brand ? $product->brand->name : null,
                    ],
                    'category' => [
                        'id' => $product->category_id,
                        'name' => $product->category ? $product->category->name : null,
                    ],
                    'condition' => [
                        'id' => $product->condition_id,
                        'name' => $product->condition ? $product->condition->name : null,
                    ],
                ];
                
                if ($isHire && $product->hiringDetail) {
                    $days = rand($product->hiringDetail->min_hire_days, 10);
                    $price = $product->price + ($product->hiringDetail->additional_fee_per_day * $days);
                    
                    $productSnapshot['hiring_details'] = [
                        'days' => $days,
                        'start_date' => now()->addDays(rand(1, 7))->format('Y-m-d'),
                        'additional_fee_per_day' => $product->hiringDetail->additional_fee_per_day,
                    ];
                } else {
                    $price = $product->price;
                }
                
                $totalPrice = $price * $quantity;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'product_snapshot' => json_encode($productSnapshot),
                ]);
                
                $orderTotal += $totalPrice;
            }
            
            // Update order total
            $order->update(['amount' => $orderTotal]);
        }
    }
} 