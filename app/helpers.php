<?php

use App\Models\ChargesPerMile;
use App\Models\Commission;
use App\Models\ContactUs;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\Notification as ModelNotification;
use App\Models\RadiusOfSearch;

if (!function_exists('apiresponse')) {
    /**
     * @param boolean $status
     * @param string $msg
     * @param array|null $data
     * @param integer $http_status
     * @return \Illuminate\Http\JsonResponse
     */
    function apiresponse($status, $msg, $data = null, $http_status = 200)
    {
        return response()->json(['success' => $status, 'message' => $msg, 'data' => $data], $http_status);
    }
}


if (!function_exists('files_upload')) {
    /**
     * @param boolean $status
     * @param string $msg
     * @param array|null $data
     * @param integer $http_status
     * @return \Illuminate\Http\JsonResponse
     */
    function files_upload($files, $name)
    {
        $type = gettype($files);
        if ($type == 'object') {
            $fileName = $name . time() . '.' . $files->getClientOriginalExtension();
            $check = $files->move(public_path("images"), $fileName);
            if ($check) {
                return $fileName;
            } else {
                return false;
            }
        } else {
            foreach ($files as $file) {
                $fileName = $name . time() . '.' . $file->getClientOriginalExtension();
                $check = $file->move(public_path("images"), $fileName);
            }
            if ($check) {
                return true;
            } else {
                return false;
            }
        }
    }
}

if(!function_exists('saveNotification')){
    /**
     * Save Notification to Database
     * @param string $title
     * @param string $body
     * @param string $type
     * @param boolean $is_read
     * @param integer $sender_id
     * @param integer $reciever_id
     */
    function saveNotification($title,$body,$type,$sender_id,$reciever_id){
        try {
            $data = [
                'title' => $title,
                'body' => $body,
                'type' => $type,
                'sender_id' => $sender_id,
                'reciever_id' => $reciever_id,
            ];
            if(ModelNotification::create($data)){
                return true;
            }else{
                return false;
            }
        } catch (Exception $e) {
            return $e;
        }
    }
}

if (!function_exists('findDistance')) {
    /**
     *
     * Convert Address to lat lng
     * @param string $address
     * @return array|boolean
     */
    function findDistance($destination,$origin)
    {
        $link = 'https://maps.googleapis.com/maps/api/distancematrix/json?destinations='.$destination.'&origins='.$origin.'&key=AIzaSyCRYfRwttAsnD0vflBvUQ4lFiGytXnInz4';
        $res = json_decode(file_get_contents($link), true);
        return $res;
    }
}

if (!function_exists('commission')) {
    /**
     *
     * Convert Address to lat lng
     * @param string $address
     * @return array|boolean
     */
    function commission()
    {
        $commission = Commission::first();
        return $commission->commission;
    }
}

if (!function_exists('radius_of_searches')) {
    /**
     *
     * Convert Address to lat lng
     * @param string $address
     * @return array|boolean
     */
    function radius_of_searches()
    {
        $radius_of_searches = RadiusOfSearch::first();
        return $radius_of_searches->miles;
    }
}

if (!function_exists('charges_per_mile')) {
    /**
     *
     * Convert Address to lat lng
     * @param string $address
     * @return array|boolean
     */
    function charges_per_mile()
    {
        $charges_per_mile = ChargesPerMile::first();
        return $charges_per_mile->charges_per_mile;
    }
}

if (!function_exists('SendNotification')) {
    /**
     * Send Notification to Device
     * @param string $device_id
     * @param string $title
     * @param string $body
     * @param null $data
     */
    function SendNotification($device_id, $title, $body, $data = null)
    {
        try {
            if ($device_id) {
                // if(file_exists('firebase.json')){
                //     return "ff";
                // }else{
                //     return "cvc";
                // }
                $factor = (new Factory())->withServiceAccount('firebase.json');
                $messaging = $factor->createMessaging();
                $message = CloudMessage::withTarget('token', $device_id)
                    ->withNotification(Notification::create($title, $body));
                if ($data) {
                    $message = CloudMessage::withTarget('token', $device_id)
                    ->withNotification(Notification::create($title, $body))
                    ->withData($data);
                }
                 $messaging->send($message);
            }else{
                return "not found";
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('formattedDate')) {

    /**
     * @param $date
     * @return string|null
     */
    function formattedDate($date)
    {
        if (!$date) {
            return null;
        }
        return date('m-d-Y', strtotime($date));
    }
}

if (!function_exists('getSanitizeString')) {

    function getSanitizeString($str)
    {
        $str = preg_replace('/[^A-Za-z0-9. -]/', '', $str);
        $str = preg_replace('#[^\pL\pN/-]+#', '', $str);
        $str = preg_replace('/\s+/', '', $str);

        return strtolower($str);
    }
}

if (!function_exists('formattedNumber')) {

    function formattedNumber($number)
    {
        return $number != null ? "+1 (" . substr($number, 0, 3) . ") " . substr($number, 3, 3) . "-" . substr($number, 6) : '-';
    }
}

if (!function_exists('queryCount')) {

    function queryCount()
    {
        return ContactUs::where('is_read_query',null)->count();
    }
}

?>
