<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedLocations extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'longitude',
        'latitude',
        'address',
        'place_name',
    ];
}

