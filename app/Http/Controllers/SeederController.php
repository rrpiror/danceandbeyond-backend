<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SeederController extends Controller
{
    public function seed(Request $request)
    {
        try {
            // Call the default database seeder
            Artisan::call('db:seed');
            return response()->json(['message' => 'Database seeded successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
} 