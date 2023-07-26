@extends('layouts.master')

@section('content')
@if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
@endif
<div class="tab-content">
    <div class="tab-pane active" id="Staff-all">
        <div class="card">
            <div class="table-responsive">
                 <h4 class=" text-dark font-weight-bold col-12 mt-2">User Information</h4>
                <table class="table table-hover table-vcenter text-nowrap table-striped mb-0">
                    <tbody>
                        <tr>
                            <td colspan="2"><div class="font-15 text-center"> <img width="150" src="{{$user->image != null?asset('images/'.$user->image):asset('images/default.png')}}"> </div></td>
                        </tr>
                        <tr>
                            <td><div class="font-15">First Name</div></td>
                            <td><div class="font-15 font-weight-bold">{{$user->first_name}}</div></td>
                        </tr>
                        <tr>
                            <td><div class="font-15">Last Name</div></td>
                            <td><div class="font-15 font-weight-bold">{{$user->last_name}}</div></td>
                        </tr>
                        <tr>
                            <td width="50%"><div class="font-15">Email ID</div></td>
                            <td width="50%"><div class="font-15 font-weight-bold">{{$user->email}}</div></td>
                        </tr>
                        <tr>
                            <td width="50%"><div class="font-15">Contact Number</div></td>
{{--                            <td width="50%"><div class="font-15 font-weight-bold">{{$user->phone}}</div></td>--}}
                            <td width="50%"><div class="font-15 font-weight-bold">{{ formattedNumber(str_replace('+1','',$user->phone))??''}}</div></td>
                        </tr>
                        <tr>
                            <td width="50%"><div class="font-15">Gender</div></td>
                            <td width="50%"><div class="font-15 font-weight-bold">{{ucfirst($user->gender)}}</div></td>
                        </tr>
                        <tr>
                            <td width="50%"><div class="font-15">Address</div></td>
                            <td width="50%"><div class="font-15 font-weight-bold">{{$user->address}}</div></td>
                        </tr>
                        <tr>
                            <td width="50%"><div class="font-15">Status</div></td>
                            <td width="50%"><div class="font-15 font-weight-bold">{{$user->status == 1?"Active":"Deactive"}}</div></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
