<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{User,RideLocation,UserPaymentMethod};


class UserChildren extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'grade',
        'age',
        'school_name',
        'user_card_id',
        'payment_type',
        'user_id',
    ];
    public function user()
	{   
        return $this->belongsTo(User::class);
	}

    public function payment_method()
	{   
        return $this->belongsTo(UserPaymentMethod::class,'user_card_id','id');
	}

    public function rideLocation()
	{   
        return $this->hasMany(RideLocation::class,'id','user_children_id');
	}
}
