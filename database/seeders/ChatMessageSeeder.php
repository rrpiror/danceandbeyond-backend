<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Database\Seeder;

class ChatMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chats = Chat::all();
        
        $messages = [
            'Hi there!',
            'How are you?',
            'I\'m interested in your product.',
            'Is this still available?',
            'What\'s the condition like?',
            'Can you do a discount?',
            'When can I pick it up?',
            'Do you offer shipping?',
            'Thanks for your help!',
            'I\'ll get back to you soon.',
            'Can I see more photos?',
            'What size is it?',
            'Does it fit true to size?',
            'How long have you had it?',
            'Is the price negotiable?',
        ];
        
        foreach ($chats as $chat) {
            // Add 3-10 messages to each chat
            $numMessages = rand(3, 10);
            $lastMessageTime = now()->subDays(rand(1, 7));
            
            for ($i = 0; $i < $numMessages; $i++) {
                // Randomly decide if the buyer or seller is sending the message
                $isBuyerSending = rand(0, 1) === 0;
                $sender_id = $isBuyerSending ? $chat->buyer_id : $chat->seller_id;
                $receiver_id = $isBuyerSending ? $chat->seller_id : $chat->buyer_id;
                
                $message = $messages[array_rand($messages)];
                $messageTime = $lastMessageTime->copy()->addMinutes(rand(1, 60));
                
                ChatMessage::create([
                    'chat_id' => $chat->id,
                    'sender_id' => $sender_id,
                    'receiver_id' => $receiver_id,
                    'message' => $message,
                    'is_read' => rand(0, 1),
                    'created_at' => $messageTime,
                    'updated_at' => $messageTime,
                ]);
                
                $lastMessageTime = $messageTime;
            }
        }
    }
} 