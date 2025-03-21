<?php

namespace Database\Seeders;

use App\Models\HiringDetail;
use App\Models\HiringUnavailabilityDay;
use Illuminate\Database\Seeder;

class HiringUnavailabilityDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hiringDetails = HiringDetail::all();
        
        foreach ($hiringDetails as $hiringDetail) {
            // Add 0-5 unavailable days for each hiring detail
            $numDays = rand(0, 5);
            
            // Start from today and add random days in the future
            $startDate = now();
            $usedDates = [];
            
            for ($i = 0; $i < $numDays; $i++) {
                // Random day within the next 30 days
                do {
                    $daysToAdd = rand(1, 30);
                    $date = $startDate->copy()->addDays($daysToAdd)->format('Y-m-d');
                } while (in_array($date, $usedDates));
                
                $usedDates[] = $date;
                
                HiringUnavailabilityDay::create([
                    'hiring_detail_id' => $hiringDetail->id,
                    'date' => $date,
                ]);
            }
        }
    }
} 