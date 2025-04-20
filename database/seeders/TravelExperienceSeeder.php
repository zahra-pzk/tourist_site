<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TravelExperience;
use App\Models\User;
use App\Models\Place;

class TravelExperienceSeeder extends Seeder
{
    public function run(): void
    {
        $users  = User::all();
        $places = Place::all();

        foreach ($places as $place) {
            foreach ($users->random(rand(3, 6)) as $user) {
                $hasTraveled = fake()->boolean(70);

                TravelExperience::create([
                    'user_id'         => $user->id,
                    'place_id'        => $place->id,
                    'has_traveled'    => $hasTraveled,
                    'travel_time'     => $hasTraveled
                                            ? fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d')
                                            : null,
                    'liked_points'    => $hasTraveled
                                            ? fake()->randomElements([
                                                'منظره زیبا',
                                                'نظافت محیط',
                                                'دسترسی آسان',
                                                'راهنمای حرفه‌ای',
                                                'آب‌وهوای خوب'
                                              ], rand(1, 3))
                                            : [],
                    'negative_points' => $hasTraveled
                                            ? fake()->randomElements([
                                                'شلوغی بیش از حد',
                                                'هزینه بالا',
                                                'نبود امکانات',
                                                'دور بودن',
                                                'پارکینگ ضعیف'
                                              ], rand(0, 2))
                                            : [],
                    'suitable_for'    => $hasTraveled
                                            ? fake()->randomElements([
                                                'خانواده',
                                                'زوج‌ها',
                                                'افراد ماجراجو',
                                                'افراد سالخورده',
                                                'کودکان'
                                              ], rand(1, 2))
                                            : [],
                    'rating'          => $hasTraveled ? rand(5, 10) : null,
                    'extra_notes'     => fake()->realTextBetween(40, 150),
                ]);
            }
        }
    }
}
