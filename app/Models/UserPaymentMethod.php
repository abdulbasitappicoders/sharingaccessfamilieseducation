<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{User,RidePayment};


class UserPaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'stripe_source_id',
        'end_number',
        'exp_date',
        'default',
        'user_id',
        'title',
        'number',
        'type',
        'routing_number',
    ];

    public function user()
	{   
        return $this->belongsTo(User::class);
	}

    public function childrenPaymentMethods()
	{   
        return $this->hasMany(UserChildren::class,'id','user_card_id');
	}

    public function ridePaymentMethods()
	{   
        return $this->hasMany(RidePayment::class,'user_card_id','id');
	}

    

    
}
