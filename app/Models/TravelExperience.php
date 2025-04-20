<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'place_id',
        'has_traveled',
        'travel_time',
        'liked_points',
        'disliked_points',
        'suitable_for',
        'rating',
        'extra_notes',
    ];

    protected $casts = [
        'liked_points' => 'array',
        'disliked_points' => 'array',
        'suitable_for' => 'array',
        'has_traveled' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
