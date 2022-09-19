<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Validator;
use Auth;
use Exception;


class ReviewController extends Controller
{
    public function review(Request $request){
        $validate = Validator::make($request->all(),[
            'review' => 'required',
            'rating' => 'required',
            'user_id' => 'required',
            'ride_id' => 'required',
        ]);
        if($validate->fails()){
            return apiresponse(false, implode("\n", $validate->errors()->all()));
        }
        try {
            $data = $request->all();
            $data['from'] = Auth::user()->id;
            $data['to'] = $request->user_id;
            $data['ride_id'] = $request->ride_id;
            if(Review::create($data)){
                return apiresponse(true, 'Thank you for your review' , $data);
            }else{
                return apiresponse(false,'Something went wrong');
            }
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }
    

    public function getRatingsAndReviews(Request $request){
        try {
            $reviews = Review::where('to',Auth::user()->id)->with('fromUser','toUser','ride','ride.rideLocations')->orderby('id','desc')->paginate(10);
            return apiresponse(true, 'Reviews', $reviews);
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }
}
