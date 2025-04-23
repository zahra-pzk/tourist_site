<?php

namespace App\Http\Controllers;

use App\Models\TravelExperience;
use Illuminate\Http\Request;

class TravelExperienceController extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'place_id'         => 'required|exists:places,id',
            'user_id'          => 'required|exists:users,id',
            'has_traveled'     => 'required|boolean',
            'travel_date'      => 'nullable|date',
            'positive_points'  => 'nullable|array',
            'negative_points'  => 'nullable|array',
            'suitable_for'     => 'nullable|array',
            'rating'           => 'nullable|integer|min:1|max:10',
            'extra_comment'    => 'nullable|string|max:1000',
        ]);
        if ($validated['has_traveled']) {
            $requiredFields = ['travel_date', 'positive_points', 'negative_points', 'suitable_for', 'rating'];
            foreach ($requiredFields as $field) {
                if (empty($validated[$field])) {
                    return response()->json([
                        'message' => "فیلد {$field} برای کاربران دارای تجربه سفر الزامی است."
                    ], 422);
                }
            }
        }
        $experience = TravelExperience::create([
            'place_id'        => $request->place_id,
            'user_id'         => auth()->id(),
            'has_traveled'     => $validated['has_traveled'],
            'travel_date'      => $validated['travel_date'] ?? null,
            'positive_points'  => $validated['positive_points'] ?? [],
            'negative_points'  => $validated['negative_points'] ?? [],
            'suitable_for'     => $validated['suitable_for'] ?? [],
            'rating'           => $validated['rating'] ?? null,
            'extra_comment'    => $validated['extra_comment'] ?? null,
        ]);

        return response()->json([
            'message' => 'نظر شما با موفقیت ثبت شد.',
            'data'    => $experience,
        ], 201);
    }

    public function forPlace($place_id)
    {
        $experiences = TravelExperience::where('place_id', $place_id)
            ->with('user:id,name')
            ->get();

        $average = $experiences->where('has_traveled', true)->avg('rating');
        $total = $experiences->count();

        return response()->json([
            'average_rating' => $average ? round($average, 1) : null,
            'total_reviews'  => $total,
            'experiences'    => $experiences,
        ]);
    }
}
