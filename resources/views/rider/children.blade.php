@extends('layouts.master')

@section('content')
@if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
@endif
<div class="tab-content">
    <div class="tab-pane active" id="Staff-all">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover table-vcenter text-nowrap table-striped mb-0">
                    <div class="all-users row">
                        <h4 class=" text-dark font-weight-bold col-11">Children Information</h4>
                        <a href="{{route('admin.rider')}}" class="btn btn-dark col-1">Back</a>
                    </div>
                    <thead class="bg-dark">
                        <tr>
                            <th class="text-white">First Name</th>
                            <th class="text-white">Last Name</th>
                            <th class="text-white">Grade</th>
                            <th class="text-white">Age</th>
                            <th class="text-white">School Name</th>
                            <th class="text-white">Bank Account</th>
                            <th class="text-white">Payment Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($childrens->childrens as $children)
                        <tr>
                            <td>
                                <div class="font-15">{{$children?$children->first_name:"N/A"}}</div>
                            </td>
                            <td>
                                <div class="font-15">{{$children?$children->last_name:"N/A"}}</div>
                            </td>
                            <td>{{isset($children)?$children->grade:"N/A"}}</td>
                            <td>{{isset($children)?$children->age:"N/A"}}</td>
                            <td>{{isset($children)?$children->school_name:"N/A"}}</td>
                            <td>{{isset($children->number->type)?$children->number->type:"N/A"}}</td>
                            <td>{{isset($children->payment_method)?$children->payment_method->type:"N/A"}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
