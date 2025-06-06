<?php

namespace Database\Factories;

use App\Models\Province;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProvinceFactory extends Factory
{
    protected $model = Province::class;

    public function definition(): array
    {
        return [
            'name'       => $this->faker->unique()->state(),
            'country_id' => Country::factory(),
        ];
    }
}
