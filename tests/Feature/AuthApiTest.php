<?php

namespace Tests\Feature;

use App\Models\Place;
use App\Models\TravelExperience;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Country;
use App\Models\Province;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_and_login_and_logout_flow(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertStatus(201)
                 ->assertJson(['message' => 'ثبت‌نام با موفقیت انجام شد.']);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);
        $response->assertStatus(200)
                 ->assertJson(['message' => 'ورود موفقیت‌آمیز بود.']);

        $user = User::where('email', 'test@example.com')->first();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/logout');
        $response->assertStatus(200)
                 ->assertJson(['message' => 'خروج با موفقیت انجام شد.']);
    }
}
