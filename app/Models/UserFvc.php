<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFvc extends Model
{
    use HasFactory;

    protected $fillable = [
        'fvc_number',
        'name',
        'image',
        'expiry',
        'user_id',
    ];
}
