<?php

namespace Database\Seeders;

use App\Models\ProductColour;
use Illuminate\Database\Seeder;

class ProductColourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colours = [
            ['name' => 'Black', 'hexcode' => '#000000'],
            ['name' => 'White', 'hexcode' => '#FFFFFF'],
            ['name' => 'Red', 'hexcode' => '#FF0000'],
            ['name' => 'Blue', 'hexcode' => '#0000FF'],
            ['name' => 'Green', 'hexcode' => '#008000'],
            ['name' => 'Yellow', 'hexcode' => '#FFFF00'],
            ['name' => 'Purple', 'hexcode' => '#800080'],
            ['name' => 'Pink', 'hexcode' => '#FFC0CB'],
            ['name' => 'Orange', 'hexcode' => '#FFA500'],
            ['name' => 'Brown', 'hexcode' => '#A52A2A'],
            ['name' => 'Grey', 'hexcode' => '#808080'],
            ['name' => 'Navy', 'hexcode' => '#000080'],
            ['name' => 'Teal', 'hexcode' => '#008080'],
            ['name' => 'Lavender', 'hexcode' => '#E6E6FA'],
            ['name' => 'Beige', 'hexcode' => '#F5F5DC'],
        ];

        foreach ($colours as $colour) {
            ProductColour::create($colour);
        }
    }
}