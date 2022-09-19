<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Notification};
use Auth;
use Exception;


class NotificationController extends Controller
{
    public function index(){
        try {
            $notifications = Notification::where('reciever_id',Auth::user()->id)->with('sender','reciever')->paginate(10);
            return apiresponse(true,"Notificatons",$notifications);
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
        
    }
}
