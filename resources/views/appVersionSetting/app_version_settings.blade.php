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

    <form action="{{route('admin.update_app_version_settings')}}" method="POST">
        @csrf
        @foreach($versions as $version)
            <h3 class="text-center">{{ ucfirst($version->platform) }}</h3>
            <div class="mb-3">
                <input type="hidden" name="id{{ $version->id }}" value="{{ $version->id }}">
                <label for="exampleInputPassword1" class="form-label">Built Number</label>
                <input type="text" value="{{ $version->built_number }}" class="form-control"
                       name="built{{ $version->id }}" id="exampleInputUsername1" autocomplete="off"
                       placeholder="Built Number">

                <label for="exampleInputPassword1" class="form-label">App Version</label>
                <input type="text" value="{{ $version->app_version }}" class="form-control"
                       name="version{{ $version->id }}" id="exampleInputUsername1" autocomplete="off"
                       placeholder="App Version">
            </div>
        @endforeach
        <button type="submit" class="btn btn-dark">Change</button>
    </form>
@endsection
