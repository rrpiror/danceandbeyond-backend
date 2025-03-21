<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call seeders in order of dependencies
        $this->call([
            // Base data
            BrandSeeder::class,
            CategorySeeder::class,
            ConditionSeeder::class,
            SizeSeeder::class,
            ColourSeeder::class,
            FulfillmentOptionSeeder::class,
            
            // Users and related
            UserSeeder::class,
            UserSchoolSeeder::class,
            AddressSeeder::class,
            UserAddressSeeder::class,
            
            // Products and related
            ProductSeeder::class,
            ProductSizeSeeder::class,
            ProductColourSeeder::class,
            ProductFulfillmentOptionSeeder::class,
            HiringDetailSeeder::class,
            HiringUnavailabilityDaySeeder::class,
            FavouriteProductSeeder::class,
            
            // Orders and related
            PaymentMethodSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            OrderItemColourSeeder::class,
            OrderItemSizeSeeder::class,
            
            // Communication
            ChatSeeder::class,
            ChatMessageSeeder::class,
            ChatBlockSeeder::class,
            NotificationSeeder::class,
            UserReviewSeeder::class,
            ProductReviewSeeder::class,
        ]);
    }
}
