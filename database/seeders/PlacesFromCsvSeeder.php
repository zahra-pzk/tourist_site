<?php

namespace Database\Seeders;

use App\Models\Place;
use App\Models\City;
use App\Models\Province;
use Illuminate\Database\Seeder;

class PlacesFromCsvSeeder extends Seeder
{
    public function run(): void
    {
        $files = [
            database_path('seeders/data/raw_data_Iran.csv'),
            database_path('seeders/data/raw_data_India.csv'),
            database_path('seeders/data/raw_data_USA.csv'),
            database_path('seeders/data/more-places.csv'),
            database_path('seeders/data/cleaned_data_Iran.India.USA.csv'),
        ];

        $batchSize = 500;
        $placesBatch = [];

        // کش کردن City ها
        $cities = City::select('id', 'name', 'province_id', 'country_id')->get();
        $cityMap = [];
        foreach ($cities as $city) {
            $key = strtolower(str_replace(' ', '', $city->name));
            $cityMap[$key] = $city;
        }

        // کش کردن Province ها
        $provinces = Province::select('id', 'name', 'country_id')->get();
        $provinceMap = [];
        foreach ($provinces as $province) {
            $key = strtolower(str_replace(' ', '', $province->name));
            $provinceMap[$key] = $province;
        }

        foreach ($files as $file) {
            if (!file_exists($file)) {
                echo "File not found: $file\n";
                continue;
            }

            $data = array_map('str_getcsv', file($file));
            $header = array_map('trim', array_shift($data));

            foreach ($data as $row) {
                $row = array_combine($header, $row);
                if (!$row) continue;

                $cityNameKey = strtolower(str_replace(' ', '', $row['city'] ?? ''));

                $provinceNameKey = strtolower(str_replace(' ', '', $row['province'] ?? ''));
                
                $matchedCity = $cityMap[$cityNameKey] ?? null;
                $matchedProvince = $provinceMap[$provinceNameKey] ?? null;

                $province_id = null;
                
                if ($matchedCity) {
                    $province_id = $matchedCity->province_id;
                } elseif ($matchedProvince) {
                    $province_id = $matchedProvince->id;
                }

                if (!$province_id) {
                    continue; 
                }

                $placesBatch[] = [
                    'name'                  => $row['attraction_name'] ?? '',
                    'description'           => $row['categories'] ?? '',
                    'latitude'              => null,
                    'longitude'             => null,
                    'province_id'           => $province_id,
                    'category'              => $row['category'] ?? '',
                    'image_url'             => null,
                    'google_street_view_url'=> null,
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ];

                if (count($placesBatch) >= $batchSize) {
                    Place::insert($placesBatch);
                    $placesBatch = [];
                }
            }
        }

        if (count($placesBatch) > 0) {
            Place::insert($placesBatch);
        }
    }
}
