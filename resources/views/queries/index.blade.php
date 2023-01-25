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
                            <td>{{isset($query->id)?$query->id:"N/A"}}</td>
                            <td>
                            <div class="font-15">
                                {{isset($query->type)?$query->type:"N/A"}}
                            </div>
                            </td>
                            <td> {{isset($query->message)?$query->message:"N/A"}}</td>
                            <td><a href="{{route('admin.query_user', Crypt::encryptString(isset($query->user->id)?$query->user->id:1))}}" class="btn btn-icon btn-dark">User Info</a></td>
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