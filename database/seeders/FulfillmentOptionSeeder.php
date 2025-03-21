<?php

namespace Database\Seeders;

use App\Models\FulfillmentOption;
use Illuminate\Database\Seeder;

class FulfillmentOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $options = [
            [
                'name' => 'Collection',
                'description' => 'Ready to collect within 1-2 business days',
            ],
            [
                'name' => 'Delivery',
                'description' => 'Delivery within 3-5 business days',
            ],
        ];

        foreach ($options as $option) {
            FulfillmentOption::create($option);
        }
    }
}
