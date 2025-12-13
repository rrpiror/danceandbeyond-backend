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
            ProductSizeSeeder::class,
            ProductColourSeeder::class,
            FulfillmentOptionSeeder::class,

            // Users and related
            UserSeeder::class,
            OrganisationSeeder::class,
            AddressSeeder::class,
            UserAddressSeeder::class,

            // Products and related
            ProductSeeder::class,
            ProductVariantSeeder::class,
            ProductFulfillmentOptionSeeder::class,
            HiringDetailSeeder::class,
            FavouriteProductSeeder::class,

            // Orders and related
            PaymentMethodSeeder::class,
            OrderStatusSeeder::class,
            OrderSeeder::class,
            ShippingServiceProviderSeeder::class,

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
