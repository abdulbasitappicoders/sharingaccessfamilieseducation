<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{ChatListMessage};


class ChatListMessageFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_list_message_id',
        'name',
    ];

    public function messages()
	{
        return $this->belongsTo(ChatListMessage::class);
	}
}
