@extends('layouts.master')

@section('style')
    <style>
        a:visited {
            /*color: pink;*/
        }
    </style>
@endsection
@section('content')
    @if(Session::has('message'))
        <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
    @endif

    <div class="tab-content">
        <div class="tab-pane active" id="Staff-all">
            <div class="card">
                <div class="table-responsive" style="overflow-x: hidden">
                    <div class="all-users row">
                        <h4 class="text-dark font-weight-bold col-9">Notifications</h4>
                    </div>
                    @forelse($queries as $notification)
{{--                            {{ dd($notification) }}--}}
{{--                            @if($notification->is_read_query) @endif--}}
                            <a href="{{ route('admin.queries') }}" class="mobile_app-query" data-id="{{ $notification->id }}" style="color: @if($notification->is_read_query == 1){{ '#551A8B' }} @endif">
                                <div class="notification-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="notification-title">{{ $notification->user->username??'N/A' }}</p>
                                            <p class="notification-purpose">{{ $notification->type??'N/A' }}</p>
                                        </div>
                                        <p class="notification-date">{{ formattedDate($notification->created_at) }}</p>
                                    </div>
                                    <p class="notification-desc">
    {{--                                    {{ $notification->message??"" }}--}}
                                        {{ (strlen($notification->message) > 100)?substr($notification->message, 0, 100)." ...
                                        ":$notification->message??'N/A' }}
                                    </p>
                                </div>
                            </a>
                    @empty
                    @endforelse

                    {{--<table class="table table-hover table-vcenter text-nowrap table-striped mb-0">
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

                        </tbody>
                    </table>--}}
                    <br>
{{--                    {{ $queries->links() }}--}}
                    <br>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).on('click','.mobile_app-query',function () {
            var id = $(this).data('id');
            var url = '{{ route("admin.read-query", ":id") }}';
            url = url.replace(':id', id);
            $.ajax({
                type: 'GET', //THIS NEEDS TO BE GET
                url: url,
                dataType: 'json',
                success: function (data) {
                    // console.log(data);
                },error:function(){
                    // console.log(data);
                }
            });

            // alert(id);
        });
    </script>
@endsection
