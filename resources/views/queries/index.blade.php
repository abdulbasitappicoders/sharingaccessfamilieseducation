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
                </div>
                <table class="table table-hover table-vcenter text-nowrap table-striped mb-0">
                    <thead class="bg-dark">
                        <tr>
                            <th class="text-white">S.No.</th>
                            <th class="text-white">Query Type</th>
                            <th class="text-white">Message Box</th>
                            <th class="text-white">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($queries as $query)
                        <tr>
                            <td>{{$query->id}}</td>
                            <td>
                            <div class="font-15">
                                {{$query->type}}
                            </div>
                            </td>
                            <td> {{$query->message}}</td>
                            <td><a href="{{route('admin.query_user', Crypt::encryptString($query->user->id))}}" class="btn btn-icon btn-dark">User Info</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
                {{ $queries->links() }}
                <br>
            </div>
        </div>
    </div>
</div>
@endsection