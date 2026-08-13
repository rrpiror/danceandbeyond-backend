<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        $notifications = [
            [
                'title' => 'Order Placed',
                'description' => 'Your order has been placed successfully.',
                'link' => '/orders/1',
            ],
            [
                'title' => 'Order Shipped',
                'description' => 'Your order has been shipped.',
                'link' => '/orders/2',
            ],
            [
                'title' => 'Order Delivered',
                'description' => 'Your order has been delivered.',
                'link' => '/orders/3',
            ],
            [
                'title' => 'New Message',
                'description' => 'You have a new message.',
                'link' => '/chats/1',
            ],
            [
                'title' => 'Product Sold',
                'description' => 'Your product has been sold.',
                'link' => '/products/1',
            ],
            [
                'title' => 'Product Hired',
                'description' => 'Your product has been hired.',
                'link' => '/products/2',
            ],
            [
                'title' => 'Payment Received',
                'description' => 'You have received a payment.',
                'link' => '/payments/1',
            ],
            [
                'title' => 'Review Received',
                'description' => 'You have received a new review.',
                'link' => '/reviews/1',
            ],
        ];
        
        // Create 30 notifications
        for ($i = 0; $i < 30; $i++) {
            $user = $users->random();
            $notification = $notifications[random_int(0, count($notifications) - 1)];
            
            // Random date within the last 7 days
            $date = now()->subDays(random_int(0, 7))->subHours(random_int(0, 23));
            
            Notification::create([
                'user_id' => $user->id,
                'title' => $notification['title'],
                'description' => $notification['description'],
                'link' => $notification['link'],
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
} 
