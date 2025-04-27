<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Province extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'country_id'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
    public function places(): HasMany
    {
        return $this->hasMany(Place::class);
    }
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}

