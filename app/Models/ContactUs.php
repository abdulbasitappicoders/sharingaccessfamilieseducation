<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ContactUs extends Model
{
    use HasFactory;

    protected $fillable = ['type','message','user_id','ride_id'];

    public function user()
	{
        return $this->belongsTo(User::class);
	}
}
