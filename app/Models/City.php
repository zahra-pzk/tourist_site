<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
    
    public function places()
    {
        return $this->hasMany(Place::class);
    }
}
