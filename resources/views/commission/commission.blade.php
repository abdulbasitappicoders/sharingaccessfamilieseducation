@extends('layouts.master')
<style>
    .form-control{
        width: 92%;
    }
    .field_icon{
        position: relative;
        left: 93%;
        top: -29px;
    }
</style>
@section('content')
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
    <form action="{{route('admin.update_commission')}}" method="POST" >
    @csrf
        <input type="hidden" value="{{$commission->id}}" name="id">
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Commission</label>
            <input type="text" value="{{$commission->commission}}" class="form-control" name="commission" id="exampleInputUsername1" autocomplete="off" placeholder="Commission">
        </div>
        <button type="submit" class="btn btn-dark">Change</button>
    </form>
@endsection