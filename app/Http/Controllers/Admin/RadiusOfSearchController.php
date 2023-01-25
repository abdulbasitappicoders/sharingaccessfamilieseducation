<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RadiusOfSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class RadiusOfSearchController extends Controller
{
    public function index()
    {
        $radius = RadiusOfSearch::first();
        return view('radiusOfSearch.radius_of_search', compact('radius'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'miles' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        try {
            $radius = RadiusOfSearch::find($request->id);
            $radius->miles = $request->miles;
            $radius->save();
            return redirect()->route('admin.radius_of_search')->with('success', __('Miles has been updated successfully!'));
        } catch (\Exception $exception) {
            return redirect()->route('admin.radius_of_search')->with('error', $exception->getMessage());
        }
    }
}
