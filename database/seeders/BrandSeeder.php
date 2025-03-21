<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            'Nike',
            'Adidas',
            'Puma',
            'Reebok',
            'Under Armour',
            'Lululemon',
            'Fabletics',
            'Capezio',
            'Bloch',
            'Grishko',
            'Freed of London',
            'Russian Pointe',
            'Gaynor Minden',
            'So Danca',
            'Sansha',
        ];

        foreach ($brands as $brandName) {
            Brand::create([
                'name' => $brandName,
            ]);
        }
    }
} 