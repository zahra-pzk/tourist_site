<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Province;

class ProvinceSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            'Iran'   => ['Tehran', 'Isfahan', 'Fars'],
            'France' => ['Île‑de‑France', 'Provence', 'Normandy'],
            'Spain'  => ['Andalusia', 'Catalonia', 'Madrid'],
        ];

        foreach ($map as $countryName => $provinces) {
            $country = Country::where('name', $countryName)->first();

            foreach ($provinces as $provinceName) {
                Province::firstOrCreate([
                    'name'       => $provinceName,
                    'country_id' => $country->id,
                ]);
            }
        }
    }
}
