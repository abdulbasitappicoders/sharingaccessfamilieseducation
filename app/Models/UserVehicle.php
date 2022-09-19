<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{User};

class UserVehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_brand',
        'model',
        'year',
        'color',
        'license_plate',
        'booking_type',
        'user_id',
    ];

    public function user()
	{
        return $this->belongsTo(User::class);
	}
}
