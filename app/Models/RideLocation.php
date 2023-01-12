<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Ride,UserChildren};

class RideLocation extends Model
{
    use HasFactory;

    protected $with = ['children'];



    protected $table = 'ride_locations';

    protected $fillable = [
        'longitude',
        'latitude',
        'ride_order',
        'ride_id',
        'user_children_id',
    ];

    public function ride()
	{   
        return $this->belongsTo(Ride::class);
	}

    public function children()
	{   
        return $this->belongsTo(UserChildren::class,'user_children_id','id');
	}
}
