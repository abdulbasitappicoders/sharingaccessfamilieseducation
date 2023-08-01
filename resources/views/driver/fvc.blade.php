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
                        <h4 class=" text-dark font-weight-bold col-12">IVP Information</h4>
                    </div>
                    <thead class="bg-dark">
                        <tr>
                            <th class="text-white">Name</th>
                            <th class="text-white">IVP Number</th>
                            <th class="text-white">Expiry</th>
                            <th class="text-white">IVP Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="font-15">{{isset($fvc->name)?$fvc->name:"N/A"}}</div>
                            </td>
                            <td>
                                <div class="font-15">{{isset($fvc->fvc_number)?$fvc->fvc_number:"N/A"}}</div>
                            </td>
                            <td>{{isset($fvc->expiry)?$fvc->expiry:"N/A"}}</td>
                            <td>
                                <?php
                                    $image = isset($fvc->image)?$fvc->image:'null';
                                ?>
                                @if(isset($fvc->image))
                                <a class="pop" href="#"> <img width="70" src="{{asset('images/'.$image)}}">
                                    @else
                                    {{ 'N/A' }}
                                @endif
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
