<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Ride,UserPaymentMethod};


class RidePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ride_id',
        'type',
        'base_amount',
        'total_amount',
        'user_card_id',
        'driver_id',
        'rider_id',
        'driver_ammount',
        'rider_amount',
        'commission',
    ];

    public function ride()
	{   
        return $this->belongsTo(Ride::class);
	}

    public function driver()
	{   
        return $this->belongsTo(User::class,'driver_id','id');
	}

    public function rider()
	{   
        return $this->belongsTo(User::class,'rider_id','id');
	}

    public function payment_method()
	{   
        return $this->belongsTo(UserPaymentMethod::class,'user_card_id');
	}
}
