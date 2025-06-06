<?php

namespace Database\Seeders;

use App\Models\Place;
use App\Models\Province;
use Illuminate\Database\Seeder;

class PlaceSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/data/raw_data_India.csv');

        if (!file_exists($path)) {
            $this->command->error("فایل places.csv پیدا نشد!");
            return;
        }

        $file = fopen($path, 'r');
        $header = fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($header, $row);

            $province = Province::where('name', 'like', "%{$data['city']}%")->first();

            if (! $province) {
                $this->command->warn("استان مرتبط برای شهر {$data['city']} پیدا نشد. جا انداخته شد.");
                continue;
            }

            Place::create([
                'name'                   => $data['attraction_name'],
                'description'            => $data['categories'] ?? $data['category'] ?? null, // خلاصه دسته‌بندی به عنوان توضیح
                'province_id'             => $province->id,
                'latitude'                => null,
                'longitude'               => null,
                'image_url'               => null,
                'google_street_view_url'  => null,
            ]);
        }

        fclose($file);

        $this->command->info('داده‌های جاذبه‌های گردشگری با موفقیت وارد شدند.');
    }
}
