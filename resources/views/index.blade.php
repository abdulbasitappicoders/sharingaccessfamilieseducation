
<!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>Safe Mobile App</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="" />

        <!-- favicon -->
        <link rel="shortcut icon" href="{{asset('landing/images/favicon.ico')}}">
        <!-- Bootstrap -->
        <link href="{{asset('landing/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- Magnific -->
        <link href="{{asset('landing/css/magnific-popup.css')}}" rel="stylesheet" type="text/css" />
        <!-- Icon -->
        <link href="{{asset('landing/css/materialdesignicons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('landing/css/pe-icon-7-stroke.css')}}" rel="stylesheet" type="text/css" />
        <!-- SLICK SLIDER -->
        <link rel="stylesheet" href="{{asset('landing/css/owl.carousel.min.css')}}"/>
        <link rel="stylesheet" href="{{asset('landing/css/owl.theme.css')}}"/>
        <link rel="stylesheet" href="{{asset('landing/css/owl.transitions.css')}}"/>
        <!-- Swiper CSS -->
        <link rel="stylesheet" href="{{asset('landing/css/swiper.min.css')}}">
        <!-- Animation -->
        <link rel="stylesheet" href="{{asset('landing/css/aos.css')}}">
        <!-- Custom Css -->
        <link href="{{asset('landing/css/style.css')}}" rel="stylesheet" type="text/css" />

    </head>

    <body>

        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg fixed-top navbar-custom navbar-light sticky">
    		<div class="container">
			    <a class="navbar-brand" href="#">
                    <!-- Applock -->
                    <img src="{{asset('landing/images/logo-light.png')}}" class="l-dark" alt="logo">
                    <img src="{{asset('landing/images/logo-light.png')}}" class="l-light" alt="logo">
                </a>
			    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
			        <span class="mdi mdi-menu"></span>
			    </button><!--end button-->

			    <div class="collapse navbar-collapse" id="navbarCollapse">
			        <ul class="navbar-nav mx-auto">
			            <li class="nav-item active">
			                <a class="nav-link" href="#home">Home</a>
			            </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#about">About Us</a>
                        </li>
			            <li class="nav-item">
			                <a class="nav-link" href="#services">Features</a>
			            </li>
			            <li class="nav-item">
			                <a class="nav-link" href="#work">Screenshots</a>
			            </li>

                    </ul><!--end navbar nav-->
                    <div>
                        <a href="#download" class="login-button mouse-down ml-3">Download</a>
                        <a href="https://sharingaccessfamilieseducation.web.app/" class="login-button mouse-down ml-3 btn-primary" style="background-color:#190a40; ">Web Application</a>
                    </div><!--end login button-->
			    </div><!--end collapse-->
		    </div><!--end container-->
		</nav><!--end navbar-->
        <!-- Navbar End -->

        <!-- HOME START-->
        <section class="bg-home" style="background-image:url({{asset('landing/images/bg-2.jpg')}})" id="home">
            <div class="bg-overlay"></div>
            <div class="home-center">
                <div class="home-desc-center">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-7 col-md-6">
                                <div class="title-heading">
                                    <h1 class="heading text-white mb-3">Sharing Access with Families to a Good Education</h1>
                                    <p class="para-desc">A viable platform that’s keen on changing the aura of transportation by connecting riders with professional drivers!</p>
                                    <div class="row justify-content-center">
                    <div class="col-md-12">
                        <ul class="list-unstyled mb-0 app-download">
                            <li class="list-inline-item"><a href="https://apps.apple.com/pk/app/s-a-f-e-mobile-app/id1642537483" target="_blank"><img src="{{asset('landing/images/apple.png')}}" class="img-fluid mt-2" alt=""></a></li>
                            <li class="list-inline-item"><a href="https://play.google.com/store/apps/details?id=com.safefrontendmobile" target="_blank"><img src="{{asset('landing/images/google.png')}}" class="img-fluid mt-2" alt=""></a></li>
                        </ul>
                    </div><!--end col-->
                </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-5 col-md-6 mt-4 pt-2">
                                <div class="home-img text-md-right">
                                    <img src="{{asset('landing/images/home/mobile04.png')}}" class="img-fluid mover-img" alt="">
                                </div>
                            </div><!--end col-->
                        </div><!--end row-->
                    </div><!--end container-->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="home-shape">
                                <img src="{{asset('landing/images/shp01.png')}}" alt="" class="img-fluid mx-auto d-block">
                            </div>
                        </div><!--end row-->
                    </div><!--end container fluid-->
                </div><!--end home desc center-->
            </div><!--end home center-->
        </section><!--end section-->
        <!-- HOME END-->

        <!-- About Start -->
        <section class="section" id="about">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <img src="{{asset('landing/images/home/about-img.jpg')}}" class="img-fluid" data-aos="fade-right" alt="">
                    </div><!--end col-->

                    <div class="col-md-7 mt-4 mt-sm-0 pt-2 pt-sm-0">
                        <div class="about-app ml-lg-4">

                            <h1 class="mt-2 mb-3">About Us</h1>
                            <p>After tiring work, none of us have the energy to deal with loads of traffic while going home - and if you don’t want to drive, who would help you get to your destination? Well, allow us to introduce you to SAFE! <br><br> Safe is a profound transportation application that brings riders closer to professional drivers so that reaching destinations isn’t an issue for anyone! Offering secure trips in an affordable price range, the SAFE Application has everything that you need!  </p>

                        </div>
                    </div><!--end col-->
                </div>
            </div><!--end container-->
        </section><!--end section-->
        <!-- About End -->

        <!-- Feature Start -->
        <section class="section bg-light" id="services">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title text-center">

                            <h1 class=" mt-3 mb-5">App's Features</h1>
                            <p class="text-muted mx-auto para-desc mb-0">This application comes in handy with a range of exquisite features - some of the viable ones are enlisted below!</p>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->

                <div class="row services align-items-center">
                    <div class="col-lg-4 col-md-6">
                        <div class="core-service">
                            <div class="core-service-content mt-4 pt-2 fea-right text-right">
                                <div class="icon ml-4 mt-4">
                                    <i class="mdi mdi-alarm-multiple"></i>
                                </div>
                                <div class="content">
                                    <h4 class="title mb-2">Ride Scheduling</h4>
                                    <p class="text-muted mb-0">Booking a trip to your destination has now become super easy with SAFE.</p>
                                </div>
                            </div>

                            <div class="core-service-content mt-4 pt-2 fea-right text-right">
                                <div class="icon ml-4 mt-4">
                                    <i class="mdi mdi-share-variant"></i>
                                </div>
                                <div class="content">
                                    <h4 class="title mb-2">Ride Sharing</h4>
                                    <p class="text-muted mb-0">Share your ride with your loved ones, and feel secure while traveling! </p>
                                </div>
                            </div>

                            <div class="core-service-content mt-4 pt-2 fea-right text-right">
                                <div class="icon ml-4 mt-4">
                                    <i class="mdi mdi-map-marker-multiple"></i>
                                </div>
                                <div class="content">
                                    <h4 class="title mb-2">Geo-Location</h4>
                                    <p class="text-muted mb-0">Comes integrated with Google Maps so that you can reach your destination on time! </p>
                                </div>
                            </div>
                        </div>
                    </div><!--end col-->

                    <div class="col-lg-4 mt-4 pt-2 core-service-img">
                        <div class="ml-lg-4 mr-lg-4">
                            <img src="{{asset('landing/images/features.png')}}" class="img-fluid mx-auto" data-aos="zoom-in-down" alt="">
                        </div>
                    </div><!--end col-->

                    <div class="col-lg-4 col-md-6">
                        <div class="core-service">
                            <div class="core-service-content mt-4 pt-2 fea-left">
                                <div class="icon mr-4 mt-4">
                                    <i class="mdi mdi-alarm-light"></i>
                                </div>
                                <div class="content">
                                    <h4 class="title mb-2">Emergency Button</h4>
                                    <p class="text-muted mb-0">Something wrong with your trip? Press the emergency button, and help will be on its way! </p>
                                </div>
                            </div>

                              <div class="core-service-content mt-4 pt-2 fea-left">
                                <div class="icon mr-4 mt-4">
                                    <i class="mdi mdi-map-marker-radius"></i>
                                </div>
                                <div class="content">
                                    <h4 class="title mb-2">Location Tracking!</h4>
                                    <p class="text-muted mb-0">Keep an insight on your whereabouts with the latest location tracking feature! </p>
                                </div>
                            </div>

                            <div class="core-service-content mt-4 pt-2 fea-left">
                                <div class="icon mr-4 mt-4">
                                    <i class="mdi mdi-wechat"></i>
                                </div>
                                <div class="content">
                                    <h4 class="title mb-2">Live Chat</h4>
                                    <p class="text-muted mb-0">Make friends on the app, and stay connected with them via the Live Chat option! </p>
                                </div>
                            </div>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end container-->
        </section><!--end section-->
        <!-- Feature End -->

        <!-- START COUNTER -->
        <section class="bg-counter" style="background: url({{asset('landing/images/counter.jpg')}}) fixed center center;">
            <div class="bg-overlay"></div>
            <div class="container">
                <div class="row" id="counter">
                    <div class="col-lg-4 col-md-4 p-4">
                        <div class="counter-box text-center">
                            <div class="counter-icon">
                                <i class="mdi mdi-heart-outline text-white"></i>
                            </div>
                            <h2 class="counter-value mt-3 text-white" data-count="1853">1</h2>
                            <h5 class="counter-head text-white">Social Sharings</h5>
                        </div><!--end counter box-->
                    </div><!--end col-->

                    <div class="col-lg-4 col-md-4 p-4">
                        <div class="counter-box text-center">
                            <div class="counter-icon">
                                <i class="mdi mdi-progress-download text-white"></i>
                            </div>
                            <h2 class="counter-value mt-3 text-white" data-count="1467">11</h2>
                            <h5 class="counter-head text-white">Total Download</h5>
                        </div><!--end counter box-->
                    </div><!--end col-->

                    <div class="col-lg-4 col-md-4 p-4">
                        <div class="counter-box text-center">
                            <div class="counter-icon">
                                <i class="mdi mdi-star-outline text-white"></i>
                            </div>
                            <h2 class="counter-value mt-3 text-white" data-count="854">15</h2>
                            <h5 class="counter-head text-white">Positive Ratings</h5>
                        </div><!--end counter box-->
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end container-->
        </section><!--end section-->
        <!-- END COUTER -->

        <!-- START SCREENSHORT-->
        <section class="section" id="work">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title text-center">
                            <h1 class="mt-3 mb-5">App Screenshots</h1>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->

                <!-- Swiper -->
                <div class="row justify-content-center mt-4 pt-2">
                    <div class="col-12 swiper-container">
                        <div class="swiper-wrapper">
                        <div class="swiper-slide"><img src="{{asset('landing/images/sc/1.png')}}" class="img-fluid" alt="App Screenshots"></div>
                        <div class="swiper-slide"><img src="{{asset('landing/images/sc/2.png')}}" class="img-fluid" alt="App Screenshots"></div>
                        <div class="swiper-slide"><img src="{{asset('landing/images/sc/3.png')}}" class="img-fluid" alt="App Screenshots"></div>
                        <div class="swiper-slide"><img src="{{asset('landing/images/sc/4.png')}}" class="img-fluid" alt="App Screenshots"></div>
                        <div class="swiper-slide"><img src="{{asset('landing/images/sc/5.png')}}" class="img-fluid" alt="App Screenshots"></div>
                        <div class="swiper-slide"><img src="{{asset('landing/images/sc/6.png')}}" class="img-fluid" alt="App Screenshots"></div>
                        <div class="swiper-slide"><img src="{{asset('landing/images/sc/7.png')}}" class="img-fluid" alt="App Screenshots"></div>
                        <div class="swiper-slide"><img src="{{asset('landing/images/sc/8.png')}}" class="img-fluid" alt="App Screenshots"></div>
                        <div class="swiper-slide"><img src="{{asset('landing/images/sc/9.png')}}" class="img-fluid" alt="App Screenshots"></div>
                        <div class="swiper-slide"><img src="{{asset('landing/images/sc/10.png')}}" class="img-fluid" alt="App Screenshots"></div>
                        <div class="swiper-slide"><img src="{{asset('landing/images/sc/11.png')}}" class="img-fluid" alt="App Screenshots"></div>
                        <div class="swiper-slide"><img src="{{asset('landing/images/sc/12.png')}}" class="img-fluid" alt="App Screenshots"></div>
                        <div class="swiper-slide"><img src="{{asset('landing/images/sc/13.png')}}" class="img-fluid" alt="App Screenshots"></div>
                        </div>
                        <!-- Add Arrows  -->
                        <div class="swiper-button-next">
                            <i class="mdi mdi-chevron-right"></i>
                        </div>
                        <div class="swiper-button-prev ">
                            <i class="mdi mdi-chevron-left"></i>
                        </div>
                    </div>

                    <div class="col-lg-5 col-md-5 col-12 mb-0 mb-md-5 mb-4 mb-sm-0">
                        <div class="screenshot-cell">
                            <img src="{{asset('landing/images/sc/mo-sc.png')}}" class="img-fluid" alt="">
                        </div>
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end container-->
        </section><!--end section-->
        <!-- END SCREENSHORT -->

        <!-- CTA Download Start -->
        <section class="section" style="background:url({{asset('landing/images/counter.jpg')}}) fixed center center;" id="download">
            <div class="bg-overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title text-center">

                            <h4 class="title text-uppercase text-light mt-3 mb-5">Get The App</h4>
                            <h1 class="text-light">It’s Free to Download for Everyone</h1>

                        </div>
                    </div><!--end col-->
                </div><!--end row-->

                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <ul class="list-unstyled mb-0 app-download text-center">
                            <li class="list-inline-item"><a href="https://apps.apple.com/pk/app/s-a-f-e-mobile-app/id1642537483" target="_blank"><img src="{{asset('landing/images/apple.png')}}" class="img-fluid mt-2" alt=""></a></li>
                            <li class="list-inline-item"><a href="https://play.google.com/store/apps/details?id=com.safefrontendmobile" target="_blank"><img src="{{asset('landing/images/google.png')}}" class="img-fluid mt-2" alt=""></a></li>
                        </ul>
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end container-->
        </section><!--end section-->
        <!-- CTA Download End -->


        <!-- Footer Start -->
        <footer class="footer">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="text-sm-left">
                            <p class="mb-0">{{date('Y')}} © S.A.F.E. All Rights Reserved.</p>
                        </div>
                    </div><!--end col-->

                    <div class="col-sm-6">
                        <ul class="list-unstyled text-sm-right social-icon social mb-0 mt-4 mt-sm-0">
                            <li class="list-inline-item"><a href="{{route('privacy_policy')}}">Privacy Policy</a></li>
                            <li class="list-inline-item"><a href="{{route('term_and_condition')}}">Terms & Condition</a></li>
                        </ul>
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end container-->
        </footer><!--end footer-->
        <!-- Footer End -->

        <!-- Back to top -->
        <a href="#" class="back-to-top text-center" id="back-to-top">
            <i class="mdi mdi-chevron-up d-block"> </i>
        </a>
        <!-- Back to top -->
        <!-- javascript -->
        <script src="{{asset('landing/js/jquery.min.js')}}"></script>
        <script src="{{asset('landing/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('landing/js/jquery.easing.min.js')}}"></script>
        <script src="{{asset('landing/js/scrollspy.min.js')}}"></script>
        <!-- SLIDER -->
        <script src="{{asset('landing/js/owl.carousel.min.js')}} "></script>
        <!-- Magnific Popup -->
        <script src="{{asset('landing/js/jquery.magnific-popup.min.js')}}"></script>
        <!-- Contact -->
        <script src="{{asset('landing/js/contact.js')}}"></script>
        <!-- Counter -->
        <script src="{{asset('landing/js/counter.init.js')}}"></script>
        <!-- Swiper JS -->
        <script src="{{asset('landing/js/swiper.min.js')}}"></script>
        <!-- Animation JS -->
        <script src="{{asset('landing/js/aos.js')}}"></script>
        <!-- Animation JS -->
        <script src="{{asset('landing/js/jquery.nicescroll.js')}}"></script>
        <!-- Plugin init -->
        <script src="{{asset('landing/js/plugin.init.js')}}"></script>
        <!-- Main Js -->
        <script src="{{asset('landing/js/app.js')}}"></script>
    </body>
</html>
