<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class CountriesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('countries')->truncate();

        $csv = Reader::createFromPath(database_path('seeders/data/countries.csv'), 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv->getRecords() as $record) {
            Country::create([
                'name' => $record['country'] ?? '',
            ]);
        }
    }
}
