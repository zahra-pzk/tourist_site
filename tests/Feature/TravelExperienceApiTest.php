<?php

namespace Tests\Feature;

use App\Models\Place;
use App\Models\TravelExperience;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Country;
use App\Models\Province;

class TravelExperienceApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user     = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');

        $country       = Country::factory()->create();
        $province      = Province::factory()->create(['country_id' => $country->id]);
        $this->place   = Place::factory()->create(['province_id' => $province->id]);
    }

    public function test_user_can_store_travel_experience(): void
    {
        $payload = [
            'place_id'      => $this->place->id,
            'has_traveled'  => true,
            'travel_time'   => now()->subYear()->format('Y-m-d'),
            'liked_points'  => ['منظره زیبا', 'راحتی'],
            'negative_points'=> ['گرونی'],
            'suitable_for'  => ['خانواده'],
            'rating'        => 8,
            'extra_notes'   => 'خیلی عالی بود!',
        ];

        $response = $this->postJson('/api/travel-experiences', $payload);
        $response->assertStatus(201)
                 ->assertJsonPath('data.place_id', $this->place->id)
                 ->assertJsonPath('data.has_traveled', true);
    }

    public function test_for_place_returns_average_and_list(): void
    {
        TravelExperience::factory()->createMany([
            ['place_id'=>$this->place->id,'user_id'=>$this->user->id,'has_traveled'=>true,'travel_time'=>'2024-01-01','liked_points'=>['x'],'negative_points'=>[],'suitable_for'=>['y'],'rating'=>5,'extra_notes'=>''],
            ['place_id'=>$this->place->id,'user_id'=>$this->user->id,'has_traveled'=>true,'travel_time'=>'2024-01-02','liked_points'=>['a'],'negative_points'=>[],'suitable_for'=>['b'],'rating'=>7,'extra_notes'=>''],
        ]);

        $response = $this->getJson("/api/places/{$this->place->id}/travel-experiences");
        $response->assertStatus(200)
                 ->assertJsonPath('average_rating', 6.0)
                 ->assertJsonPath('total_reviews', 2)
                 ->assertJsonCount(2, 'experiences');
    }
}
