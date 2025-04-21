<?php

namespace Tests\Feature;

use App\Models\Place;
use App\Models\TravelExperience;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Country;
use App\Models\Province;

class ProvinceApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');

        $this->country = Country::factory()->create(['name' => 'Landia']);
    }

    public function test_can_crud_provinces(): void
    {
        // CREATE
        $response = $this->postJson('/api/provinces', [
            'name' => 'TestProvince',
            'country_id' => $this->country->id,
        ]);
        $response->assertStatus(201)
                 ->assertJsonPath('data.name', 'TestProvince');

        $provId = $response->json('data.id');

        $response = $this->getJson('/api/provinces');
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'TestProvince']);

        $response = $this->getJson("/api/provinces/{$provId}");
        $response->assertStatus(200)
                 ->assertJsonPath('name', 'TestProvince');

        $response = $this->putJson("/api/provinces/{$provId}", [
            'name' => 'NewProvince',
            'country_id' => $this->country->id,
        ]);
        $response->assertStatus(200)
                 ->assertJsonPath('data.name', 'NewProvince');

        $response = $this->deleteJson("/api/provinces/{$provId}");
        $response->assertStatus(200)
                 ->assertJson(['message' => 'استان با موفقیت حذف شد.']);
    }
}
