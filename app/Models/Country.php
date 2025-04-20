<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $fillable = ['name'];
    public function provinces(): HasMany
    {
        return $this->hasMany(Province::class);
    }
    public function places()
    {
        return $this->hasManyThrough(Place::class, Province::class);
    }
}
