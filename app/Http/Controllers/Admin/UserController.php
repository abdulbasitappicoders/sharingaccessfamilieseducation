<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User};

class UserController extends Controller
{
    public function index(){
        return "hy";
    }


    //Driver Module
    public function driver(){
        $users = User::where('role','driver')->simplePaginate(10);
        return view('driver.index',compact('users'));
    }

    public function driver_licence($id){
        $licence = User::where('id',$id)->with('licence','vehicle')->first();
        return view('driver.licence',compact('licence'));
    }

    public function driver_insurance($id){
        $insurance = User::where('id',$id)->with('riderInsurance')->first();
        return view('driver.insurance-details',compact('insurance'));
    }

    public function driver_status(Request $request){
        $user = User::find($request->id);
        if($user->status == 1){
            $user->status = 0;
        }else{
            $user->status = 1;
        }
        if($user->save()){
            return back()->with('message','Status changed');
        }
    }


    //Rider Module
    public function rider(){
        $users = User::where('role','rider')->simplePaginate(10);
        return view('rider.index',compact('users'));
    }

    public function rider_children($id){
        $childrens = User::where('id',$id)->with('childrens')->first();
        return view('rider.children',compact('childrens'));
    }

}



