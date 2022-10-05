@extends('layouts.master')

@section('content')
@if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
@endif
<div class="tab-content">
    <div class="tab-pane active" id="Staff-all">
      <div class="card">
        <div class="table-responsive">
          <div class="all-users row">
            <h4 class="text-dark font-weight-bold col-9">Driver</h4>
          </div>
          <table class="table table-hover table-vcenter text-nowrap table-striped mb-0">
            <thead class="bg-dark">
                <tr>
                    <th class="text-white">Ride ID</th>
                    <th class="text-white">Ride Type</th>
                    <th class="text-white">Driver Name</th>
                    <th class="text-white">Rider Name</th>
                    <th class="text-white">Ride Date</th>
                    <th class="text-white">Ride Time</th>
                    <th class="text-white">Total Amount</th>
                    <th class="text-white">Payment Method</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td>{{$payment->ride_id}}</td>
                    <td>
                        <div class="font-15">
                        @if($payment->ride->type == 'normal')
                        Single
                        @else
                        Child
                        @endif
                        </div>
                    </td>
                    <td>{{$payment->driver ? $payment->driver->username:"N/A"}}</td>
                    <td>{{$payment->rider ? $payment->rider->username:"N/A"}}</td>
                    <td>{{date('d-m-Y', strtotime($payment->created_at))}}</td>
                    <td>{{date('g:i A', strtotime($payment->created_at))}}</td>
                    <td>${{$payment->total_amount}}</td>
                    <td>{{$payment->type}}</td>
                </tr>
                @endforeach
            </tbody>
          </table>
          <br>
          {{ $payments->links() }}
          <br>
        </div>
      </div>
    </div>
  </div>

@endsection