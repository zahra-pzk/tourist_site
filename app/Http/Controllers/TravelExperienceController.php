<?php

namespace App\Http\Controllers;

use App\Models\TravelExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TravelExperienceController extends Controller
{
    public function index($placeId)
    {
        $experiences = TravelExperience::where('place_id', $placeId)->get();

        $withRating = $experiences->whereNotNull('rating');
        $average = $withRating->avg('rating');
        $count = $withRating->count();

        return response()->json([
            'average_rating' => round($average, 2),
            'rating_count' => $count,
            'experiences' => $experiences
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'place_id' => 'required|exists:places,id',
            'has_traveled' => 'required|boolean',
            'travel_time' => 'nullable|string',
            'liked_points' => 'nullable|array',
            'disliked_points' => 'nullable|array',
            'suitable_for' => 'nullable|array',
            'rating' => 'nullable|integer|min:1|max:10',
            'extra_notes' => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();

        $experience = TravelExperience::create($data);

        return response()->json($experience, 201);
    }
}
