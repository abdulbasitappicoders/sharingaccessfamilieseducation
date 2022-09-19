<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{User,ChatList};

class ChatListMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'type',
        'chat_list_id',
        'from',
        'to',
        'is_read',
    ];

    public function toUser()
	{   
        return $this->belongsTo(User::class,'to','id');
	}

    public function fromUser()
	{   
        return $this->belongsTo(User::class,'from','id');
	}

    public function chatList()
	{   
        return $this->belongsTo(ChatList::class,'chat_list_id','id');
	}

    public function messagesFiles()
	{
        return $this->hasMany(ChatListMessageFile::class);
	}
}
