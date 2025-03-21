<?php

namespace Database\Seeders;

use App\Models\Condition;
use Illuminate\Database\Seeder;

class ConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $conditions = [
            'Brand New',
						'Used - Very Good',
						'Used - Good',
        ];

        foreach ($conditions as $conditionName) {
            Condition::create([
                'name' => $conditionName,
            ]);
        }
    }
}
