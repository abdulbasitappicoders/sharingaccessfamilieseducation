<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\{User,Ride,RidePayment, UserAccount};
use App\Services\StripeService;
use Auth;
use Mail;
use Exception;
use Stripe\Account;
use Stripe\Stripe;


class AuthController extends Controller
{
    public function test2(){
        return true;
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'email'     =>  'required|email',
            'password'  =>  'min:8',
        ]);
        if($validator->fails()){
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }
        try {
            $checkUser = User::where('email', $request->email)->where('role', $request->role)->first();
            if($checkUser) {
                return apiresponse(false, __($request->email.' with '.$request->role . ' role already exists'));
            }
            $code = rand(10000,99999);
            $stripeService = new StripeService();
            $data = $request->except(['password']);
            $data['stripe_customer_id'] = $stripeService->createCustomer($request)->id;
            $data['password'] = Hash::make($request->password);
            $data['confirmation_code'] = $code;
            if ($request->hasFile('image')) {
                $res = files_upload($request->image, 'profile');
                $data['image'] = $res;
            }
            $user = User::create($data);
            $authData = ['code' => $code,'user_id' => $user->id];
            if($user){
                \Mail::to($request->email)->send(new \App\Mail\VerificationEmail($authData));
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
                $createStripeAccount = $stripeService->createOnBoarding($user, '1995-01-01');
                $userStripeAccount = new UserAccount();
                $userStripeAccount->user_id = $user->id;
                $userStripeAccount->stripe_account_id = $createStripeAccount->id;
                $userStripeAccount->save();
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
            'password'  => 'required|min:8',
            'role'      => 'required'
        ]);
        if ($validator->fails()) return apiresponse(false, implode("\n", $validator->errors()->all()));
        try {
            $user = User::where('email', $request->email)->where('role', $request->role)->with('licence','vehicle','childrens','UserPaymentMethods','childrens.payment_method','userAvailability','UserFvc')->first();
            if(!$user) {
                return apiresponse(false, 'User not found', ['data' => null]);
            }
            $stripeService = new StripeService();
            $userAccount = UserAccount::where('user_id', $user->id)->first();
            if(!$userAccount) {
                $createStripeAccount = $stripeService->createOnBoarding($user, '1995-01-01');
                $userStripeAccount = new UserAccount();
                $userStripeAccount->user_id = $user->id;
                $userStripeAccount->stripe_account_id = $createStripeAccount->id;
                $userStripeAccount->save();
            }
            if ($user) {
                if($user->is_verified == 1){
                    if (Hash::check($request->password, $user->password)) {
                        if ($request->has('device_id') and !empty($request->device_id)) {
                            User::find($user->id)->update(['device_id' => $request->device_id]);
                            $user = User::where('id',$user->id)->with('licence','vehicle','childrens','childrens.payment_method','userAvailability','UserFvc')->first();
                        }
                        if($user->role == 'rider'){
                            $rides = Ride::where('rider_id',$user->id)
                            ->where('status','completed')
                            ->with('driver','rider','rideLocations','ridePayment','review')->count();
                        }else{
                            $rides = Ride::where('driver_id',$user->id)
                            ->where('status','completed')
                            ->with('driver','rider','rideLocations','ridePayment','review', 'UserFvc')->count();
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
                        
                        $onboarding_url = $stripeService->getConnectUrl($user->stripeAccount->stripe_account_id, $user->id);
                        $data = [
                            'token'     => $user->createToken('customer-Token')->accessToken,
                            'user'      => $user,
                            'csrf_token' =>  csrf_field() ,
                            'onboarding_url' =>  $onboarding_url ,
                        ];
                        return apiresponse(true, 'Login Success', $data);
                    } else {
                        return apiresponse(false, 'Invalid Credentials');
                    }
                }else{
                    return apiresponse(false, 'An email has been sent to your registered email address. Please click on verify to verify your account.');
                }
            } else {
                $user = User::where('email',$request->email)->where('role', $request->role)->withTrashed()->first();
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

    public function connectReAuth($account_no)
    {
        $stripeService = new StripeService();

        $userAccount = UserAccount::where('stripe_account_id', $account_no)->first();
        $user = User::where('id', $userAccount->user_id)->first();
        try {
            $account = $stripeService->getConnectUrl($userAccount->stripe_account_id);
            $user->update(['onboarding_url' => $account->url]);

//            return apiresponse(true, 'Link has been generated successfully', $user);
            return view('connect-success', ['message' => 'Link has been generated successfully']);
        } catch (Exception $exception) {
//            return apiresponse(false, $exception->getMessage());
            return view('connect-failed', ['message' => $exception->getMessage()]);
        }
    }

    public function connectReturn($id)
    {
        $userAccount = UserAccount::where('user_id', $id)->first();
        $user = User::where('id', $userAccount->user_id)->first();
        try {
            Stripe::setApiKey(config('payment.STRIPE_SECRET_KEY'));
            $acc = Account::retrieve(
                $userAccount->stripe_account_id,
                []
            );
            if (!$acc->details_submitted) {
                return view('connect-failed', ['message' => "Unable to complete connect account"]);
//                return apiresponse(false, "Unable to complete connect account",);
            }
            $user->update(['is_broad' => '1']);
            return view('connect-success', ['message' => 'Connect account has been verified successfully']);
        //    return apiresponse(true, 'Connect account has been verified successfully', $user);
        } catch (Exception $exception) {
            dd($exception->getMessage());
            return view('connect-failed', ['message' => $exception->getMessage()]);
//            return apiresponse(false, $exception->getMessage());
        }
    }

    public function socialLogin(Request $request){

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required',
            'email' => 'required',
            'role' => 'required',
        ]);
        if ($validator->fails()) return apiresponse(false, implode("\n", $validator->errors()->all()));
        try {
            $user = User::where('email',$request->email)->where('role', $request->role)->first();
            if($user){
                if ($request->has('device_id') and !empty($request->device_id)) {
                    User::find($user->id)->update(['device_id' => $request->device_id, 'login_count' => $user->login_count+1]);
                    $user = User::find($user->id);
                }
                $data = [
                    'token' => $user->createToken('customer-Token')->accessToken,
                    'user' => $user
                ];
            }else{
                $user = User::where('email',$request->email)->withTrashed()->first();
                if($user){
                    return apiresponse(false, 'Your account has been deleted!');
                }
                $stripeService = new StripeService();

                $data = $request->all();
                $data['stripe_customer_id'] = $stripeService->createCustomer($request)->id;
                $data['role'] = 'rider';
                $user = User::create($data);
                $user = User::find($user->id);
                $user->login_count = $user->login_count+1;
                $user->save();
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
            $code = rand(1000, 9999);
            if (!$user) {
                return apiresponse(false, 'Email does not exist');
            } else {
                $user->confirmation_code = $code;
                if($user->save()) {
                    \Mail::to($user->email)->send(new \App\Mail\sendForgotPasswordEmail($code));
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
            $data = ['data' => []];
            return apiresponse(true, "password updated successfully", $data);
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function verifyEmail(){

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
