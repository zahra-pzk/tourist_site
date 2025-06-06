<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MergeCsvFiles extends Command
{
    protected $signature = 'merge:csv-files 
                            {--input= : مسیر پوشه‌ای که فایل‌های CSV داخلش هست} 
                            {--output=merged_output.csv : نام فایل خروجی}';

    protected $description = 'ادغام چند فایل CSV در یک فایل خروجی';

    public function handle()
    {
        ini_set('memory_limit', '1024M');
        $inputDir = $this->option('input');
        $outputFile = $this->option('output');

        if (!$inputDir || !File::isDirectory($inputDir)) {
            $this->error('مسیر پوشه ورودی معتبر نیست.');
            return;
        }

        $csvFiles = File::files($inputDir);
        $mergedData = [];
        $header = null;

        foreach ($csvFiles as $file) {
            if ($file->getExtension() !== 'csv') continue;

            $lines = file($file->getRealPath(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            if (!$lines) continue;

            $currentHeader = str_getcsv(array_shift($lines));

            if (!$header) {
                $header = $currentHeader;
                $mergedData[] = $header;
            }

            if ($currentHeader !== $header) {
                $this->warn("هشدار: فرمت ستون‌های فایل {$file->getFilename()} با بقیه یکی نیست و رد شد.");
                continue;
            }

            foreach ($lines as $line) {
                $mergedData[] = str_getcsv($line);
            }
        }

        $handle = fopen($outputFile, 'w');
        foreach ($mergedData as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);

        $this->info("✅ فایل‌ها با موفقیت در '{$outputFile}' ادغام شدند.");
    }
}
