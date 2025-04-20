<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CountrySeeder::class,
            ProvinceSeeder::class,
            PlaceSeeder::class,
            TravelExperienceSeeder::class,
        ]);
    }
}
