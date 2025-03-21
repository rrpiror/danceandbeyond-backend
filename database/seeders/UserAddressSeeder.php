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
            // Each user gets 1-2 addresses
            $numAddresses = rand(1, 2);
            
            for ($i = 0; $i < $numAddresses; $i++) {
                $addressId = $addresses[rand(0, $addressCount - 1)]->id;
                $type = $types[array_rand($types)];
                
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