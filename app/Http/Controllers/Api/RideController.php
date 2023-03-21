<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\{Ride,RideLocation,User,RideType,UserChildren,RideRequestedTo,RidePayment,ChatList,ChatListMessage, Commission, UserPaymentMethod};
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\StripeService;
use Auth;
use DB;
use Exception;
Use \Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Stripe\StripeClient;
use Illuminate\Support\Arr;



class RideController extends Controller
{
    protected $total_distance = 0;

    public function __construct()
    {
        $this->stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ride_for' => 'required',
            'ride_locations' => 'required',
            'vehicle_type' => 'required',
            'pickUpLong' => 'required',
            'pickUpLat' => 'required',
            'type' => 'required',
            'total_distance' => 'required',
            'total_time' => 'required',
            'total_price' => 'required',
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }

        try {

            $previousRide = Ride::where('rider_id', auth()->user()->id)->where('type', 'normal')->where(function ($q) {
                $q->where('status', 'accepted')->orWhere('status', 'confirmed');
            })->first();
            if ($previousRide) {
                return apiresponse(false, __('Ride already booked'), ['data' => null]);
            }
//            $adminDistance =
            $distances = DB::select('SELECT id,latitude, longitude,vehicle_type,role, SQRT(
                POW(69.1 * (latitude - ' . $request->pickUpLat . '), 2) +
                POW(69.1 * (' . $request->pickUpLong . ' - longitude) * COS(latitude / 57.3), 2)) AS distance
                FROM users
                WHERE role = \'driver\'
                AND is_online = \'1\'
                AND vehicle_type = \'' . $request->vehicle_type . '\'
                HAVING distance < ' . radius_of_searches() . ' ORDER BY distance
            ;');
            if ($distances) {
                $data['rider_id'] = Auth::user()->id;
                $data['request_time'] = Carbon::now();
                $data['status'] = 'requested';
                $data['ride_for'] = $request->ride_for;
                if ($request->type == 'schedule') {
                    $data['schedule_start_time'] = $request->schedule_start_time;
                }
                $data['type'] = $request->type;
                $data['vehicle_type'] = $request->vehicle_type;
                $data['estimated_distance'] = $request->total_distance;
                $data['estimated_time'] = $request->total_time;
                $data['estimated_price'] = $request->total_price;
                $res = Ride::create($data);
                // return gettype($res);
                foreach ($distances as $user) {
                    $resUser = User::where('id', $user->id)->with('vehicle', 'toReview')->first();
                    if (!$resUser) {
                        continue;
                    }

                    $acceptedRidesCount = Ride::where('driver_id', $user->id)->whereIn('status', ['accepted', 'confirmed'])->count();
                    if ($acceptedRidesCount > 0) {
                        continue;
                    }

                    $resUser->distance = $user->distance;
                    $users[] = $resUser;
                    $requestedTo = new RideRequestedTo();
                    $requestedTo->driver_id = $resUser->id;
                    $requestedTo->ride_id = $res->id;
                    $requestedTo->save();
                    $title = 'You have a new ride request from ' . Auth::user()->username;
                    $body = 'You have a new ride request from ' . Auth::user()->username;
                    SendNotification($resUser->device_id, $title, $body);
                    saveNotification($title, $body, 'ride_request', Auth::user()->id, $resUser->id);
                }
                foreach ($request->ride_locations as $location) {
                    $rideLocation = new RideLocation();
                    $rideLocation->address = $location['address'];
                    $rideLocation->longitude = $location['longitude'];
                    $rideLocation->latitude = $location['latitude'];
                    if (isset($location['children_id']) && $location['children_id'] != 'null' && $location['children_id'] != null) {
                        $rideLocation->user_children_id = $location['children_id'];
                    }
                    $rideLocation->ride_order = $location['ride_order'];
                    $rideLocation->ride_id = $res->id;
                    $rideLocation->save();
                }
                //creating array with index id => 21 like
                $updatedUsers = [];
                $users = Arr::pluck($users, 'id');
                foreach ($users as $user) {
                    $updatedUsers[] = ['id' => $user];
                }
                $response['users'] = $updatedUsers;
                $response['ride'] = Ride::where('id', $res->id)->with(['driver', 'rider', 'rideLocations', 'rideLocations.children'])->first();
                $response['schedule_type'] = true;
                broadcast(new \App\Events\InitialRideEvent($response))->toOthers();
                if ($res) {
                    return apiresponse(true, 'Searching for driver', $response);
                } else {
                    return apiresponse(false, 'Something went wrong');
                }
            } else {
                return apiresponse(false, 'No driver found');
            }
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function availableDrivers(Request $request){
        $validator = Validator::make($request->all(), [
            'long' => 'required',
            'lat' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }
        try {
            $distances = DB::select('SELECT id,latitude, longitude,vehicle_type,role, SQRT(
                POW(69.1 * (latitude - '.$request->lat.'), 2) +
                POW(69.1 * ('.$request->long.' - longitude) * COS(latitude / 57.3), 2)) AS distance
                FROM users
                WHERE role = \'driver\'
                AND is_online = \'1\'
                HAVING distance < 100 ORDER BY distance
            ;');
            if($distances){
                foreach($distances as $user){
                    $ids[] = $user->id;
                }
                $users = User::whereIn('id',$ids)->get();
                return apiresponse(true,'Drivers found', $users);
            }else{
                return apiresponse(false,'Drivers not found');
            }
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function calculateDistance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ride_locations' => 'required',
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }

        try {
            $ride_types = RideType::all();
            $totalDistance = 0;
            $totalTime = 0;
            $origin = $request->ride_locations[0]['latitude'] . "," . $request->ride_locations[0]['longitude'];
            foreach ($request->ride_locations as $location) {
                if ($location['ride_order'] == '1') {
                    continue;
                }
                $destination = $location['latitude'] . "," . $location['longitude'];
                $res = findDistance($destination, $origin);
                if ($res['rows'][0]['elements'][0]['status'] == 'ZERO_RESULTS') {
                    return apiresponse(false, "Invalid location");
                }
                $rawDistance = $res['rows'][0]['elements'][0]['distance']['value'];
                $rawTime = $res['rows'][0]['elements'][0]['duration']['value'];
                $res['coordinates']['latitude'] = $location['latitude'];
                $res['coordinates']['longitude'] = $location['longitude'];
                if (isset($location['children_id']) && $location['children_id'] != null) {
                    $children = UserChildren::find($location['children_id']);
                    if ($children) {
                        $res['child'] = $children;
                    }
                }
                $totalDistance += $rawDistance;
                $totalTime += $rawTime;
                foreach ($ride_types as $type) {
//                    $res['price'][$type->name] = round(($rawDistance * 0.000621) * $type->price, 2);
                    $res['price'][$type->name] = round(($rawDistance * 0.000621) * charges_per_mile(), 2);
                }
                $distanceArray[] = $res;

                $origin = $destination;
            }
            /*$car = $totalDistance * charges_per_mile();
            $suv = $totalDistance * charges_per_mile();
            $mini_van = $totalDistance * charges_per_mile();*/
            $data['total_distance'] = $totalDistance;
            $data['total_time'] = $totalTime;
            $data['routes_data'] = $distanceArray;
            foreach ($ride_types as $type) {

//                $data['total_prices'][] = ['id' => $type->id, 'name' => $type->name, 'price' => round(($totalDistance * 0.000621) * $type->price, 2)];
                $data['total_prices'][] = ['id' => $type->id, 'name' => $type->name, 'price' => round(($totalDistance * 0.000621) * charges_per_mile(), 2)];
            }
            // $data['total_prices'][]['total_car_price'] = $car;
            // $data['total_prices'][]['total_mini_van_price'] = $mini_van;
            // $data['total_prices'][]['total_suv_price'] = $suv;
            return apiresponse(true, "Data found", $data);
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function acceptRide(Request $request){
        $validator = Validator::make($request->all(), [
            'ride_id' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }
        try {
            $ride = Ride::where('id',$request->ride_id)->first();
            if($ride->status == 'accepted'){
                return apiresponse(true, 'Ride already accepted by another driver');
            }else{
                $ride->driver_id = Auth::user()->id;
                $ride->status = 'accepted';
                $ride->updated_at = Carbon::now();
                $ride->save();
                $rideUpdated = Ride::where('id',$ride->id)->with(['driver','rider','rideLocations','rideLocations.children'])->first();
                $rideUpdated->vehicle = $rideUpdated->driver->vehicle;
                $title = 'You have a new notification from ' . Auth::user()->username;
                $body = 'You have a new notification from ' . Auth::user()->username;
                SendNotification($ride->rider->device_id, $title, $body);
                saveNotification($title,$body,'ride_accepted',Auth::user()->id,$ride->rider_id);
                broadcast(new \App\Events\AcceptRideEvent($rideUpdated))->toOthers();

                //Send notification
                return apiresponse(true, 'Ride accepted',$rideUpdated);
            }

        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function confirmRide(Request $request){
        $validator = Validator::make($request->all(), [
            'ride_id' => 'required',
            'confirm' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }
        try {
            if($request->confirm){
                $rideUpdated = Ride::where('id',$request->ride_id)->with(['driver','rider','rideLocations','rideLocations.children'])->first();
                if($request->filled('payment_method_id')){
                    $rideUpdated->payment_method_id = $request->payment_method_id;

                }
                $rideUpdated->status = 'confirmed';
                $rideUpdated->save();
                $title = 'You have a new notification from ' . Auth::user()->username;
                $body = 'You have a new notification from ' . Auth::user()->username;
                SendNotification($rideUpdated->driver->device_id, $title, $body);
                saveNotification($title,$body,'ride_confirmed',Auth::user()->id,$rideUpdated->driver_id);
                foreach($rideUpdated->rideLocations as $location){
                    if($location->ride_order == 1){
                        $destination = $location->latitude.','.$location->longitude;
                        break;
                    }
                }
                $user = User::find($rideUpdated->driver_id);
                $origin = $user->latitude.','.$user->longitude;

                $res = findDistance($destination,$origin);
                if($res['rows'][0]['elements'][0]['status'] == 'ZERO_RESULTS'){
                    return apiresponse(false,"Invalid location");
                }
                $rideUpdated->vehicle = $rideUpdated->driver->vehicle;
                $rideUpdated->time_and_distance = $res['rows'][0]['elements'][0]['duration']['text'];
                broadcast(new \App\Events\ConfirmRideEvent($rideUpdated))->toOthers();
                return apiresponse(true, 'Ride confirmed',$rideUpdated);
            }else{
                $rideLocations = RideLocation::where('id',$request->ride_id)->get();
                if($rideLocations){
                    foreach($rideLocations as $location){
                        $location->delete();
                    }
                }
                $ride = Ride::find($request->ride_id);
                if($ride){
                    $ride->delete();
                    $rideUpdated['id'] = $request->ride_id;
                    $rideUpdated['message'] = 'Ride has been canceled';
                    $rideUpdated = new Collection($rideUpdated);
                    broadcast(new \App\Events\ConfirmRideEvent($rideUpdated))->toOthers();
                    //Send notification
                    return apiresponse(true, 'Ride has been canceled',$rideUpdated);
                }else{
                    return apiresponse(false, 'invalid ride id');
                }
            }
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function startRide(Request $request){
        $validator = Validator::make($request->all(), [
            'ride_id' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }
        try {
                $ride = Ride::where('id',$request->ride_id)->where('status','confirmed')->first();
                if($ride){
                    $ride->start_time = Carbon::now();
                    $ride->driver_status = 'started';
                    $ride->save();
                    $title = 'You have a new notification from ' . Auth::user()->username;
                    $body = 'You have a new notification from ' . Auth::user()->username;
                    SendNotification($ride->rider->device_id, $title, $body);
                    saveNotification($title,$body,'ride_started',Auth::user()->id,$ride->rider_id);
                    $rideStartLocation = RideLocation::where('ride_id',$ride->id)->where('ride_order','1')->first();
                    $rideStartLocation->status = 'completed';
                    $rideStartLocation->save();
                    $rideUpdated = Ride::where('id',$ride->id)->with('driver','rider','rideLocations','rideLocations.children')->first();
                    foreach($rideUpdated->rideLocations as $location){
                        if($location->ride_order == 1){
                            $destination = $location->latitude.','.$location->longitude;
                            break;
                        }
                    }
                    $user = User::find($rideUpdated->driver_id);
                    $origin = $user->latitude.','.$user->longitude;

                    $res = findDistance($destination,$origin);
                    $rideUpdated->vehicle = $rideUpdated->driver->vehicle;
                    $rideUpdated->time_and_distance = $res['rows'][0]['elements'][0]['duration']['text'];
                    broadcast(new \App\Events\StartRideEvent($rideUpdated))->toOthers();
                    //Send notification
                    return apiresponse(true, 'Ride started',$rideUpdated);
                }else{
                    return apiresponse(false, 'Ride not confirmed');
                }

        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function dropRide(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location_id' => 'required',
            'ride_id' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }
        try {
            $stripeService = new StripeService();
            $rideCurrentLocation = RideLocation::where('id', $request->location_id)->first();
            $ride = Ride::find($rideCurrentLocation->ride_id);
            $user = User::find($ride->rider_id);
            $order = $rideCurrentLocation->ride_order - 1;
            $prevoiusLocation = RideLocation::where('ride_id', $rideCurrentLocation->ride_id)->where('ride_order', $order)->first();
            $origin = $rideCurrentLocation->latitude . "," . $rideCurrentLocation->longitude;
            $destination = $prevoiusLocation->latitude . "," . $prevoiusLocation->longitude;
            $res = findDistance($destination, $origin);
            $distance = $res['rows'][0]['elements'][0]['distance']['value'];
            $price = round(($distance * 0.000621) * charges_per_mile(), 2);
//            $price = ($distance * 0.000621) * charges_per_mile();
            if ($rideCurrentLocation->user_children_id != null) {
                $userChildren = UserChildren::find($rideCurrentLocation->user_children_id);
                if ($userChildren->user_card_id != null) {
                    $userPaymentMethod = UserPaymentMethod::find($userChildren->user_card_id);
                    $Charge = $stripeService->createCharge($price, $user->stripe_customer_id, $userPaymentMethod->stripe_source_id);
                    if ($Charge->status != 'succeeded') {
                        return apiResponse(false, __('payment Not completed'));
                    }
                }
            } else {
                $userPaymentMethod = UserPaymentMethod::where('user_id', $user->id)->where('default', 1)->first();
                $Charge = $stripeService->createCharge($price, $user->stripe_customer_id, $userPaymentMethod->stripe_source_id);
                if ($Charge->status != 'succeeded') {
                    return apiResponse(false, __('payment Not completed'));
                }
            }
            $rideCurrentLocation->status = 'completed';
            $rideCurrentLocation->price = $price;
            $rideCurrentLocation->save();
            $commission = Commission::first();
            $newprice = ($price * ((100 - commission()) / 100)); // subtract $amount % of $price, from $price
            $ridePayment = new RidePayment();
            $ridePayment->ride_id = $ride->id;
            $ridePayment->base_amount = $price;
            $ridePayment->total_amount = $price;
            $ridePayment->commission = (commission() / 100) * $price;
            $ridePayment->commission_percentage = commission();
            $ridePayment->driver_ammount = $newprice;
            $ridePayment->rider_amount = $price;
            $ridePayment->type = $userPaymentMethod->type;
            $ridePayment->is_paid = 0;
            $ridePayment->user_card_id = $userPaymentMethod->id;
            $ridePayment->driver_id = $ride->driver_id;
            $ridePayment->rider_id = $ride->rider_id;
            $ridePayment->save();

            $rideUpdated = Ride::where('id', $request->ride_id)->with('driver', 'rider', 'rideLocations', 'rideLocations.children')->first();

            //Send notification
            return apiresponse(true, 'Ride dropped', $rideUpdated);

        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function cancelRide(Request $request){
        $validator = Validator::make($request->all(), [
            'ride_id' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }
        try {
            $rideStartLocation = Ride::where('id',$request->ride_id)->first();
            $rideStartLocation->status = 'canceled';
            $rideStartLocation->save();
            $rideUpdated = Ride::where('id',$rideStartLocation->id)->with('driver','rider','rideLocations','rideLocations.children')->first();
            RideRequestedTo::where('ride_id',$rideUpdated->id)->delete();
            //Send notification
            broadcast(new \App\Events\AcceptRideEvent($rideUpdated))->toOthers();
            broadcast(new \App\Events\DriverCancelRide($rideUpdated))->toOthers();
            return apiresponse(true, 'Ride canceled',$rideUpdated);
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function driverCancelRide(Request $request){
        $validator = Validator::make($request->all(), [
            'ride_id' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }
        try {
            $requestedRide = RideRequestedTo::where('ride_id',$request->ride_id)->where('driver_id',Auth::user()->id)->first();
            if($requestedRide->delete()){
                $rideUpdated = Ride::where('id',$rideStartLocation->id)->with('driver','rider','rideLocations','rideLocations.children')->first();
                broadcast(new \App\Events\DriverCancelRide($rideUpdated))->toOthers();
                return apiresponse(true, 'RideRide canceled',$rideUpdated);
            }else{
                return apiresponse(false, 'Something went wrong');
            }

            //Send notification
            // broadcast(new \App\Events\AcceptRideEvent($rideUpdated))->toOthers();

        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function rideComplete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ride_id' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }
        try {
            $stripeService = new StripeService();
            $rideStartLocation = RideLocation::where('ride_id', $request->ride_id)->orderBy('ride_order', 'asc')->first();
            $ride = Ride::find($rideStartLocation->ride_id);
            if ($ride->status == 'completed') {
                return apiresponse(false, 'Ride already completed');
            }
            $user = User::find($ride->rider_id);
            $order = $rideStartLocation->ride_order + 1;
            $prevoiusLocation = RideLocation::where('ride_id', $rideStartLocation->ride_id)->where('ride_order', $order)->first();
            $origin = $rideStartLocation->latitude . "," . $rideStartLocation->longitude;
            $destination = $prevoiusLocation->latitude . "," . $prevoiusLocation->longitude;
            $res = findDistance($destination, $origin);
            $distance = $res['rows'][0]['elements'][0]['distance']['value'];
            $price = round(($distance * 0.000621) * charges_per_mile(), 2);
            if ($rideStartLocation->user_children_id != null) {
                $userChildren = UserChildren::find($rideStartLocation->user_children_id);
                if ($userChildren->user_card_id != null) {
                    $userPaymentMethod = UserPaymentMethod::find($userChildren->user_card_id);
                    $Charge = $stripeService->createCharge($price, $user->stripe_customer_id, $userPaymentMethod->stripe_source_id);
                    if ($Charge->status != 'succeeded') {
                        return apiResponse(false, __('payment Not completed'));
                    }
                }
            } else {
                $userPaymentMethod = UserPaymentMethod::where('user_id', $user->id)->where('default', 1)->first();
                $Charge = $stripeService->createCharge($price, $user->stripe_customer_id, $userPaymentMethod->stripe_source_id);
                if ($Charge->status != 'succeeded') {
                    return apiResponse(false, __('payment Not completed'));
                }
            }
            $rideStartLocation->status = 'completed';
            $rideStartLocation->price = $price;
            $rideStartLocation->save();
            $commission = Commission::first();
            $newprice = ($price * ((100 - commission()) / 100)); // subtract $amount % of $price, from $price
            $ridePayment = new RidePayment();
            $ridePayment->ride_id = $ride->id;
            $ridePayment->base_amount = $price;
            $ridePayment->total_amount = $price;
            $ridePayment->commission = (commission() / 100) * $price;
            $ridePayment->commission_percentage = commission();
            $ridePayment->driver_ammount = $newprice;
            $ridePayment->rider_amount = $price;
            $ridePayment->type = $userPaymentMethod->type;
            $ridePayment->is_paid = 0;
            $ridePayment->user_card_id = $userPaymentMethod->id;
            $ridePayment->driver_id = $ride->driver_id;
            $ridePayment->rider_id = $ride->rider_id;
            $ridePayment->save();
            foreach (RideLocation::where('ride_id', $request->ride_id)->get() as $value) {
                if ($value->status == 'pending') {
                    return apiresponse(false, 'Please complete your drops');
                }
            }
            // $origin = $request->ride_locations[0]['latitude'].",".$request->ride_locations[0]['longitude'];
            // foreach($request->ride_locations as $location){
            //     if($location['ride_order'] == '1'){
            //         continue;
            //     }
            //     $destination =  $location['latitude'].",".$location['longitude'];
            //     $res = findDistance($destination,$origin);
            //     $rawDistance = $res['rows'][0]['elements'][0]['distance']['value'];
            //     $rawTime = $res['rows'][0]['elements'][0]['duration']['value'];
            //     $res['coordinates']['latitude'] = $location['latitude'];
            //     $res['coordinates']['longitude'] = $location['longitude'];
            //     $totalDistance += $rawDistance;
            //     $totalTime += $rawTime;
            //     $origin = $destination;
            // }

            // $ride_type = RideType::where('type',$rideStartLocation->vehicle_type)->first();

            $ride->end_time = Carbon::now();
            $ride->status = 'completed';
            $ride->estimated_price = RideLocation::where('ride_id', $ride->id)->sum('price');
            $ride->save();
            // $card = UserPaymentMethod::where('user_id',$ride->rider_id)->where('default', 1)->first();
            // $driverProfile = User::find($ride->rider_id);

            // $Charge = $stripeService->createCharge($ride->estimated_price, $driverProfile->stripe_customer_id, $card->stripe_source_id);
            // if($Charge->status != 'succeeded') {
            //     return apiResponse(false, __('payment Not completed'));
            // }
            // $payment = $this->stripe->charges->create([
            //     "amount" => 100 * ($ride->estimated_price),
            //     "currency" => "USD",
            //     "source" => $card->stripe_source_id,
            //     "customer" => auth()->user()->stripe_customer_id,
            //     "description" => "Membership Booking."
            // ]);
            // if($ride->save()){
            //     $ridePayment = new RidePayment();
            //     $ridePayment->ride_id = $ride->id;
            //     $ridePayment->base_amount = $ride->estimated_price;
            //     $ridePayment->total_amount = $ride->estimated_price;
            //     $ridePayment->type = 'card';
            //     $ridePayment->user_card_id = $card->id;
            //     $ridePayment->driver_id = $ride->driver_id;
            //     $ridePayment->rider_id = $ride->rider_id;
            //     $ridePayment->save();
            // }

            //Stripe computation

            $ride = Ride::where('id', $request->ride_id)->with(['driver', 'rider', 'rideLocations', 'ridePayment'])->first();
            //Send notification
            broadcast(new \App\Events\CompleteRideEvent($ride))->toOthers();
            return apiresponse(true, 'Ride Completed', $ride);

        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function scheduleRides(){
        try {
            if(Auth::user()->role == 'rider'){
                $rides = Ride::where('rider_id',Auth::user()->id)
                ->where('type','schedule')
                ->whereIn('status',['accepted','confirmed'])
                ->with('driver','driver.vehicle','driver.licence','rider','rideLocations','ridePayment')->orderBy("id","DESC")->paginate(10);
            }else{
                $rides = Ride::where('driver_id',Auth::user()->id)
                ->where('type','schedule')
                ->whereIn('status',['accepted','confirmed'])
                ->with('driver','driver.vehicle','driver.licence','rider','rideLocations','ridePayment')->orderBy("id","DESC")->paginate(10);
            }
            return apiresponse(true,'Rides found',$rides);
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function webScheduleRides()
    {
        try {
            if (Auth::user()->role == 'rider') {
                $rides = Ride::where('rider_id', Auth::user()->id)
//                    ->where('type','schedule')
//                    ->whereIn('status',['accepted','confirmed'])
                    ->with('driver', 'driver.vehicle', 'driver.licence', 'rider', 'rideLocations', 'ridePayment')->orderBy("id", "DESC")->paginate(10);
            } else {
                $rides = Ride::where('driver_id', Auth::user()->id)
//                    ->where('type','schedule')
//                    ->whereIn('status',['accepted','confirmed'])
                    ->with('driver', 'driver.vehicle', 'driver.licence', 'rider', 'rideLocations', 'ridePayment')->orderBy("id", "DESC")->paginate(10);
            }
            return apiresponse(true, 'Rides found', $rides);
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function pastRides()
    {
        try {
            if (Auth::user()->role == 'rider') {
                $rides = Ride::where('rider_id', Auth::user()->id)
                    ->whereIn('status', ['completed', 'canceled'])
                    ->with('driver', 'rider', 'rideLocations', 'ridePayment', 'review', 'ridePayment.payment_method')->orderBy("id", "DESC")->paginate(10);
            } else {
                $rides = Ride::where('driver_id', Auth::user()->id)
                    ->whereIn('status', ['completed', 'canceled'])
                    ->with('driver', 'rider', 'rideLocations', 'ridePayment', 'review', 'ridePayment.payment_method')->orderBy("id", "DESC")->paginate(10);
            }
            return apiresponse(true, 'Rides found', $rides);
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function canceledRides(){
        try {
            if(Auth::user()->role == 'rider'){
                $rides = Ride::where('rider_id',Auth::user()->id)
                ->where('status','canceled')
                ->with('driver','rider','rideLocations','ridePayment','review')->paginate(10);
            }else{
                $rides = Ride::where('driver_id',Auth::user()->id)
                ->where('status','canceled')
                ->with('driver','rider','rideLocations','ridePayment','review')->paginate(10);
            }
            return apiresponse(true,'Rides found',$rides);
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function totalRides(){
        try {
            if(Auth::user()->role == 'rider'){
                $rides = Ride::where('rider_id',Auth::user()->id)
                ->where('status','completed')
                ->with('driver','rider','rideLocations','ridePayment','review')->paginate(10);
            }else{
                $rides = Ride::where('driver_id',Auth::user()->id)
                ->where('status','completed')
                ->with('driver','rider','rideLocations','ridePayment','review')->paginate(10);
            }
            return apiresponse(true,'Rides found',$rides);
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function latestRide(Request $request)
    {
        try {
            $upcomingTime = Date("Y-m-d H:i:s", strtotime('+1 hour'));
            $pastTime = Date("Y-m-d H:i:s", strtotime('-1 hour'));
            $currentTime = Date("Y-m-d H:i:s");

            if (isset($request->timezone) && $request->timezone != null) {
                $upcomingTimeDate = new \DateTime("+1 hour", new \DateTimeZone($request->timezone));
                $upcomingTime = $upcomingTimeDate->format('Y-m-d H:i:s');

                $pastTimeDate = new \DateTime("-1 hour", new \DateTimeZone($request->timezone));
                $pastTime = $pastTimeDate->format('Y-m-d H:i:s');

                $currentTimeDate = new \DateTime("now", new \DateTimeZone($request->timezone));
                $currentTime = $currentTimeDate->format('Y-m-d H:i:s');
            }

            if (Auth::user()->role == 'driver') {
                // return Date("Y-m-d H:i:s"); //2022-10-06 14:47:18
                $rideUpdated = Ride::where('driver_id', Auth::user()->id)->with('driver', 'rider', 'rideLocations')
                    ->whereIn('status', ['confirmed', 'accepted'])
//                    ->where('type', 'schedule')
//                    ->whereBetween('schedule_start_time', [$pastTime, $upcomingTime])
                    ->orderBy('id', 'desc')
                    ->first();
                // return $rideUpdated;

                // return $upcomingTime;
                if ($rideUpdated) {
                    // if ($rideUpdated->type == 'schedule' && ($rideUpdated->schedule_start_time > $pastTime) && ($upcomingTime < $rideUpdated->schedule_start_time)) {
                    if ($rideUpdated->type == 'schedule' && ($rideUpdated->schedule_start_time >= $currentTime) && ($upcomingTime <= $rideUpdated->schedule_start_time)) {
                        return apiresponse(true, 'Ride not found');
                    }
                    $chatList = ChatList::where('from', $rideUpdated->rider_id)->where('to', $rideUpdated->driver_id)->first();
                    if (!$chatList) {
                        $chatList = ChatList::where('to', $rideUpdated->rider_id)->where('from', $rideUpdated->driver_id)->first();
                    }
                    if (!$chatList) {
                        $chatListData = [
                            'to' => $rideUpdated->rider_id,
                            'from' => $rideUpdated->driver_id,
                        ];
                        $chatList = ChatList::create($chatListData);
                    }
                    $chatCount = ChatListMessage::where('chat_list_id', $chatList->id)->where('is_read', 0)->count();
                    foreach ($rideUpdated->rideLocations as $location) {
                        if ($location->ride_order == 1) {
                            $destination = $location->latitude . ',' . $location->longitude;
                            break;
                        }
                    }
                    $user = User::find($rideUpdated->driver_id);
                    $origin = $user->latitude . ',' . $user->longitude;

                    $res = findDistance($destination, $origin);
                    $rideUpdated->vehicle = $rideUpdated->driver->vehicle;
                    $rideUpdated->total_messages = $chatCount;
                    $rideUpdated->time_and_distance = $res['rows'][0]['elements'];
//                    $rideUpdated->schedule_type = $rideUpdated->type == 'schedule' ? true : false;
                    $rideUpdated->schedule_type = $currentTime >= $rideUpdated->schedule_start_time ? true : false;
                    return apiresponse(true, 'Ride found', $rideUpdated);
                } else {
                    return apiresponse(true, 'Ride not found');
                }
            } else {
                $rideUpdated = Ride::where('rider_id', Auth::user()->id)->with('driver', 'rider', 'rideLocations')
                    ->whereIn('status', ['confirmed', 'accepted'])
//                    ->where('type', 'schedule')
//                    ->whereBetween('schedule_start_time', [$pastTime, $upcomingTime])
                    // ->whereDate('schedule_start_time', '>=', $pastTime)
                    // ->whereDate('schedule_start_time', '<=', $upcomingTime)
                    ->orderBy('id', 'desc')
                    ->first();
                if ($rideUpdated) {
                    // if ($rideUpdated->type == 'schedule' && ($rideUpdated->schedule_start_time > $pastTime) && ($upcomingTime < $rideUpdated->schedule_start_time)) {
                    if ($rideUpdated->type == 'schedule' && ($rideUpdated->schedule_start_time >= $currentTime) && ($upcomingTime <= $rideUpdated->schedule_start_time)) {
                        return apiresponse(true, 'Ride not found');
                    }
                    foreach ($rideUpdated->rideLocations as $location) {
                        if ($location->ride_order == 1) {
                            $destination = $location->latitude . ',' . $location->longitude;
                            break;
                        }
                    }

                    $user = User::find($rideUpdated->driver_id);
                    $origin = $user->latitude . ',' . $user->longitude;

                    $res = findDistance($destination, $origin);

                    $rideUpdated->vehicle = $rideUpdated->driver->vehicle;
                    $rideUpdated->time_and_distance = $res['rows'][0]['elements'][0]['status'] !== "ZERO_RESULTS" ? $res['rows'][0]['elements'][0]['duration']['text'] : null;
                    $rideUpdated->schedule = $res['rows'][0]['elements'][0]['status'] !== "ZERO_RESULTS" ? $res['rows'][0]['elements'][0]['duration']['text'] : null;
//                    $rideUpdated->schedule_type = $rideUpdated->type == 'schedule' ? true : false;
                    $rideUpdated->schedule_type = $currentTime >= $rideUpdated->schedule_start_time ? true : false;
                    return apiresponse(true, 'Ride found', $rideUpdated);
                } else {
                    return apiresponse(true, 'Ride not found');
                }
            }
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function lastRide(){
        try {
            if(Auth::user()->role == 'driver'){
                $rideUpdated = Ride::where('driver_id',Auth::user()->id)->with('driver','rider','rideLocations','ridePayment')
                ->whereIn('status',['completed','canceled'])
                ->orderBy('id','desc')
                ->first();
                if($rideUpdated){
                    return apiresponse(true,'Ride found',$rideUpdated);
                }else{
                    return apiresponse(false,'Ride not found');
                }
            }else{
                $rideUpdated = Ride::where('rider_id',Auth::user()->id)->with('driver','rider','rideLocations','ridePayment')
                ->where('status','completed')
                ->orderBy('id','desc')
                ->first();
                if($rideUpdated){
                    return apiresponse(true,'Ride found',$rideUpdated);
                }else{
                    return apiresponse(true,'Ride not found');
                }
            }
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function driverRequestedRides()
    {
        try {
            $acceptedRidesCount = Ride::where('driver_id', auth()->user()->id)->whereIn('status', ['accepted', 'confirmed'])->count();
            if ($acceptedRidesCount > 0) {
                return apiresponse(false, "No ride found");
            }

            $requestedRides = RideRequestedTo::where('driver_id', Auth::user()->id)->get();
            if ($requestedRides->count() == 0) {
                return apiresponse(false, "No ride found");
            }

            $arr = [];
            $destination = "";
            foreach ($requestedRides as $rRide) {
                $ride = Ride::where('id', $rRide->ride_id)->where('status', 'requested')->with('driver', 'rider', 'rideLocations')->first();
                if ($ride) {
                    // $chatList = ChatList::where('from',$ride->rider_id)->where('to',$ride->driver_id)->first();
                    // if(!$chatList){
                    //     $chatList = ChatList::where('to',$ride->rider_id)->where('from',$ride->driver_id)->first();
                    // }
                    // if(!$chatList){
                    //     $chatListData = [
                    //         'to' => $ride->rider_id,
                    //         'from' => $ride->driver_id,
                    //     ];
                    //     $chatList = ChatList::create($chatListData);
                    // }
                    // $chatCount = ChatListMessage::where('chat_list_id',$chatList->id)->where('is_read',0)->count();
                    foreach ($ride->rideLocations as $location) {
                        if ($location->ride_order == 1) {
                            $destination = $location->latitude . ',' . $location->longitude;
                            break;
                        }
                    }
                    $user = User::find(Auth::user()->id);
                    $origin = $user->latitude . ',' . $user->longitude;
                    $res = findDistance($destination, $origin);
                    $ride->time_and_distance = sizeof($res['rows']) > 0 ? $res['rows'][0]['elements'] : null;
                    // $ride->total_messages = $chatCount;
                    $arr[] = $ride;
                } else {
                    RideRequestedTo::find($rRide->id)->delete();
                }
            }
            return apiresponse(true, 'Rides Found', $arr);

        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function test(){
        // $destination = "";
        // $arr = [
        //     0 =>[
        //             'lat' => '40.659569',
        //             'long' => '-73.933783',
        //     ],
        //     1 =>[
        //             'lat' =>' 40.729029',
        //             'long' => '-73.851524'

        //     ]
        // ];
        // foreach($arr as $dest){
        //     $destination .=$dest['lat'].",".$dest['long'];
        // }
        // return $destination;
        // return findDistance($destination,$origin);
        return RideLocation::with('children')->get();

    }

    public function getUserTimeZone(Request $request)
    {
        $upcomingTime = new \DateTime("+1 hour", new \DateTimeZone('Asia/Karachi'));
        dd($upcomingTime->format('Y-m-d H:i:s'));
    }
}
