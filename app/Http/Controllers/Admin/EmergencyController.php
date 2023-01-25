<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppEmergencyNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmergencyController extends Controller
{
    public function index()
    {
        $number = AppEmergencyNumber::first();
        return view('emergency.emergency', compact('number'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'number' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        try {
            $radius = AppEmergencyNumber::find($request->id);
            $radius->emergency_number = $request->number;
            $radius->save();
            return redirect()->route('admin.emergency')->with('success', __('Emergency number has been updated successfully!'));
        } catch (\Exception $exception) {
            return redirect()->route('admin.emergency')->with('error', $exception->getMessage());
        }
    }
}
