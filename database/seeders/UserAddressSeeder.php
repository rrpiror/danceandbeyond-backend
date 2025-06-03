<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Seeder;

class UserAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $addresses = Address::all();
        $addressCount = $addresses->count();
        $types = ['shipping', 'billing'];

        foreach ($users as $user) {
            // Each user gets 2 addresses 1 shipping and 1 billing
            
            for ($i = 0; $i < 2; $i++) {
                $addressId = $addresses[rand(0, $addressCount - 1)]->id;
                $type = $types[$i];
                
                // Check if this user-address combination already exists
                $exists = UserAddress::where('user_id', $user->id)
                    ->where('address_id', $addressId)
                    ->exists();
                
                if (!$exists) {
                    UserAddress::create([
                        'user_id' => $user->id,
                        'address_id' => $addressId,
                        'type' => $type,
                    ]);
                }
            }
        }
    }
} 