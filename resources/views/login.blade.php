<!doctype html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}" type="image/x-icon">

<title>Safe Mobile App Admin Panel</title>

<!-- Bootstrap Core and vandor -->
<link rel="stylesheet" href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}" />

<!-- Core css -->
<link rel="stylesheet" href="{{asset('assets/css/style.min.css')}}"/>

</head>
<body class="font-muli theme-cyan gradient">
    

<div class="auth option2">
    <div class="auth_left">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <a class="header-brand" href="#"><img class="" src="{{asset('assets/images/logo.png')}}" alt="">
                    </a>
                    <div class="card-title mt-3 text-white">Login to your account</div>
                </div>
                <form method="POST" action="{{ route('login') }}">
                @csrf
                    <div class="form-group">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label"><a href="forgot-password.html" class="mb-2 text-white float-right small">I forgot password</a></label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        {{-- <label class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" />
                        <span class="text-white custom-control-label">Remember me</span>
                        </label> --}}
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-block" title="">Sign in</button>
                        <!-- <div class="text-muted mt-4">Don't have account yet? <a class="text-white" href="register.html">Sign up</a></div> -->
                    </div>
                </form>
            </div>
        </div>        
    </div>
</div>

<!-- Start Main project js, jQuery, Bootstrap -->
<script src="{{asset('assets/bundles/lib.vendor.bundle.js')}}"></script>

<!-- Start project main js  and page js -->
<script src="{{asset('assets/js/core.js')}}"></script>
</body>
</html>