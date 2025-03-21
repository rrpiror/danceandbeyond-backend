<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\User;
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
        
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        
        // Create 20 orders
        for ($i = 1; $i <= 20; $i++) {
            $user = $users->random();
            $address = $addresses->random();
            $paymentMethod = $paymentMethods->random();
            $status = $statuses[array_rand($statuses)];
            $paymentConfirmed = rand(0, 1);
            
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
            
            Order::create([
                'user_id' => $user->id,
                'payment_method_id' => $paymentMethod->id,
                'amount' => 0, // Will be updated after adding order items
                'payment_confirmed' => $paymentConfirmed,
                'status' => $status,
                'addresses' => $addressesJson,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
} 