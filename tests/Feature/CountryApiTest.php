<?php

namespace Tests\Feature;

use App\Models\Place;
use App\Models\TravelExperience;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Country;
use App\Models\Province;

class CountryApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');
    }

    public function test_can_crud_countries(): void
    {
        $response = $this->postJson('/api/countries', ['name' => 'TestLand']);
        $response->assertStatus(201)
                 ->assertJsonPath('data.name', 'TestLand');

        $countryId = $response->json('data.id');

        $response = $this->getJson('/api/countries');
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'TestLand']);

        $response = $this->getJson("/api/countries/{$countryId}");
        $response->assertStatus(200)
                 ->assertJson(['id' => $countryId, 'name' => 'TestLand']);

        $response = $this->putJson("/api/countries/{$countryId}", ['name' => 'NewLand']);
        $response->assertStatus(200)
                 ->assertJsonPath('data.name', 'NewLand');

        $response = $this->deleteJson("/api/countries/{$countryId}");
        $response->assertStatus(200)
                 ->assertJson(['message' => 'کشور با موفقیت حذف شد.']);
    }
}
