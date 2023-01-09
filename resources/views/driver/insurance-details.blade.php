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
                        <h4 class="text-dark font-weight-bold col-9">Insurance Details</h4>
                    </div>
                    <thead class="bg-dark">
                        <tr>
                            <th class="text-white">Full Name</th>
                            <th class="text-white">Insurance Number</th>
                            <th class="text-white">Expiry</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="font-15">{{isset($insurance->riderInsurance[0])?$insurance->riderInsurance[0]->name:"N/A"}}</div>
                            </td>
                            <td>
                                <div class="font-15">{{isset($insurance->riderInsurance[0])?$insurance->riderInsurance[0]->number:"N/A"}}</div>
                            </td>
                            <td>{{isset($insurance->riderInsurance[0])?$insurance->riderInsurance[0]->exp_date:"N/A"}}/td>
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