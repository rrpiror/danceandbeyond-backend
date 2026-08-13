<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\ChatBlock;
use Illuminate\Database\Seeder;

class ChatBlockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chats = Chat::all();
        
        // Block 2 random chats
        $blockedChats = $chats->random(2);
        
        foreach ($blockedChats as $chat) {
            // Randomly decide which user blocked the chat
            $blockingUser = random_int(0, 1) === 0 ? $chat->buyer_id : $chat->seller_id;
            
            ChatBlock::create([
                'user_id' => $blockingUser,
                'chat_id' => $chat->id,
            ]);
        }
    }
} 
