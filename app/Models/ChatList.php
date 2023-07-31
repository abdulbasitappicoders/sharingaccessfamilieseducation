<?php

namespace App\Models;

use App\Models\ChatListMessage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatList extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'to',
        'from',
        'faq_category_id',
        'updated_at',
    ];

    public function messages()
    {
        return $this->hasMany(ChatListMessage::class);
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to', 'id');
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from', 'id');
    }

    public function category()
    {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id', 'id');
    }

    public function supportMessages()
    {
        return $this->hasMany(SupportMessage::class);
    }
}
