<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{User,Ride};

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'review',
        'rating',
        'to',
        'from',
        'ride_id',
    ];

    public function toUser()
	{   
        return $this->belongsTo(User::class,'to','id');
	}

    public function fromUser()
	{   
        return $this->belongsTo(User::class,'from','id');
	}

    public function ride()
	{   
        return $this->belongsTo(Ride::class);
	}
}
