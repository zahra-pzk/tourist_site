<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlaceController extends Controller
{

    public function index(): JsonResponse
    {
        $places = Place::with(['province.country'])
            ->withAvg('experiences as average_rating', 'rating')
            ->withCount('experiences as ratings_count')
            ->get();

        return response()->json([
            'data' => $places,
        ], 200);
    }


    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'                     => 'required|string|max:150',
            'description'              => 'nullable|string',
            'province_id'              => 'required|exists:provinces,id',
            'image_url'                => 'nullable|url',
            'google_street_view_url'   => 'nullable|url',
        ]);

        $place = Place::create($validated);

        return response()->json([
            'message' => 'مکان دیدنی با موفقیت ثبت شد.',
            'data'    => $place,
        ], 201);
    }

    public function show(Place $place): JsonResponse
    {
        $place->load(['province.country', 'experiences.user'])
              ->loadAvg('experiences as average_rating', 'rating')
              ->loadCount('experiences as ratings_count');

        return response()->json([
            'data' => $place,
            'meta' => [
                'average_rating'   => $place->average_rating,
                'ratings_count'    => $place->ratings_count,
                'street_view_url'  => $place->street_view_link,
            ],
        ], 200);
    }


    public function update(Request $request, Place $place): JsonResponse
    {
        $validated = $request->validate([
            'name'                   => 'required|string|max:150',
            'description'            => 'nullable|string',
            'province_id'            => 'required|exists:provinces,id',
            'image_url'              => 'nullable|url',
            'google_street_view_url' => 'nullable|url',
        ]);

        $place->update($validated);

        return response()->json([
            'message' => 'مکان دیدنی با موفقیت ویرایش شد.',
            'data'    => $place,
        ], 200);
    }


    public function destroy(Place $place): JsonResponse
    {
        $place->delete();

        return response()->json([
            'message' => 'مکان دیدنی حذف شد.',
        ], 200);
    }

    public function streetView(Place $place): JsonResponse
    {
        $url = $place->street_view_link;

        if (empty($url)) {
            return response()->json([
                'message' => 'مختصات یا URL نمای خیابانی ثبت نشده است.'
            ], 400);
        }

        return response()->json([
            'street_view_url' => $url,
        ], 200);
    }
    public function byCity(\App\Models\City $city)
{
    $places = $city->places()->select('id', 'name')->get();
    return response()->json($places);
}

}