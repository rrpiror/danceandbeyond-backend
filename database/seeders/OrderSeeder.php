<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemHiringDetail;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\SellerOrder;
use App\Models\User;
use App\Models\ProductColour;
use App\Models\ProductSize;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $addresses = Address::all();
        $paymentMethods = PaymentMethod::all();
        $products = Product::with('media', 'variants.colour', 'variants.size')->get();
        $orderStatuses = OrderStatus::all();
        $sizes = ProductSize::all();
        $colours = ProductColour::all();
        
        // Create 20 orders
        for ($i = 1; $i <= 20; $i++) {
            $user = $users->random();
            $address = $addresses->random();
            $paymentMethod = $paymentMethods->random();
            
            // Random date within the last 30 days
            $date = now()->subDays(rand(0, 30));
            
            // Create JSON for addresses
            $addressesJson = json_encode([
                'shipping' => [
                    'id' => $address->id,
                    'house_number' => $address->house_number,
                    'building_name' => $address->building_name,
                    'street' => $address->street,
                    'town' => $address->town,
                    'city' => $address->city,
                    'postcode' => $address->postcode,
                ],
                'billing' => [
                    'id' => $address->id,
                    'house_number' => $address->house_number,
                    'building_name' => $address->building_name,
                    'street' => $address->street,
                    'town' => $address->town,
                    'city' => $address->city,
                    'postcode' => $address->postcode,
                ],
            ]);
            
            // Create order with initial amount 0
            $order = Order::create([
                'user_id' => $user->id,
                'payment_method_id' => $paymentMethod->id,
                'amount' => 0,
                'addresses' => $addressesJson,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            // Create order items and seller orders
            $this->createOrderItems($order, $products, $orderStatuses, $sizes, $colours);
        }
    }

    private function createOrderItems($order, $products, $orderStatuses, $sizes, $colours)
    {
        // Each order gets 1-3 items
        $numItems = rand(1, 3);
        $usedProductIds = [];
        $sellerOrders = [];
        $totalOrderAmount = 0;

        for ($i = 0; $i < $numItems; $i++) {
            // Get a random product that hasn't been used for this order yet
            do {
                $product = $products->random();
            } while (in_array($product->id, $usedProductIds));

            $usedProductIds[] = $product->id;
            $sellerId = $product->user_id;

            // Check if we already created a seller order for this seller, otherwise create one
            if (!isset($sellerOrders[$sellerId])) {
                $sellerOrders[$sellerId] = SellerOrder::create([
                    'order_id' => $order->id,
                    'seller_id' => $sellerId,
                    'amount' => 0,
                    'transferred_at' => rand(0, 1) ? now()->subDays(rand(1, 30)) : null,
                ]);

                // Attach random order statuses to seller order
                if ($orderStatuses->isNotEmpty()) {
                    $randomStatuses = $orderStatuses->random(rand(1, 3));
                    $sellerOrders[$sellerId]->statuses()->attach($randomStatuses->pluck('id'));
                }
            }

            $sellerOrder = $sellerOrders[$sellerId];

            $isHire = $product->type === 'hire';
            $quantity = rand(1, 6); // Quantity for both hire and sale products
            $hiringDays = null;

            // Create product snapshot
            $productSnapshot = [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'type' => $product->type,
                'media' => $product->media->map(function ($media) {
                    return [
                        'id' => $media->id,
                        'model_type' => $media->model_type,
                        'model_id' => $media->model_id,
                        'uuid' => $media->uuid,
                        'collection_name' => $media->collection_name,
                        'name' => $media->name,
                        'file_name' => $media->file_name,
                        'mime_type' => $media->mime_type,
                        'disk' => $media->disk,
                        'conversions_disk' => $media->conversions_disk,
                        'size' => $media->size,
                        'manipulations' => $media->manipulations,
                        'custom_properties' => $media->custom_properties,
                        'generated_conversions' => $media->generated_conversions,
                        'responsive_images' => $media->responsive_images,
                        'order_column' => $media->order_column,
                        'created_at' => $media->created_at,
                        'updated_at' => $media->updated_at,
                        'original_url' => $media->getUrl(),
                        'preview_url' => $media->getUrl(),
                    ];
                })->toArray(),
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
                $hiringDays = rand($product->hiringDetail->min_hire_days, 10);

                $productSnapshot['hiring_details'] = [
                    'days' => $hiringDays,
                    ...$product->hiringDetail->toArray(),
                ];
            }

            // Include variants in the snapshot
            $productSnapshot['variants'] = $product->variants->take(3)->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'colour_id' => $variant->colour_id,
                    'size_id' => $variant->size_id,
                    'quantity' => $variant->quantity,
                    'colour' => $variant->colour ? [
                        'id' => $variant->colour->id,
                        'name' => $variant->colour->name,
                        'hexcode' => $variant->colour->hexcode,
                    ] : null,
                    'size' => $variant->size ? [
                        'id' => $variant->size->id,
                        'name' => $variant->size->name,
                    ] : null,
                ];
            })->toArray();
            
            $price = $product->price;
            $totalItemPrice = $price * $quantity;

            // Get a random variant for this order item
            $randomVariant = $product->variants->isNotEmpty() ? $product->variants->random() : null;

            // Create Order Item
            $orderItem = OrderItem::create([
                'seller_order_id' => $sellerOrder->id,
                'product_id' => $product->id,
                'variant_id' => $randomVariant?->id,
                'quantity' => $quantity,
                'price' => $price,
                'product_snapshot' => json_encode($productSnapshot),
            ]);

            // Create hiring detail if it's a hire product
            if ($isHire && $hiringDays) {
                OrderItemHiringDetail::create([
                    'order_item_id' => $orderItem->id,
                    'hiring_days' => $hiringDays,
                ]);
            }

            // Update seller order amount
            $sellerOrder->increment('amount', $totalItemPrice);
            
            // Add to total order amount
            $totalOrderAmount += $totalItemPrice;
        }

        // Update the main order with the calculated total amount
        $order->update(['amount' => $totalOrderAmount]);
    }
} 