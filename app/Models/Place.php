<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Place
 *
 * @property int                             $id
 * @property string                          $name
 * @property string|null                     $description
 * @property int                             $province_id
 * @property string|null                     $image_url
 * @property string|null                     $google_street_view_url
 * @property-read \App\Models\Province       $province
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TravelExperience[] $experiences
 * @property float                           $average_rating
 * @property int                             $ratings_count
 * @property string                          $street_view_link
 */
class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'province_id',
        'image_url',
        'google_street_view_url',
    ];
    protected $appends = [
        'average_rating',
        'ratings_count',
        'street_view_link',
    ];

    /**
     * Province relationship
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Travel experiences for this place
     */
    public function experiences(): HasMany
    {
        return $this->hasMany(TravelExperience::class);
    }

    /**
     * Average rating (1–10), rounded to 1 decimal place.
     */
    public function getAverageRatingAttribute(): float
    {
        $avg = $this->experiences()->whereNotNull('rating')->avg('rating');
        return round($avg ?? 0, 1);
    }

    /**
     * Total number of non-null ratings.
     */
    public function getRatingsCountAttribute(): int
    {
        return $this->experiences()->whereNotNull('rating')->count();
    }

    /**
     * Resolve a Street View URL for iframe.
     * If 'google_street_view_url' is set, returns it.
     * Otherwise, falls back to dynamic generation if latitude/longitude exist.
     */
    public function getStreetViewLinkAttribute(): string
    {
        if (! empty($this->google_street_view_url)) {
            return $this->google_street_view_url;
        }

        // Optional: if you've added latitude & longitude columns
        if (isset($this->latitude, $this->longitude) &&
            is_numeric($this->latitude) &&
            is_numeric($this->longitude)
        ) {
            $key = config('services.google.maps_api_key');
            return "https://www.google.com/maps/embed/v1/streetview"
                 . "?location={$this->latitude},{$this->longitude}&key={$key}";
        }

        return '';
    }
}
