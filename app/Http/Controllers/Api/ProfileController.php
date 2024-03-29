<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StripeService;
use Illuminate\Http\Request;
use App\Models\{AppEmergencyNumber,
    User,
    RidePayment,
    UserAccount,
    UserChildren,
    UserLicense,
    UserVehicle,
    UserAvailable,
    Ride,
    Review,
    DriverInsurance,
    UserFvc};
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Exception;
use Auth;

class ProfileController extends Controller
{
    public function logout()
    {
        $user = auth()->user()->token();
        User::findOrFail(auth()->user()->id)->update(['device_id' => null]);
        $user->revoke();
        $data = ['data' => []];
        return apiresponse(true, 'You have been logged out successfully', $data);
    }

    public function updateProfile(Request $request)
    {
        try {
            $data = $request->all();
            if ($request->hasFile('image')) {
                $validator = Validator::make($request->all(), [
                    'image' => 'mimes:jpeg,png,jpg,gif|max:5120',
                ]);

                if ($validator->fails()) {
                    return apiresponse(false, implode("\n", $validator->errors()->all()));
                }

                $res = files_upload($request->image, 'profile');
                $data['image'] = $res;
            }

            if (isset($data['vehicle_type']) && getSanitizeString($data['vehicle_type']) == 'minivan') {
                $data['vehicle_type'] = 'mini_Van';
            }
            $res = User::find(Auth::user()->id)->update($data);
            if ($res) {
                if (Auth::user()->role == 'driver') {
                    if (isset($request->license) && $request->filled('license')) {
                        $license = $request->license;
                        $license['user_id'] = Auth::user()->id;
                        $userLicense = UserLicense::where('user_id', Auth::user()->id)->first();
                        if ($userLicense) {
                            if (isset($request->license['card_front']) && $request->license['card_front'] != null) {
                                $res = files_upload($request->license['card_front'], 'card');
                                $license['card_front'] = $res;
                            }
                            if (isset($request->license['card_back']) && $request->license['card_back'] != null) {
                                $res = files_upload($request->license['card_back'], 'card');
                                $license['card_back'] = $res;
                            }
                            UserLicense::where('user_id', Auth::user()->id)->update($license);
                        } else {
                            if (isset($request->license['card_front']) && $request->license['card_front'] != null) {
                                $res = files_upload($request->license['card_front'], 'card');
                                $license['card_front'] = $res;
                            }
                            if (isset($request->license['card_back']) && $request->license['card_back'] != null) {
                                $res = files_upload($request->license['card_back'], 'card');
                                $license['card_back'] = $res;
                            }
                            UserLicense::create($license);
                        }
                    }
                    if (isset($request->fvc)) {
                        $fvc = $request->fvc;
                        $fvc['user_id'] = Auth::user()->id;
                        $userFvc = UserFvc::where('user_id', Auth::user()->id)->first();
                        if ($userFvc) {
                            if (isset($request->fvc['image']) && $request->fvc['image'] != null) {
                                $res = files_upload($request->fvc['image'], 'fvc');
                                $fvc['image'] = $res;
                            }
                            UserFvc::where('user_id', Auth::user()->id)->update($fvc);
                        } else {
                            if (isset($request->fvc['image']) && $request->fvc['image'] != null) {
                                $res = files_upload($request->fvc['image'], 'fvc');
                                $fvc['image'] = $res;
                            }
                            UserFvc::create($fvc);
                        }
                    }
                    if (isset($request->availability) && $request->filled('availability')) {
                        userAvailable::where('user_id', Auth::user()->id)->delete();
                        foreach ($request->availability as $available) {
                            $available['user_id'] = Auth::user()->id;
                            userAvailable::create($available);
                        }
                    } else {
                        userAvailable::where('user_id', auth()->user()->id)->delete();
                    }
                    if (isset($request->insurance) && $request->filled('insurance')) {
                        // return $request->insurance;
                        $insurance = $request->insurance;
                        $insurance['user_id'] = Auth::user()->id;
                        $userInsurance = DriverInsurance::where('user_id', Auth::user()->id)->first();
                        if ($userInsurance) {
                            if (isset($request->insurance['front']) && $request->insurance['front'] != null) {
                                $res = files_upload($request->insurance['front'], 'insurance_front');
                                $insurance['front'] = $res;
                            }
                            if (isset($request->insurance['back']) && $request->insurance['back'] != null) {
                                $res = files_upload($request->insurance['back'], 'insurance_back');
                                $insurance['back'] = $res;
                            }
                            DriverInsurance::where('user_id', Auth::user()->id)->update($insurance);
                        } else {
                            if (isset($request->insurance['front']) && $request->insurance['front'] != null) {
                                $res = files_upload($request->insurance['front'], 'insurance_front');
                                $insurance['front'] = $res;
                            }
                            if (isset($request->insurance['back']) && $request->insurance['back'] != null) {
                                $res = files_upload($request->insurance['back'], 'insurance_back');
                                $insurance['back'] = $res;
                            }
                            DriverInsurance::create($insurance);
                        }
                    }
                    if (isset($request->vehicle) && $request->filled('vehicle')) {
                        $vehicle = $request->vehicle;
                        $vehicle['user_id'] = Auth::user()->id;
                        if (getSanitizeString($vehicle['booking_type']) == 'minivan') {
                            $vehicle['booking_type'] = 'Mini Van';
                        }
                        $userVehicle = UserVehicle::where('user_id', Auth::user()->id)->first();
                        if ($userVehicle) {
                            UserVehicle::where('user_id', Auth::user()->id)->update($vehicle);
                        } else {
                            UserVehicle::create($vehicle);
                        }
                    }
                } else {
                    if (isset($request->childrens) && $request->filled('childrens')) {
                        $childrens = $request->childrens;
                        foreach ($childrens as $children) {
                            // return gettype($children);
                            $children['user_id'] = Auth::user()->id;
                            if (isset($children['id'])) {
                                UserChildren::find($children['id'])->update($children);
                            } else {
                                UserChildren::create($children);
                            }
                        }
                    }

                }
            }
            $data = User::where('id', Auth::user()->id)->with('childrens', 'childrens.payment_method', 'licence', 'vehicle', 'UserPaymentMethods', 'userAvailability', 'riderInsurance', 'UserFvc')->first();
            $rides = Ride::where('driver_id', $data->id)
                ->where('status', 'completed')->count();
            if (Auth::user()->role == 'driver') {
                $total_earnings = RidePayment::where('driver_id', Auth::user()->id)->sum('total_amount');
            } else {
                $total_earnings = RidePayment::where('rider_id', Auth::user()->id)->sum('total_amount');
            }
            $data->total_earnings = $total_earnings;
            $data->total_rides = $rides;
            return apiresponse(true, Auth::user()->role . " Updated", $data);
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function childDelete(Request $request){
        $validator = Validator::make($request->all(), [
            'child_id'     => 'required',
        ]);
        if ($validator->fails()) return apiresponse(false, implode("\n", $validator->errors()->all()));
        try {
            $child = UserChildren::find($request->child_id);

            if($child){
                if($child->delete()){
                    $user = User::where('id',$child->user_id)->with('childrens','childrens.payment_method','licence','vehicle','UserPaymentMethods','userAvailability')->first();
                    return apiresponse(true, 'Child deleted',$user);
                }else{
                    return apiresponse(false, 'Something went wrong');
                }

            }else{
                return apiresponse(false, 'child not found');
            }
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function makeUserAuthenticate()
    {
        try {
            $user = auth()->user();
            $checkUser = User::find($user->id);
            if($checkUser) {
                $checkUser->is_authenticated = 1;
                $checkUser->save();
                return apiresponse(true, __('User authenticated'), ['data' => auth()->user()]);
            }
            return apiresponse(false, __('User not found'), ['data' => null]);
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function updateLatLong(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }
        try {
            $user = User::where('id',$request->user_id)->first();
            $user->longitude = $request->longitude;
            $user->latitude = $request->latitude;
            $user->save();
            return apiresponse(true, 'Location updated');
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function onlineOffline(){
        try {
            $user = User::where('id',Auth::user()->id)->with('childrens','UserPaymentMethods','childrens.payment_method','licence','vehicle','userAvailability','UserFvc')->first();
            if(Auth::user()->role == 'rider'){
                $total_earnings = RidePayment::where('rider_id', Auth::user()->id)->sum('total_amount');
                $rides = Ride::where('rider_id',Auth::user()->id)
                ->where('status','completed')->count();
            }else{
                $total_earnings = RidePayment::where('driver_id', Auth::user()->id)->sum('total_amount');
                $rides = Ride::where('driver_id',Auth::user()->id)
                ->where('status','completed')->count();
            }
            if(Auth::user()->is_online == 1){
                $user->is_online = 0;
            }else{
                $user->is_online = 1;
            }
            $user->save();
            Auth::setUser($user);
            $user->total_rides = $rides;
            $user->total_earnings = $total_earnings;
            return apiresponse(true,'Users status updated',$user);
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function updateIsBoard(Request $request){
        $validator = Validator::make($request->all(), [
            'is_board' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }
        try {
            $user = User::find(Auth::user()->id);
            if(Auth::user()->role == 'rider'){
                $rides = Ride::where('rider_id',Auth::user()->id)
                ->where('status','completed')->count();
            }else{
                $rides = Ride::where('driver_id',Auth::user()->id)
                ->where('status','completed')->count();
            }
            $user->is_broad = $request->is_board;
            $user->save();
            Auth::setUser($user);
            $user->total_rides = $rides;
            return apiresponse(true, 'User updated',$user);
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function getUser(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }
        try {
            $user = User::where('id',$request->user_id)->with(['vehicle','licence','UserPaymentMethods','userAvailability','getReview','getReview.fromUser'])->first();
            if($user){
                $avg = Review::where('to',$user->id)->avg('rating');
                if($avg){
                    $user->average_rating = round($avg,2);
                }else{
                    $user->average_rating = 0;
                }

                return apiresponse(true, 'user found' ,$user);
            }else{
                return apiresponse(false, 'user found' );
            }
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function deleteUser(){
        try {
            $user = User::find(Auth::user()->id);
            if($user){
                $user->status = 0;
                $user->save();
                $user->delete();
                return apiresponse(true,'Account deleted',1);
            }else{
                return apiresponse(false,'Account not found');
            }
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required_with:password|min:8',
            'new_password' => 'min:8|required_with:confirm_password|same:confirm_password|different:old_password',
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }

        try {
            $checkPassword = Hash::check($request->old_password, auth()->user()->password);
            if (!$checkPassword) {
                return apiresponse(false, "Old password is incorrect");
            }

            $data['password'] = Hash::make($request->new_password);
            User::findOrFail(auth()->user()->id)->update($data);
            return apiresponse(true, 'Password has been updated successfully', ['data' => $data]);

        } catch (\Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function getUserInfo(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $user = User::where('id', auth()->user()->id)->with('licence', 'vehicle', 'childrens', 'UserPaymentMethods', 'childrens.payment_method', 'userAvailability', 'UserFvc')->first();

            if (!$user) {
                return apiresponse(false, 'User not found', ['data' => null]);
            }

            $stripeService = new StripeService();
            $userAccount = UserAccount::where('user_id', $user->id)->first();
            if (!$userAccount) {
                $createStripeAccount = $stripeService->createOnBoarding($user, '1995-01-01');
                $userStripeAccount = new UserAccount();
                $userStripeAccount->user_id = $user->id;
                $userStripeAccount->stripe_account_id = $createStripeAccount->id;
                $userStripeAccount->save();
            }

            if ($user->role == 'rider') {
                $rides = Ride::where('rider_id', $user->id)
                    ->where('status', 'completed')
                    ->with('driver', 'rider', 'rideLocations', 'ridePayment', 'review')->count();
            } else {
                $rides = Ride::where('driver_id', $user->id)
                    ->where('status', 'completed')
                    ->with('driver', 'rider', 'rideLocations', 'ridePayment', 'review', 'UserFvc')->count();
            }
            $user->login_count = $user->login_count + 1;
            $user->save();
            if ($user->role == 'driver') {
                $total_earnings = RidePayment::where('driver_id', $user->id)->sum('total_amount');
            } else {
                $total_earnings = RidePayment::where('rider_id', $user->id)->sum('total_amount');
            }
            $user->total_earnings = $total_earnings;
            $user->total_rides = $rides;

            $onboarding_url = $stripeService->getConnectUrl($user->stripeAccount->stripe_account_id, $user->id);
            if ($user->role == 'rider') {
                $user->is_completed_profile = 1;
            }
            $data = [
                'user' => $user,
                'csrf_token' => csrf_field(),
                'onboarding_url' => $onboarding_url,
                'emergency_contact_no' => AppEmergencyNumber::count() > 0 ? AppEmergencyNumber::first()->emergency_number : null
            ];

            return apiresponse(true, 'User data has been loaded successfully', $data);
        } catch (Exception $exception) {
            return apiresponse(false, $exception->getMessage());
        }
    }
}
