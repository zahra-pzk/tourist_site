<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    public function travelExperiences()
    {
        return $this->hasMany(TravelExperience::class);
    }
    
    public function averageRating()
    {
        return $this->travelExperiences()
            ->whereNotNull('rating')
            ->avg('rating');
    }
    }
