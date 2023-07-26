<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactUs extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = ['type','message','user_id','ride_id'];

    public function user()
	{
        return $this->belongsTo(User::class);
	}
}
