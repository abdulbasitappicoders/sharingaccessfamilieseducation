<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Hash;
use App\Models\{User,Ride,RidePayment};
use Auth;
use Exception;


class AuthController extends Controller
{

    public $stripe = "";

    public function __construct()
    {
        $this->stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
    }

    public function test2(){
        return true;
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'email'     =>  'required|unique:users,email',
            'password'  =>  'min:8',
        ]);
        if($validator->fails()){
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }
        try {
            $stripeCustomer = $this->stripe->customers->create([
                'email' => $request->email,
                'name' => isset($request->username)?$request->username:$request->fist_name.' '.$request->last_name,
            ]);
            $data = $request->except(['password']);
            $data['stripe_customer_id'] = $stripeCustomer->id;
            $data['password'] = Hash::make($request->password);
            if ($request->hasFile('image')) {
                $res = files_upload($request->image, 'profile');
                $data['image'] = $res;
            }
            $user = User::create($data);
            if($user){
                if($user->role == 'driver'){
                    $rides = Ride::where('driver_id',$user->id)
                    ->where('status','completed')->count();
                    $total_earnings = RidePayment::where('driver_id', $user->id)->sum('total_amount');
                }else{
                    $rides = Ride::where('rider_id',$user->id)
                    ->where('status','completed')->count();
                    $total_earnings = RidePayment::where('rider_id', $user->id)->sum('total_amount');
                }
                $user->total_earnings = $total_earnings;
                $user->total_rides = $rides;
                $data = [
                    'token' => $user->createToken('customer-Token')->accessToken,
                    'user' => $user
                ];
                return apiresponse(true, 'Login Success', $data);
            }
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email|exists:users,email',
            'password'  => 'required|min:8'
        ]);
        if ($validator->fails()) return apiresponse(false, implode("\n", $validator->errors()->all()));
        try {
            $user = User::where('email', $request->email)->with('licence','vehicle','childrens','UserPaymentMethods','childrens.payment_method','userAvailability')->first();
            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    if ($request->has('device_id') and !empty($request->device_id)) {
                        User::find($user->id)->update(['device_id' => $request->device_id]);
                        $user = User::where('id',$user->id)->with('licence','vehicle','childrens','childrens.payment_method','userAvailability')->first();
                    }
                    if($user->role == 'rider'){
                        $rides = Ride::where('rider_id',$user->id)
                        ->where('status','completed')
                        ->with('driver','rider','rideLocations','ridePayment','review')->count();
                    }else{
                        $rides = Ride::where('driver_id',$user->id)
                        ->where('status','completed')
                        ->with('driver','rider','rideLocations','ridePayment','review')->count();
                    }
                    $user->login_count = $user->login_count+1;
                    $user->save();
                    if($user->role == 'driver'){
                        $total_earnings = RidePayment::where('driver_id', $user->id)->sum('total_amount');
                    }else{
                        $total_earnings = RidePayment::where('rider_id', $user->id)->sum('total_amount');
                    }
                    $user->total_earnings = $total_earnings;
                    $user->total_rides = $rides;
                    $data = [
                        'token' => $user->createToken('customer-Token')->accessToken,
                        'user' => $user,
                        'csrf_token' =>  csrf_field() 
                    ];
                    return apiresponse(true, 'Login Success', $data);
                } else {
                    return apiresponse(false, 'Invalid Credentials');
                }
            } else {
                $user = User::where('email',$request->email)->withTrashed()->first();
                if($user){
                    return apiresponse(false, 'Your account has been deleted!');
                }else{
                    return apiresponse(false, 'User not Found!');
                }
            }
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function socialLogin(Request $request){

        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required',
        ]);
        if ($validator->fails()) return apiresponse(false, implode("\n", $validator->errors()->all()));
        try {
            $user = User::where('email',$request->email)->first();
            if($user){
                if ($request->has('device_id') and !empty($request->device_id)) {
                    User::find($user->id)->update(['device_id' => $request->device_id]);
                    $user = User::find($user->id);
                }
                $data = [
                    'token' => $user->createToken('customer-Token')->accessToken,
                    'user' => $user
                ];
            }else{
                $stripe = new StripeClient(env("STRIPE_SECRET_KEY"));
                $stripeCustomer = $stripe->customers->create([
                    'email' => $request->email,
                    'name' => $request->username,
                ]);
                $data = $request->all();
                $data['stripe_customer_id'] = $stripeCustomer->id;
                $data['role'] = 'rider';
                $user = User::create($data);
                $user = User::find($user->id);
                $data = [
                    'token' => $user->createToken('customer-Token')->accessToken,
                    'user' => $user
                ];
            }
            return apiresponse(true, 'Social Login Success', $data);
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function sendForgotPasswordEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required'
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        } 

        try {   
            $user = User::where('email', $request->email)->first();
            $code = substr(md5(rand()), 0, 4);
            if (!$user) {
                return apiresponse(false, 'Email does not exist');
            } else {
                $user->confirmation_code = $code;
                if($user->save()) {
                    // Mail::to($user->email)->send(new ForgotPassword($user));
                    return apiresponse(true, 'Password reset link sent to your email', $user);
                } else {
                    return apiresponse(false, 'Some error occurred. Please try again');
                }
            }
        } catch(Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function verifyForgotPin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'code'  => 'required'
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        } 

        $user = User::where('email', $request->email)->first();
        if($request->code == $user->confirmation_code){
            return apiresponse(true, 'Confirmation code has been matched successfully', $user);
        }

        return apiresponse(false, 'Code missmatch');
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }
        try {
            $pass = Hash::make($request->password);
            User::where('email', $request->email)->update(['password' => $pass]);
            return apiresponse(true, "password updated successfully");
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function test(Request $request){
        // if ($request->hasFile('image')) {
        //     $res = files_upload($request->image, 'profile');
        //     if($res){
        //         return "done";
        //     }
            
        // }
        return "3" + '0um';
    }
}
