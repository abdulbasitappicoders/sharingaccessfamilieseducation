<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\{UserChildren,UserPaymentMethod,UserVehicle,UserLicense,UserAvailable,ChatList,
    ChatListMessage,ContactUs,Review,Notification,Ride,RideRequestedTo};


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const driver = 'driver'; 
    public const rider = 'rider'; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'gender',
        'stripe_customer_id',
        'device_id',
        'image',
        'is_notify',
        'vehicle_type',
        'confirmation_code',
        'longitude',
        'latitude',
        'status',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getNameAttribute(){
        return $this['first_name']." ".$this['last_name'];
    }

    public function childrens()
	{
        return $this->hasMany(UserChildren::class);
	}

    public function driverPayment()
	{
        return $this->hasMany(RidePayment::class,'id','driver_id');
	}

    public function riderPayment()
	{
        return $this->hasMany(RidePayment::class,'id','rider_id');
	}

    public function chatLists()
	{
        return $this->hasMany(ChatList::class);
	}

    public function messages()
	{
        return $this->hasMany(ChatListMessage::class);
	}

    public function licence()
	{
        return $this->hasOne(UserLicense::class);
	}

    public function vehicle()
	{
        return $this->hasOne(UserVehicle::class);
	}

    public function contactUs()
	{
        return $this->hasMany(ContactUs::class);
	}

    public function getReview()
	{   
        return $this->hasMany(Review::class,'to','id');
	}
   

    public function toReview()
	{   
        return $this->hasMany(Review::class,'from','id');
	}

    public function Sendernotifications()
	{   
        return $this->hasMany(Notification::class,'id','sender_id');
	}

    public function recievernotifications()
	{   
        return $this->hasMany(Notification::class,'id','reciever_id');
	}

    public function userAvailability()
	{   
        return $this->hasMany(UserAvailable::class);
	}

    public function UserPaymentMethods()
	{   
        return $this->hasMany(UserPaymentMethod::class);
	}

    public function driverRide()
	{   
        return $this->hasMany(Ride::class,'id','driver_id');
	}

    public function riderRide()
	{   
        return $this->hasMany(Ride::class,'id','rider_id');
	}

    public function rideRequestedTo()
	{   
        return $this->hasMany(RideRequestedTo::class,'id','driver_id');
	}
}
