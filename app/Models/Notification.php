<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'is_read',
        'sender_id',
        'reciever_id',
    ];

    public function sender()
	{   
        return $this->belongsTo(User::class,'sender_id','id');
	}

    public function reciever()
	{   
        return $this->belongsTo(User::class,'reciever_id','id');
	}


}
