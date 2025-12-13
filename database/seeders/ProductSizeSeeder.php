<?php

namespace Database\Seeders;

use App\Models\ProductSize;
use Illuminate\Database\Seeder;

class ProductSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = [
            'XXS',
            'XS',
            'S',
            'M',
            'L',
            'XL',
            'XXL',
        ];

        foreach ($sizes as $sizeName) {
            ProductSize::create([
                'name' => $sizeName,
            ]);
        }
    }
}
