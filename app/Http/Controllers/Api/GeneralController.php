<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppEmergencyNumber;
use App\Models\AppVersionSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class GeneralController extends Controller
{
    public function getEmergencyNumber()
    {
        $getEmergencyNumber = AppEmergencyNumber::first();
        if($getEmergencyNumber){
            return apiresponse(true, __('Emergency number found'), ['data' => $getEmergencyNumber->emergency_number]);
        }
        return apiresponse(true, __('Emergency number not found'), ['data' => null]);
    }

    public function checkAppVersion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appVersion'    => 'required',
            'platform'      => 'required',
        ]);
        if ($validator->fails()) return apiresponse(false, implode("\n", $validator->errors()->all()));
        $appVersion = AppVersionSetting::where('app_version', $request->appVersion)->where('platform', $request->platform)->first();
        if($appVersion) {
            return apiresponse(true, __('App version matched'), ['data' => $appVersion->app_version]);
        }
        return apiresponse(true, __('App version does not matched'), ['data' => null]);
    }
}
