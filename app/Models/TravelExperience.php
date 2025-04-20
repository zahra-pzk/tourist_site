<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'has_traveled' => 'boolean',
        'positive_points' => 'array',
        'negative_points' => 'array',
        'suitable_for' => 'array',
    ];
    class TravelExperience extends Model
    {
    
    
    
        public function user(): BelongsTo
        {
            return $this->belongsTo(User::class);
        }
        public function place(): BelongsTo
        {
            return $this->belongsTo(Place::class);
        }
    }
    

