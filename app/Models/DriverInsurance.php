<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class DriverInsurance extends Model
{
    use HasFactory;
    protected $table = 'driver_insurances';
    protected $fillable = ['name','exp_date','number','front','back','user_id'];

    public function user()
	{   
        return $this->belongsTo(User::class);
	}
}
