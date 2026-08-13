<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserReview;
use Illuminate\Database\Seeder;

class UserReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $userCount = $users->count();
        
        $reviewComments = [
            'Great seller, item as described!',
            'Fast shipping and good communication.',
            'Item was in perfect condition.',
            'Excellent service, highly recommend!',
            'Very responsive and helpful.',
            'The item was exactly what I needed.',
            'Smooth transaction from start to finish.',
            'Very professional and friendly.',
            'Would definitely buy from again!',
            'Item arrived earlier than expected.',
        ];
        
        // Create 20 reviews
        for ($i = 0; $i < 20; $i++) {
            // Get two random users for the review
            $userIndex = random_int(0, $userCount - 1);
            do {
                $sellerIndex = random_int(0, $userCount - 1);
            } while ($userIndex === $sellerIndex);
            
            $user = $users[$userIndex];
            $seller = $users[$sellerIndex];
            
            // Check if this user has already reviewed this seller
            $exists = UserReview::where('user_id', $user->id)
                ->where('seller_id', $seller->id)
                ->exists();
            
            if (!$exists) {
                $rating = random_int(3, 5); // Mostly positive ratings (3-5 stars)
                $description = $reviewComments[random_int(0, count($reviewComments) - 1)];
                
                // Random date within the last 30 days
                $date = now()->subDays(random_int(0, 30));
                
                UserReview::create([
                    'user_id' => $user->id,
                    'seller_id' => $seller->id,
                    'rating' => $rating,
                    'description' => $description,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }
} 
