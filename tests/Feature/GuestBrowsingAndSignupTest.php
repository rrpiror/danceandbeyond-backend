<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class GuestBrowsingAndSignupTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_browse_products_without_a_token(): void
    {
        $this->getJson('/api/products')->assertOk()->assertJsonPath('success', true);
    }

    public function test_signup_accepts_omitted_phone_and_address(): void
    {
        $service = Mockery::mock(UserService::class);
        $service->shouldReceive('checkEmail')->once()->andReturn(false);
        $service->shouldReceive('create')->once()->andReturn(['user' => ['id' => 1], 'token' => 'test-token']);
        $this->app->instance(UserService::class, $service);

        $this->postJson('/api/signup', [
            'name' => 'Review Tester',
            'email' => 'review@example.test',
            'password' => 'password123',
            'type' => 'individual',
        ])->assertOk()->assertJsonPath('success', true);
    }

    public function test_signup_rejects_an_incomplete_address_if_provided(): void
    {
        $this->postJson('/api/signup', [
            'name' => 'Review Tester',
            'email' => 'review@example.test',
            'password' => 'password123',
            'type' => 'individual',
            'address' => ['city' => 'London'],
        ])->assertStatus(422);
    }

    public function test_users_table_allows_a_missing_phone_number(): void
    {
        $user = User::create([
            'name' => 'Review Tester',
            'email' => 'review@example.test',
            'password' => 'password123',
            'type' => 'individual',
            'status' => 'active',
        ]);

        $this->assertNull($user->fresh()->phone_number);
    }
}
