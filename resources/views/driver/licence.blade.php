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
                        <h4 class=" text-dark font-weight-bold col-12">Vehicle Information</h4>
                    </div>
                    <thead class="bg-dark">
                        <tr>
                            <th class="text-white">Vehicle Brand</th>
                            <th class="text-white">Vehicle Model</th>
                            <th class="text-white">Year</th>
                            <th class="text-white">Color</th>
                            <th class="text-white">Lincense Plate #</th>
                            <th class="text-white">Booking Type</th>
                            <th class="text-white">Nanem on Card</th>
                            <th class="text-white">Drivining Lincense #</th>
                            <th class="text-white">Expiry</th>
                            <th class="text-white">Lincense Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="font-15">{{$licence->licence?$licence->licence->vehicle_brand:"N/A"}}</div>
                            </td>
                            <td>
                                <div class="font-15">{{$licence->licence?$licence->licence->model:"N/A"}}</div>
                            </td>
                            <td>{{$licence->vehicle?$licence->vehicle->year:"N/A"}}</td>
                            <td>{{$licence->vehicle?$licence->vehicle->color:"N/A"}}</td>
                            <td>{{$licence->vehicle?$licence->vehicle->license_plate:"N/A"}}</td>
                            <td>{{$licence->vehicle?$licence->vehicle->booking_type:"N/A"}}</td>
                            <td>{{$licence->licence?$licence->licence->name_on_card:"N/A"}}</td>
                            <td>{{$licence->licence?$licence->licence->license_plate_number:"N/A"}}</td>
                            <td>{{$licence->licence?$licence->licence->expiry:"N/A"}}</td>
                            <td>
                                <?php
                                    $front = $licence->licence?$licence->licence->card_front:'null';
                                    $back = $licence->licence?$licence->licence->card_back:null;
                                ?>
                                <a class="pop" href="#"> <img width="70" src="{{asset('images/'.$front)}}"></a> 
                                <a class="pop" href="#"> <img width="70" src="{{asset('images/'.$back)}}"></a>
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal"><span class="sr-only">Close</span></button>
                <img src="" class="imagepreview" style="width: 100%;" >
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
            $('.pop').on('click', function() {
                $('.imagepreview').attr('src', $(this).find('img').attr('src'));
                $('#imagemodal').modal('show');   
            });     
        });
</script>
@endsection