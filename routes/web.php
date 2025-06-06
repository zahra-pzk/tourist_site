<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

// اگر بخواهیم index.html از resources/frontend لود شود:
Route::get('/', function () {
    return response()->file(public_path('\frontend\index.html'));
});

// برای سرو فایل‌های CSS و JS داخل resources/frontend
Route::get('\static\{filename}', function ($filename) {
    $fullPath = resource_path("frontend\{$filename}");
    if (! File::exists($fullPath)) {
        abort(404);
    }
    return Response::file($fullPath, [
        'Content-Type' => File::mimeType($fullPath),
    ]);
});
Route::view('/test-review', 'test-review');
