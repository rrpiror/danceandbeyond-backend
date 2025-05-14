<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Repositories\ServiceProviderRepository;

class ShippingServiceProviderSeeder extends Seeder
{
    protected ServiceProviderRepository $serviceProviderRepository;

    public function __construct(ServiceProviderRepository $serviceProviderRepository)
    {
        $this->serviceProviderRepository = $serviceProviderRepository;
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = [
            [
                'name' => 'Evri',
                'description' => 'Evri is a global shipping company that provides shipping services to customers.',
            ],

        ];

        foreach ($providers as $provider) {
            $this->serviceProviderRepository->create($provider);
        }
    }
}
