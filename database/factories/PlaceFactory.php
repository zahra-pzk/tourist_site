<?php

namespace Database\Factories;

use App\Models\Place;
use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlaceFactory extends Factory
{
    protected $model = Place::class;

    public function definition(): array
    {
        return [
            'name'        => $this->faker->unique()->city() . ' Sight',
            'description' => $this->faker->paragraph(),
            'province_id' => Province::factory(),
            'image_url'   => $this->faker->imageUrl(400, 300),
        ];
    }
}
