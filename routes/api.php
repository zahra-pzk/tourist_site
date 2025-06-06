<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\TravelExperienceController;

// فقط دیتا می‌دیم. هیچ ربطی به صفحات نداره.
Route::get('/countries', [CountryController::class, 'index']);
Route::get('/provinces', [ProvinceController::class, 'index']);
Route::get('/places', [PlaceController::class, 'index']);
Route::get('/places/{place}', [PlaceController::class, 'show']);
Route::get('/places/{place}/street-view', function (\App\Models\Place $place) {
    return response()->json(['street_view_url' => $place->google_street_view_url]);
});
Route::post('/travel-experiences', [TravelExperienceController::class, 'store']);
Route::get('/places/{place}/travel-experiences', [TravelExperienceController::class, 'forPlace']);
Route::get('/countries/{country}/cities', [CountryController::class, 'cities']);
Route::get('/cities/{city}/places', [PlaceController::class, 'byCity']);
