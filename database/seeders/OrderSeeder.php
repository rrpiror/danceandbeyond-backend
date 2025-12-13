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
use App\Services\OrderService;
use Illuminate\Database\Seeder;
use App\Models\StripeIntent;

class OrderSeeder extends Seeder
{

    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

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
            $this->createOrderItems($order, $products, $orderStatuses);

            $stripeIntent = StripeIntent::create([
                'order_id' => $order->id,
                'payment_intent_id' => 'pi_1M0M0M' . $i,
                'client_secret' => 'cs_1M0M0M' . $i,
                'amount' => $order->amount * 100,
                'currency' => 'gbp',
                'status' => 'requires_payment_method',
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                ],
            ]);
        }
    }

    private function createOrderItems($order, $products, $orderStatuses)
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

            $item = [
                'id' => $product->id,
                'name' => $product->name,
                'type' => $product->type,
                'price' => $product->price,
                'quantity' => $quantity,
                'hiring_days' => $hiringDays,
            ];

            // Create product snapshot
            $productSnapshot = $this->orderService->createProductSnapshot($product);
            
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