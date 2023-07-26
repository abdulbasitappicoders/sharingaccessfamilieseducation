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
            <h4 class="text-dark font-weight-bold col-9">Payments</h4>
          </div>
          <table class="table table-hover table-vcenter text-nowrap table-striped mb-0" id="payments">
            <thead class="bg-dark">
                <tr>
                    <th class="text-white">Ride ID</th>
                    <th class="text-white">Ride Type</th>
                    <th class="text-white">Driver Name</th>
                    <th class="text-white">Rider Name</th>
                    <th class="text-white">Ride Date</th>
                    <th class="text-white">Ride Time</th>
                    <th class="text-white">Driver Amount</th>
                    <th class="text-white">Rider amount</th>
                    <th class="text-white">Commission</th>
                    <th class="text-white">Commission Percentage</th>
                    <th class="text-white">Is Paid</th>
                    <th class="text-white">Total Amount</th>
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
                    <td>{{$payment->driver_ammount ? "$".$payment->driver_ammount:"N/A"}}</td>
                    <td>{{$payment->rider_amount ? "$".$payment->rider_amount:"N/A"}}</td>
                    <td>{{$payment->commission ? "$".$payment->commission:"N/A"}}</td>
                    <td>{{$payment->commission_percentage ? $payment->commission_percentage."%":"N/A"}}</td>
                    <td>{{$payment->is_paid ? "Yes":"No"}}</td>
                    <td>${{$payment->total_amount}}</td>
                </tr>
                @endforeach
            </tbody>
          </table>
          <br>
{{--          {{ $payments->links() }}--}}
          <br>
        </div>
      </div>
    </div>
  </div>

<script>
    $(document).ready(function() {
        $('#payments').DataTable();
    });
</script>

@endsection
