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
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/summernote/dist/summernote.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/plugins/sweetalert/sweetalert.css')}}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <!-- Core css -->
    <link rel="stylesheet" href="{{asset('assets/css/style.min.css')}}"/>
    <style type="text/css">
        @media screen and (max-width: 34em) {
      .row-offcanvas-left .sidebar-offcanvas {
        left: -45%;
      }
      .row-offcanvas-left.active {
        left: 45%;
        margin-left: -6px;
      }
      .sidebar-offcanvas {
        width: 45%;
      }
    }
    
    .card {
      overflow: hidden;
    }
    
    .card-block .rotate {
      z-index: 8;
      float: right;
      height: 100%;
    }
    
    .card-block .rotate i {
      color: rgba(20, 20, 20, 0.15);
      position: absolute;
      left: 0;
      left: auto;
      right: -10px;
      bottom: 0;
      display: block;
      -webkit-transform: rotate(-44deg);
      -moz-transform: rotate(-44deg);
      -o-transform: rotate(-44deg);
      -ms-transform: rotate(-44deg);
      transform: rotate(-44deg);
    }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body class="font-muli">
    <div id="main_content">
        <!-- Start Main top header -->
        <div id="header_top" class="header_top">
            <div class="container">
                <div>
                    <a href="javascript:void(0)" class="nav-link icon menu_toggle"><i class="fe fe-align-center"></i> </a>
                </div>
            </div>
        </div>
        <!-- Start Main leftbar navigation -->
        <div id="left-sidebar" class="sidebar">
            <h5 class="brand-name"><img src="{{asset('assets/images/logo.png')}}" alt="logo"></h5>
            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="menu-uni" role="tabpanel">
                    <nav class="sidebar-nav">
                        <ul class="metismenu">
                            <li class="border-top">
                            <a href="{{route('admin.home')}}"><span class="ml-3 text-white">Dashboard</span></a>
                            </li>
                            <li class="border-top">
                                <a href="{{route('admin.rider')}}"><span class="ml-3 text-white">Rider</span></a>
                            </li>
                            <li class="border-top">
                                <a href="{{route('admin.driver')}}"><span class="ml-3 text-white">Driver</span></a>
                            </li>
                            <li class="border-top">
                                <a href="{{route('admin.community_group')}}"><span class="ml-3 text-white">Community Group</span></a>
                            </li>
                            <li class="border-bottom border-top">
                                <a href="{{route('admin.payments')}}"><span class="ml-3 text-white">Payment</span></a>
                            </li>
                            <li class="border-bottom">
                                <a href="{{route('admin.help')}}"><span class="ml-3 text-white">Help</span></a>
                            </li>
                            <li class="border-bottom">
                                <a href="{{route('admin.queries')}}"><span class="ml-3 text-white">Queries</span></a>
                            </li>
                            <li class="border-bottom">
                                <a href="{{route('admin.termsCondition')}}"><span class="ml-3 text-white">Terms & Service</span></a>
                            </li>
                            <li class="border-bottom">
                                <a href="{{route('admin.privacyAndPolicy')}}"><span class="ml-3 text-white">Privacy & Policy</span></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div><!-- Start project content area -->
        <!-- Start project content area -->
        <div class="page">
        <!-- Start Page header -->
            <!-- Start Page title and tab -->
            <div class="" id="page_top">
                <div class="container-fluid" style="background-color: #f6921e;">
                <div class="page-header">
                    <div class="left">
                    
                    </div>
                    <div class="right">
                    <!-- <a href="notifications.html"><i class="fas fa-bell" style="font-size: 20px; color: #fff; margin-right: 20px;"></i></a>  -->
                    <div class="input-group">
                        <a class="btn btn-dark" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                        {{-- <a href="login.html" class="btn btn-dark" title="">Logout</a> --}}
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                    </div>
                </div>
                </div>
            </div><!-- Start Page title and tab -->
            <div class="section-body mt-5">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <!-- Start Main project js, jQuery, Bootstrap -->
    <script src="{{asset('assets/bundles/lib.vendor.bundle.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <!-- Start Plugin Js -->
    <script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/plugins/dropify/js/dropify.min.js')}}"></script>
    <script src="{{asset('assets/bundles/summernote.bundle.js')}}"></script>
    <script src="{{asset('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>
    <!-- Start project main js  and page js -->
    <script src="{{asset('assets/js/core.js')}}"></script>
</body>
<script>
    $(".sidebar-dropdown > a").click(function() {
        $(".sidebar-submenu").slideUp(200);
        if (
            $(this)
                .parent()
                .hasClass("active")
        ) {
            $(".sidebar-dropdown").removeClass("active");
            $(this)
                .parent()
                .removeClass("active");
        } else {
            $(".sidebar-dropdown").removeClass("active");
            $(this)
                .next(".sidebar-submenu")
                .slideDown(200);
            $(this)
                .parent()
                .addClass("active");
        }
    });
    $("#close-sidebar").click(function() {
        $(".page-wrapper").removeClass("toggled");
    });
    $("#show-sidebar").click(function() {
        $(".page-wrapper").addClass("toggled");
    });
</script>
<script type="text/javascript">
    
    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ["Total Drivers", "Active Drivers", " Pending Drivers"],
        datasets: [{
        backgroundColor: [
            "#200d51",
            "#f6921e",
            "#000"
        ],
        data: [27, 19,  8]
        }]
    }
    });
</script>
</html>
