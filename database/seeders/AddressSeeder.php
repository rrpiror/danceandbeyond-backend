<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = ['London', 'Manchester', 'Birmingham', 'Liverpool', 'Leeds', 'Glasgow', 'Edinburgh', 'Bristol', 'Cardiff', 'Belfast'];
        $postcodes = ['SW1A 1AA', 'M1 1AA', 'B1 1AA', 'L1 1AA', 'LS1 1AA', 'G1 1AA', 'EH1 1AA', 'BS1 1AA', 'CF10 1AA', 'BT1 1AA'];
        
        for ($i = 1; $i <= 30; $i++) {
            $cityIndex = array_rand($cities);
            
            Address::create([
                'house_number' => $i,
                'building_name' => $i % 3 === 0 ? "Building $i" : null,
                'street' => "Dance Street",
                'town' => $i % 2 === 0 ? "Town $i" : null,
                'city' => $cities[$cityIndex],
                'postcode' => $postcodes[$cityIndex],
            ]);
        }
    }
} 