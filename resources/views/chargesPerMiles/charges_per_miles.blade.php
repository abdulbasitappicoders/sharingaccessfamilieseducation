@extends('layouts.master')
<style>
    .form-control {
        width: 92%;
    }

    .field_icon {
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
    <form action="{{route('admin.update_charges_per_miles')}}" method="POST">
        @csrf
        <input type="hidden" value="{{$charge->id}}" name="id">
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Charges Per Mile</label>
            <input type="text" value="{{$charge->charges_per_mile}}" class="form-control" name="charges_per_mile"
                   id="exampleInputUsername1" autocomplete="off" placeholder="Charges Per Mile">
        </div>
        <button type="submit" class="btn btn-dark">Change</button>
    </form>
@endsection
