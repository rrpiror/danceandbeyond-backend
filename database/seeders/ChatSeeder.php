<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $userCount = $users->count();
        
        // Create 10 chat conversations
        for ($i = 0; $i < 10; $i++) {
            // Get two random users for the chat
            $userIndex1 = rand(0, $userCount - 1);
            do {
                $userIndex2 = rand(0, $userCount - 1);
            } while ($userIndex1 === $userIndex2);
            
            $buyer = $users[$userIndex1];
            $seller = $users[$userIndex2];
            
            // Check if a chat between these users already exists
            $existingChat = Chat::where(function ($query) use ($buyer, $seller) {
                $query->where('buyer_id', $buyer->id)
                    ->where('seller_id', $seller->id);
            })->orWhere(function ($query) use ($buyer, $seller) {
                $query->where('buyer_id', $seller->id)
                    ->where('seller_id', $buyer->id);
            })->first();
            
            if (!$existingChat) {
                Chat::create([
                    'buyer_id' => $buyer->id,
                    'seller_id' => $seller->id,
                ]);
            }
        }
    }
} 