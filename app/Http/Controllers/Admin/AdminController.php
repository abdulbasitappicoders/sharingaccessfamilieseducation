<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class AdminController extends Controller
{
    public function index()
    {
        try {
            return view('changePassword.change_password');
        } catch (Exception $e) {
            return  $e->getMessage();
        }
    }

    public function update(Request $request) 
    {
         $validator = Validator::make($request->all(), [
            'old_password' => [
                'required', function ($attribute, $value, $fail) {
                    if (!Hash::check($value, auth()->user()->password)) {
                        $fail('Old Password didn\'t match');
                    }
                },
            ],
            'new_password' => ['required', 'min:8'],
            'confirm_new_password' => 'required|same:new_password'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        try {
            $user = User::find(auth()->user()->id);
            $user->password = Hash::make($request->new_password);
            $user->save();
            return redirect()->route('admin.change_password')->with('success', __('Password has been updated successfully!'));
        } catch (\Exception $exception) {
            return redirect()->route('admin.change_password')->with('error', $exception->getMessage());
        }
    }
}
