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

    <form action="{{route('admin.update_password')}}" method="POST" >
    @csrf
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Old Password</label>
            <input type="text" class="form-control" name="old_password" id="exampleInputUsername1" autocomplete="off" placeholder="Old Password">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">New Password</label>
            <input type="password" class="form-control" name="new_password" id="password" autocomplete="off" placeholder="New Password">
            <span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-password"></span>
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" name="confirm_new_password" id="confirm_password" autocomplete="off" placeholder="Confirm Password">
            <span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-c-password"></span>
        </div>
        <button type="submit" class="btn btn-dark">Change</button>
    </form>

    <script>
        $(document).on('click', '.toggle-password', function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $("#password");
            input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
        })
        $(document).on('click', '.toggle-c-password', function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $("#confirm_password");
            input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
        })
</script>
@endsection