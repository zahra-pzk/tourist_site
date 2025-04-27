<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\Place;
use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlacesFullSeeder extends Seeder
{
    public function run(): void
    {
        $files = [
            database_path('seeders/data/cleaned_data_Iran.India.USA.csv'),
            database_path('seeders/data/more-places.csv'),
            database_path('seeders/data/raw_data_India.csv'),
            database_path('seeders/data/raw_data_Iran.csv'),
            database_path('seeders/data/raw_data_USA.csv'),
        ];

        // کش کردن همه شهرها در آرایه
        $cities = City::select('id', 'name', 'province_id', 'country_id')->get()->keyBy('name')->toArray();

        $batchSize = 500; // اندازه هر سری برای اینسرت
        $places = [];

        foreach ($files as $file) {
            if (!file_exists($file)) {
                echo "File not found: $file\n";
                continue;
            }

            $handle = fopen($file, 'r');
            if (!$handle) {
                echo "Cannot open file: $file\n";
                continue;
            }

            $header = null;
            while (($row = fgetcsv($handle)) !== false) {
                if (!$header) {
                    $header = array_map('trim', $row);
                    continue;
                }

                $data = array_combine($header, $row);

                $cityName = trim($data['city'] ?? $data['City'] ?? '');
                $provinceName = trim($data['state'] ?? $data['province'] ?? '');
                $countryName = trim($data['country'] ?? '');

                if (!$cityName && !$provinceName) {
                    continue;
                }

                $city = $cities[$cityName] ?? $cities[$provinceName] ?? null;

                if (!$city) {
                    continue;
                }

                $places[] = [
                    'name'         => $data['attraction_name'] ?? $data['name'] ?? 'مکان بدون نام',
                    'description'  => $data['categories'] ?? $data['category'] ?? null,
                    'latitude'     => isset($data['latitude']) ? (float) $data['latitude'] : null,
                    'longitude'    => isset($data['longitude']) ? (float) $data['longitude'] : null,
                    'province_id'  => $city['province_id'],
                    'city_id'      => $city['id'],
                    'country_id'   => $city['country_id'],
                    'category'     => $data['category'] ?? 'عمومی',
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];

                // هر batchSize رکورد که جمع شد insert کن
                if (count($places) >= $batchSize) {
                    Place::insert($places);
                    $places = [];
                }
            }

            fclose($handle);
        }

        // اگر رکوردی باقی مونده بود
        if (!empty($places)) {
            Place::insert($places);
        }

        $this->cleanup();
    }

    private function cleanup(): void
    {
        City::whereDoesntHave('places')->delete();
        Province::whereDoesntHave('cities')->delete();
        Country::whereDoesntHave('provinces')->delete();
    }
}
