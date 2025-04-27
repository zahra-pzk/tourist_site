<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Province;
use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class CitiesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cities')->truncate();

        $filePath = database_path('seeders/data/cities.csv');
        if (!file_exists($filePath)) {
            $this->command->error("File not found: $filePath");
            return;
        }

        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0);

        $batchSize = 500;
        $batch = [];

        foreach ($csv->getRecords() as $record) {
            $province = Province::find($record['province_id']);
            $country = Country::find($record['country_id']);
            if (!$province || !$country) continue;

            $batch[] = [
                'name'        => $record['city'] ?? '',
                'province_id' => $province->id,
                'country_id'  => $country->id,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];

            if (count($batch) >= $batchSize) {
                City::insert($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            City::insert($batch);
        }
    }
}
