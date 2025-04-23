<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\TravelExperienceController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile'] ?? fn() => response()->json(auth()->user()));

    Route::apiResource('countries',  CountryController::class);
    Route::apiResource('provinces',  ProvinceController::class);
    Route::apiResource('places',     PlaceController::class);

    Route::post('travel-experiences',                         [TravelExperienceController::class, 'store']);
    Route::get('places/{place}/travel-experiences',           [TravelExperienceController::class, 'forPlace']);
});
