<?php

namespace Database\Factories;

use App\Models\Place;
use App\Models\User;
use App\Models\TravelExperience;
use Illuminate\Database\Eloquent\Factories\Factory;

class TravelExperienceFactory extends Factory
{
    protected $model = TravelExperience::class;

    public function definition(): array
    {
        $hasTraveled = $this->faker->boolean(80);

        return [
            'user_id'         => User::factory(),
            'place_id'        => Place::factory(),
            'has_traveled'    => $hasTraveled,
            'travel_time'     => $hasTraveled ? $this->faker->dateTimeBetween('-2 years', 'now') : null,
            'liked_points'    => $hasTraveled ? $this->faker->randomElements([
                'منظره زیبا', 'نظافت محیط', 'دسترسی آسان', 'راهنمای حرفه‌ای', 'آب‌وهوای خوب'
            ], rand(1, 3)) : [],
            'disliked_points' => $hasTraveled ? $this->faker->randomElements([
                'شلوغی زیاد', 'هزینه بالا', 'نبود امکانات', 'دور بودن', 'پارکینگ ضعیف'
            ], rand(0, 2)) : [],
            'suitable_for'    => $hasTraveled ? $this->faker->randomElements([
                'خانواده', 'افراد ماجراجو', 'کودکان', 'افراد مسن', 'زوج‌ها'
            ], rand(1, 2)) : [],
            'rating'          => $hasTraveled ? rand(5, 10) : null,
            'extra_notes'     => $this->faker->optional()->sentence(8),
        ];
    }
}
