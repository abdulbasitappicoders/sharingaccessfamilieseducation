<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RidePayment;
use Auth;

class PaymentController extends Controller
{
    public function getPaymentHistory(){
        if(Auth::user()->role == 'driver'){
            $paymentHistory = RidePayment::where('driver_id',Auth::user()->id)->with('driver','rider','ride','ride.rideLocations','payment_method')->orderBy('id', 'DESC')->get();
        }else{
            $paymentHistory = RidePayment::where('rider_id',Auth::user()->id)->with('driver','rider','ride','ride.rideLocations','payment_method')->orderBy('id', 'DESC')->get();
        }
        if($paymentHistory){
            return apiresponse(true,"History found",$paymentHistory);
        }else{
            return apiresponse(false, "hostory not found");
        }
    }
}
