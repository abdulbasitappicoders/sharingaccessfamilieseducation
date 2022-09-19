<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{User,RideLocation,RidePayment,Review};

class Ride extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'rider_id',
        'driver_id',
        'request_time',
        'start_time',
        'end_time',
        'wait_time',
        'status',
        'driver_status',
        'ride_for',
        'type',
        'ride_type_id',
        'estimated_distance',
        'estimated_time',
        'estimated_price',
        'vehicle_type',
        'schedule_start_time',
        'payment_method_id',
    ];

    public function driver()
	{   
        return $this->belongsTo(User::class,'driver_id','id');
	}

    public function rider()
	{   
        return $this->belongsTo(User::class,'rider_id','id');
	}

    public function rideLocations()
	{   
        return $this->hasMany(RideLocation::class);
	}

    public function review()
	{   
        return $this->hasOne(Review::class);
	}

    public function ridePayment()
	{   
        return $this->hasOne(RidePayment::class);
	}

    public function rideRequestedTo()
	{   
        return $this->hasMany(RideRequestedTo::class,'id','ride_id');
	}

    
}
