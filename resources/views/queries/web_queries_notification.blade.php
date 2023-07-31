@extends('layouts.master')

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
                        <div class="notification-card">
                            <a href="{{ route('admin.queries') }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="notification-title">{{ $notification->type??'' }}</p>
                                    <p class="notification-date">{{ formattedDate($notification->created_at) }}</p>
                                </div>
                                <p class="notification-desc">
                                    {{ $notification->message??"" }}
                                </p>
                            </a>
                        </div>
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

@endsection
