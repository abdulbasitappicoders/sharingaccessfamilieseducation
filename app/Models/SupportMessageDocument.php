<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportMessageDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'support_message_id',
        'name',
    ];
}
