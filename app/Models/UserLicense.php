<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{User};


class UserLicense extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_on_card',
        'license_plate_number',
        'expiry',
        'card_front',
        'card_back',
        'user_id',
    ];

    public function user()
	{
        return $this->belongsTo(User::class);
	}
}
