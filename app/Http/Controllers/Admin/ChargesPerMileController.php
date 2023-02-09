<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChargesPerMile;
use App\Models\RideType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ChargesPerMileController extends Controller
{
    public function index()
    {
        $charge = ChargesPerMile::first();
        return view('chargesPerMiles.charges_per_miles', compact('charge'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'charges_per_mile' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        try {
            $radius = ChargesPerMile::find($request->id);
            $radius->charges_per_mile = $request->charges_per_mile;
            $radius->save();
            foreach (RideType::all() as $rideType) {
                $rideType::where('id', $rideType->id)->update(['price' => $request->charges_per_mile]);
            }

            return redirect()->route('admin.charges_per_miles')->with('success', __('Charges per mile has been updated successfully!'));
        } catch (\Exception $exception) {
            return redirect()->route('admin.charges_per_miles')->with('error', $exception->getMessage());
        }
    }
}
