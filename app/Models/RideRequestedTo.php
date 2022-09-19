<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{User,ride};

class RideRequestedTo extends Model
{
    use HasFactory;

    protected $fillable = ['driver_id','ride_id'];

    public function driver()
	{   
        return $this->belongsTo(User::class,'driver_id','id');
	}

    public function rides()
	{   
        return $this->belongsTo(Ride::class,'ride_id','id');
	}
}
