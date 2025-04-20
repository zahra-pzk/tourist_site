<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;

class PlaceController extends Controller
{

    public function index()
    {
        return response()->json(
            Place::with(['province.country'])
                ->withAvg('experiences as average_rating', 'rating')
                ->withCount('experiences')
                ->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:150',
            'description' => 'nullable|string',
            'province_id' => 'required|exists:provinces,id',
            'image_url'   => 'nullable|url',
        ]);

        $place = Place::create($validated);

        return response()->json([
            'message' => 'مکان دیدنی با موفقیت ثبت شد.',
            'data' => $place,
        ], 201);
    }

    public function show(Place $place)
    {
        return response()->json(
            $place->load(['province.country', 'experiences.user'])
                  ->loadAvg('experiences', 'rating')
        );
    }

    public function update(Request $request, Place $place)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:150',
            'description' => 'nullable|string',
            'province_id' => 'required|exists:provinces,id',
            'image_url'   => 'nullable|url',
        ]);

        $place->update($validated);

        return response()->json([
            'message' => 'مکان دیدنی با موفقیت ویرایش شد.',
            'data' => $place,
        ]);
    }
    public function destroy(Place $place)
    {
        $place->delete();

        return response()->json(['message' => 'مکان دیدنی حذف شد.']);
    }
}
