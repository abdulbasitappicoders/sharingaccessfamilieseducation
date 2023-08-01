<?php

namespace App\Http\Controllers\Api;

use App\Events\ContactQuery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use App\Models\WebContactUs;
use Illuminate\Support\Facades\Validator;
use Auth;
use Exception;
use Pusher\Pusher;


class ContactUsController extends Controller
{
    public function index()
    {

    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'type' => 'required',
            'message' => 'required',
        ]);
        if ($validate->fails()) {
            return apiresponse(false, implode('\n', $validate->errors()->all()));
        }
        try {

            $data = $request->all();
            $data['user_id'] = Auth::user()->id;
            if (ContactUs::create($data)) {;
                event(new ContactQuery(queryCount()));

                return apiresponse(true, 'Request has been sent successful', 1);
            } else {
                return apiresponse(false, 'something went wrong');
            }
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function storeWebContactInquiry(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        if ($validate->fails()) {
            return apiresponse(false, implode('\n', $validate->errors()->all()));
        }

        try {
            $data = $request->all();
            WebContactUs::create($data);
            return apiresponse(true, 'Request has been sent successful');
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

}
