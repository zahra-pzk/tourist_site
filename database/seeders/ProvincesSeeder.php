<?php

namespace Database\Seeders;

use App\Models\Province;
use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class ProvincesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('provinces')->truncate();

        $filePath = database_path('seeders/data/states.csv');
        if (!file_exists($filePath)) {
            $this->command->error("File not found: $filePath");
            return;
        }

        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0);

        $batchSize = 500;
        $batch = [];

        foreach ($csv->getRecords() as $record) {
            $country = Country::find($record['country_id']);
            if (!$country) continue;

            $batch[] = [
                'name'       => $record['province'] ?? $record['state_code'] ?? '',
                'country_id' => $country->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batch) >= $batchSize) {
                Province::insert($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            Province::insert($batch);
        }
    }
}
