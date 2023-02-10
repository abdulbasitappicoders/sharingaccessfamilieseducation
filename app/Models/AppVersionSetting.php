<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppVersionSetting extends Model
{
    use HasFactory;

    protected $fillable = ['built_number', 'app_version', 'platform'];
}
