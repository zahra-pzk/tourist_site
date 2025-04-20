<?php
use App\Http\Controllers\TravelExperienceController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/travel-experiences', [TravelExperienceController::class, 'store']);
    Route::get('/places/{place}/travel-experiences', [TravelExperienceController::class, 'index']);
});
