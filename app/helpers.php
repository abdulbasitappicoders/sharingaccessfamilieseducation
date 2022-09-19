<?php
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\Notification as ModelNotification;

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
    function files_upload($files,$name)
    {
        $type = gettype($files);
        if($type == 'object'){
            $fileName = $name.time() . '.' . $files->getClientOriginalExtension();
            $check = $files->move(public_path("images"), $fileName);
            if($check){
                return $fileName;
            }else{
                return false;
            }
        }else{
            foreach($files as $file){
                $fileName = $name.time() . '.' . $file->getClientOriginalExtension();
                $check = $file->move(public_path("images"), $fileName);
            }
            if($check){
                return true;
            }else{
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
        $link = 'https://maps.googleapis.com/maps/api/distancematrix/json?destinations='.$destination.'&origins='.$origin.'&key=AIzaSyBBVMEPDktEjcindc7_NjCpFWsSWVspyKI';
        $res = json_decode(file_get_contents($link), true);
        return $res;
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

?>