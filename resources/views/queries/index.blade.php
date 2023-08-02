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
                    <h4 class="text-dark font-weight-bold col-9">Queries</h4>
                    <div class="col-md-12 mt-3">
                        @if(Session::has('success'))
                            <div class="alert alert-success alert-dismissible" style="text-align:center;">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
                                <strong>Success!</strong>
                                <?= htmlentities(Session::get('success'))?>
                            </div>
                        @endif
                        @if(Session::has('error'))
                            <div class="alert alert-danger alert-dismissible" style="text-align:center;">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
                                <strong>Error!</strong>
                                <?= htmlentities(Session::get('error'))?>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible" style="text-align:center;">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
                                <p><strong>Whoops!</strong> Please correct errors and try again!</p>
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <table class="table table-hover table-vcenter text-nowrap table-striped mb-0" id="contact_us_query">
                    <thead class="bg-dark">
                        <tr>
                            <th class="text-white">S.No.</th>
                            <th class="text-white">Email</th>
                            <th class="text-white">Query Type</th>
                            <th class="text-white">Message Box</th>
                            <th class="text-white">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i =1; @endphp
                        @foreach ($queries as $query)
                        <tr class="mobile_app-query" data-id="{{ $query->id }}">
                            <td>{{ $i++ }}</td>
                            <td>{{ $query->user->email??"N/A" }}</td>
                            <td>
                            <div class="font-15">
                                {{isset($query->type)?$query->type:"N/A"}}
                            </div>
                            </td>
{{--                            <td> {{isset($query->message)?$query->message:"N/A"}}</td>--}}
                            <td>
                                <p data-title="{{ $query->type }}" data-id="{{ $query->message }}"
                                   id="read" data-toggle="modal"
                                   data-target="#contact_us">
                                    {{ (strlen($query->message) > 20)?substr($query->message, 0, 20)." ... Read More
                                    ":$query->message??'N/A' }}
                                </p>
                            </td>
                            <td>
                                <a href="{{route('admin.query_user', Crypt::encryptString(isset($query->user->id)?$query->user->id:1))}}" class="btn btn-icon btn-dark">User Info</a>
                                <a href="mailto:{{$query->user->email}}" class="btn btn-icon btn-dark">Reply</a>
                                <a href="{{route('admin.delete-query', $query->id)}}"
                                   class="btn btn-icon btn-dark">Delete</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
{{--                {{ $queries->links() }}--}}
                <br>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="contact_us" tabindex="-1" aria-labelledby="contact_us" aria-hidden="true">
    <div class="modal-dialog ">
        {{-- modal-dialog-centered--}}
        <div class="modal-content">
            <div class="modal-header newfqheading " >
                <h5 class="modal-title" id="contact_usLabel">Message </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body newfqbody" style="padding: 3% !important;">
                <p id="read_more">
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on("click", '#read', function () {
        // alert('work')
        var str = $(this).data('id').length;
        if (str >= 20) {
            $("#read").css({"cursor": "pointer"});
        }
        $("#read_more").text($(this).data('id'));
        $("#contact_usLabel").text($(this).data('title'));
    });

    $(document).on('click','.mobile_app-query',function () {
        var id = $(this).data('id');
        var url = '{{ route("admin.read-query", ":id") }}';
        url = url.replace(':id', id);
        $.ajax({
            type: 'GET', //THIS NEEDS TO BE GET
            url: url,
            dataType: 'json',
            success: function (data) {
                console.log(data);
            },error:function(){
                console.log(data);
            }
        });

        // alert(id);
    });
    /*$(function(){
        $('.row').click(function(){
            var $row = $(this).index();
        });
        $('.row .link').click(function(e){
            e.stopPropagation();
        });
    });*/
    $(document).ready(function() {
        $('#contact_us_query').DataTable();
    });
</script>
@endsection
