<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Place extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'province_id', 'image'];
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
    public function experiences(): HasMany
    {
        return $this->hasMany(TravelExperience::class);
    }

    public function averageRating(): float
    {
        return round($this->experiences()->avg('rating') ?? 0, 1);
    }
    public function ratingsCount(): int
    {
        return $this->experiences()->whereNotNull('rating')->count();
    }
}
