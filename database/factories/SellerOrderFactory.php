<?php

namespace Database\Factories;

use App\Models\SellerOrder;
use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SellerOrder>
 */
class SellerOrderFactory extends Factory
{
    protected $model = SellerOrder::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'seller_id' => User::factory()->seller(),
            'amount' => $this->faker->randomFloat(2, 10, 500),
            'transferred_at' => $this->faker->optional(0.7)->dateTimeBetween('-30 days', 'now'),
        ];
    }

    /**
     * Indicate that the seller order has been transferred.
     */
    public function transferred(): static
    {
        return $this->state(fn (array $attributes) => [
            'transferred_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ]);
    }

    /**
     * Indicate that the seller order has not been transferred yet.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'transferred_at' => null,
        ]);
    }

    /**
     * Create a seller order with a specific amount.
     */
    public function withAmount(float $amount): static
    {
        return $this->state(fn (array $attributes) => [
            'amount' => $amount,
        ]);
    }

    /**
     * Create a seller order for a specific seller.
     */
    public function forSeller(User $seller): static
    {
        return $this->state(fn (array $attributes) => [
            'seller_id' => $seller->id,
        ]);
    }

    /**
     * Create a seller order for a specific order.
     */
    public function forOrder(Order $order): static
    {
        return $this->state(fn (array $attributes) => [
            'order_id' => $order->id,
        ]);
    }
} 