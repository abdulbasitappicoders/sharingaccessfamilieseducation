<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CommissonController extends Controller
{
    public function index()
    {
        $commission = Commission::first();
        return view('commission.commission', compact('commission'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'commission' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        try {
            $commission = Commission::find($request->id);
            $commission->commission = $request->commission;
            $commission->save();
            return redirect()->route('admin.commission')->with('success', __('Commission has been updated successfully!'));
        } catch (\Exception $exception) {
            return redirect()->route('admin.commission')->with('error', $exception->getMessage());
        }
    }
}
