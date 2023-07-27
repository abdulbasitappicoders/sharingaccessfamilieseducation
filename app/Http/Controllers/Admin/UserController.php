<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, UserFvc};

class UserController extends Controller
{
    public function index(){
        return "hy";
    }


    //Driver Module
    public function driver(Request $request){
        if ($request->driver_status == "1" || $request->driver_status == "0"){
//            dd($request->driver_status);
            $users = User::where('role','driver')->where("status",'like', '%' . $request->driver_status . '%')->orderBy('id','DESC')->get();
            return view('driver.index',compact('users'));
        }
//        $users = User::where('role','driver')->orderBy('id','DESC')->simplePaginate(10);
        $users = User::where('role','driver')->orderBy('id','DESC')->get();
        return view('driver.index',compact('users'));
    }

    public function driver_licence($id){
        $licence = User::where('id',$id)->with('licence','vehicle')->first();
//        dd($licence);
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


    public function driver_fvc($id)
    {
        $fvc = UserFvc::where('user_id',$id)->first();
        return view('driver.fvc', compact('fvc'));
    }


    //Rider Module
    public function rider(Request $request){
        if ($request->rider_status == "1" || $request->rider_status == "0"){
//            dd($request->driver_status);
            $users = User::where('role','rider')->where("status",'like', '%' . $request->rider_status . '%')->orderBy('id','DESC')->get();
            return view('rider.index',compact('users'));
        }
//        $users = User::where('role','rider')->simplePaginate(10);
        $users = User::where('role','rider')->get();
        return view('rider.index',compact('users'));
    }

    public function rider_children($id){
        $childrens = User::where('id',$id)->with('childrens')->first();
//        dd($childrens);
        return view('rider.children',compact('childrens'));
    }

}



