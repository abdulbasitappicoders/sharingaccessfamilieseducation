<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Ride,User,RideType,RidePayment};

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data['total_riders'] = User::where('role','rider')->count();
        $data['total_drivers'] = User::where('role','driver')->count();
        $data['total_types'] = RideType::count();
        $data['total_rides'] = Ride::whereIn('status',['accepted','canceled','completed'])->count();
        $data['total_running_rides'] = Ride::where('status','accepted')->count();
        $data['total_canceled_rides'] = Ride::where('status','canceled')->count();
        $data['total_completed_rides'] = Ride::where('status','completed')->count();
        $data['revenue'] = Ride::where('status','completed')->count();
        return view('home',compact('data'));
    }
}
