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
            <h4 class="text-dark font-weight-bold col-9">Rider</h4>
          </div>
            <div style="padding: 18px">
                <form action="{{ route('admin.rider') }}" method="GET">
                    <Label>Filter </Label>
                    <select class="form-select" name="rider_status" id="rider_status">
                        <option value=""  selected>Select Status</option>
                        <option value="1" @if(request()->query('rider_status') == '1') {{ 'selected' }} @endif>Active</option>
                        <option value="0" @if(request()->query('rider_status') == '0') {{ 'selected' }} @endif>Deactive</option>
                    </select>
                </form>
            </div>
          <table class="table table-hover table-vcenter text-nowrap table-striped mb-0" id="rider">
            <thead class="bg-dark">
                <tr>
                    <th class="text-white">First Name</th>
                        <th class="text-white">Last Name</th>
                        <th class="text-white">Email ID</th>
                        <th class="text-white">Contact Number</th>
                        <th class="text-white">State</th>
                        <th class="text-white">Status</th>
                        <th class="text-white">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{$user->first_name??'N/A'}}</td>
                    <td>
                      <div class="font-15">
                        {{$user->last_name??'N/A'}}
                      </div>
                    </td>
                    <td>{{$user->email??'N/A'}}</td>
{{--                    <td> {{ $user->phone }}</td>--}}
                    <td> @if($user->phone) {{ formattedNumber(str_replace('+1','',$user->phone))}} @else {{ 'N/A' }} @endif </td>
                    <td>{{$user->state??'N/A'}}</td>
                    <td>
                        @if($user->status == 1)
                        <button class="btn btn-success">Active</button>
                        @else
                        <button class="btn btn-danger">Deactive</button>
                        @endif
                    </td>
                        <td>
                            <a href="{{route('admin.rider_children',$user->id)}}" class="btn btn-icon btn-dark">Childern Info</a>
                            <a href="" onclick="UserStatus({{$user->id}})" data-toggle="modal" data-target="#exampleModal" class="btn btn-icon btn-secondary">Status</a>
                        </td>
                  </tr>
                @endforeach
            </tbody>
          </table>
          <br>
{{--          {{ $users->links() }}--}}
          <br>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel" style="color: #ffff">Are you sure you want to change status!</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-footer">
        <form action="{{route('admin.driver_status')}}" method="POST">
            @csrf
            <input type="hidden" name="id" id="id">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            <button type="submit" class="btn" style="background-color: red;color:#fff">Yes</button>
        </form>
        </div>
      </div>
    </div>
  </div>

<script>
    $(document).ready(function() {
        $('#rider').DataTable();
    })

    $(document).ready(function() {
        $('#rider_status').on('change', function() {
            this.form.submit();
        });
    })

    function UserStatus(id){
        var form_id = document.getElementById('id').value = id;
        // console.log("1--------->"+form_id);
        // var form_id = id;
        // console.log("2--------->"+form_id);


    }
</script>
@endsection
