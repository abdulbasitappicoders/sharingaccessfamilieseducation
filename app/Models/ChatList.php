<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{User,ChatListMessage};

class ChatList extends Model
{
    use HasFactory;

    protected $fillable = [
        'to',
        'from'
    ];

    public function messages()
	{
        return $this->hasMany(ChatListMessage::class);
	}

    public function toUser()
	{   
        return $this->belongsTo(User::class,'to','id');
	}

    public function fromUser()
	{   
        return $this->belongsTo(User::class,'from','id');
	}
}
