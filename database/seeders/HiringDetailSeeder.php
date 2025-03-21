<?php

namespace Database\Seeders;

use App\Models\HiringDetail;
use App\Models\Product;
use Illuminate\Database\Seeder;

class HiringDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all products that are for hire
        $hireProducts = Product::where('type', 'hire')->get();
        
        foreach ($hireProducts as $product) {
            $additionalFeePerDay = rand(500, 2000) / 100; // Random fee between 5 and 20
            $minHireDays = rand(1, 3);
            
            HiringDetail::create([
                'product_id' => $product->id,
                'min_hire_days' => $minHireDays,
                'additional_fee_per_day' => $additionalFeePerDay,
            ]);
        }
    }
} 