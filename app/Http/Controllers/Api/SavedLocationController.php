<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Models\SavedLocations;

class SavedLocationController extends Controller
{
    public function index(){
        try {
            $locations = SavedLocations::where('user_id',Auth::user()->id)->limit(10)->orderByDesc('id')->get();
            return apiresponse(true,'Locations found',$locations);
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'longitude' => 'required',
            'latitude' => 'required',
            'name' => 'required',
            'address' => 'required',
            'place_name' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }
        try {
            $data = $request->all();
            $data['user_id'] = Auth::user()->id;
            $res = SavedLocations::create($data);
            if($res){
                return apiresponse(true,'Location saved',$res);
            }else{
                return apiresponse(false,'Something went wrong');
            }
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }
}
