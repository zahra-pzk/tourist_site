<?php

namespace Tests\Feature;

use App\Models\Place;
use App\Models\TravelExperience;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Country;
use App\Models\Province;

class PlaceApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user     = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');

        $country = Country::factory()->create();
        $this->province = Province::factory()->create([
            'country_id' => $country->id,
        ]);
    }

    public function test_can_crud_places(): void
    {
        $response = $this->postJson('/api/places', [
            'name'        => 'TestPlace',
            'description' => 'A nice place',
            'province_id' => $this->province->id,
            'image_url'   => 'https://example.com/img.jpg',
        ]);
        $response->assertStatus(201)
                 ->assertJsonPath('data.name', 'TestPlace');

        $placeId = $response->json('data.id');

        $response = $this->getJson('/api/places');
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'TestPlace']);

        $response = $this->getJson("/api/places/{$placeId}");
        $response->assertStatus(200)
                 ->assertJsonPath('id', $placeId);

        $response = $this->putJson("/api/places/{$placeId}", [
            'name'        => 'NewPlace',
            'description' => 'Updated',
            'province_id' => $this->province->id,
            'image_url'   => 'https://example.com/new.jpg',
        ]);
        $response->assertStatus(200)
                 ->assertJsonPath('data.name', 'NewPlace');

        $response = $this->deleteJson("/api/places/{$placeId}");
        $response->assertStatus(200)
                 ->assertJson(['message' => 'مکان دیدنی حذف شد.']);
    }
}
