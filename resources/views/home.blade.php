@extends('layouts.master')
<style>
.text-uppercase{
    text-align: center;
    margin-top: 20px;
    color: #fff;
    margin-bottom: 0px;
    font-weight: bold;
}
.display-1{
    font-size: 3.5rem;
    font-weight: 900 !important;
    line-height: 1.2;
    color: #fff;
    text-align: center;
    margin-bottom: 15px;
}
</style>
@section('content')
    <div class="row mb-3">
        <div class="col-xl-3 col-lg-6 riders">
            <div class="card card-inverse card-success">
                <div class="card-block bg-success">
                    <div class="rotate">
                        <i class="fa fa-user fa-5x"></i>
                    </div>
                <h6 class="text-uppercase">Total Riders</h6>
                <h1 class="display-1">{{$data['total_riders']}}</h1>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 riders">
            <div class="card card-inverse card-danger">
                <div class="card-block bg-danger">
                    <div class="rotate">
                        <i class="fa fa-list fa-4x"></i>
                    </div>
                    <h6 class="text-uppercase">Total Drivers</h6>
                    <h1 class="display-1">{{$data['total_drivers']}}</h1>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 riders">
            <div class="card card-inverse card-info">
                <div class="card-block bg-info">
                    <div class="rotate">
                        <i class="fa fa-car fa-5x"></i>
                    </div>
                    <h6 class="text-uppercase">Vehicle Type</h6>
                    <h1 class="display-1">{{$data['total_types']}}</h1>
                </div>
            </div>
        </div>
        {{-- <div class="col-xl-3 col-lg-6 riders">
            <div class="card card-inverse card-warning">
                <div class="card-block bg-warning">
                    <div class="rotate">
                        <i class="fa fa-dollar-sign fa-5x"></i>
                    </div>
                    <h6 class="text-uppercase">Revenue</h6>
                    <h1 class="display-1">$9.84</h1>
                </div>
            </div>
        </div> --}}
        <div class="col-xl-3 col-lg-6 riders">
            <div class="card card-inverse card-success">
                <div class="card-block bg-warning">
                    <div class="rotate">
                        <i class="fa fa-taxi fa-5x"></i>
                    </div>
                    <h6 class="text-uppercase">Total No of Ride</h6>
                    <h1 class="display-1">{{$data['total_rides']}}</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
   
    <div class="col-xl-3 col-lg-6 riders">
        <div class="card card-inverse card-info">
            <div class="card-block bg-info">
                <div class="rotate">
                    <i class="fa fa-user fa-5x"></i>
                </div>
                <h6 class="text-uppercase">Running Ride</h6>
                <h1 class="display-1">{{$data['total_running_rides']}}</h1>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 riders">
        <div class="card card-inverse card-danger">
            <div class="card-block bg-danger">
                <div class="rotate">
                    <i class="fa fa-window-close fa-4x"></i>
                </div>
                <h6 class="text-uppercase">Cancelled Ride</h6>
                <h1 class="display-1">{{$data['total_canceled_rides']}}</h1>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 riders">
        <div class="card card-inverse card-warning">
            <div class="card-block bg-success">
                <div class="rotate">
                    <i class="fa fa-check-square fa-5x"></i>
                </div>
                <h6 class="text-uppercase">Completed Ride</h6>
                <h1 class="display-1">{{$data['total_completed_rides']}}</h1>
            </div>
        </div>
    </div>
    </div>
    <h1><strong>Driver Statistics</strong></h1>
    <div>
        <canvas id="myChart" height="100" ></canvas>
    </div>
@endsection
