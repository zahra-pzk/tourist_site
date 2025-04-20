<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\Place;

class PlaceSeeder extends Seeder
{
    public function run(): void
    {
        $placeholder = 'https://via.placeholder.com/400x300';

        foreach (Province::all() as $province) {
            Place::firstOrCreate([
                'name'        => $province->name . ' Landmark',
                'province_id' => $province->id,
            ], [
                'description' => "A beautiful spot in {$province->name}.",
                'image'       => $placeholder,
                'google_street_view_url' => null,
            ]);
        }
    }
}
