<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.6.8-fix/jquery.nicescroll.min.js"></script>
<link rel="stylesheet" href="{{asset('assets/css/chat.css')}}" />

@extends('layouts.master')
@section('style')
<style>
    .newfqheading h5 {
        color: white;
        font-weight: 600;
    }

    .newfqbody {
        padding: 20px;
    }

    .setBtn {
        color: #fff;
        background-color: #000;
        border-color: #000;
    }

    .btnSpace {
        margin-left: 10px;
    }
</style>
@endsection
@section('content')
@if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
@endif
<div class="tab-content">
    <div class="tab-pane active" id="Staff-all">
        <div class="card">


            {{-- ////////////////// --}}

            <div class="chatboxarea">

                <h4 class="text-dark font-weight-bold col-9">Faq Queries Chat</h4>
                <br>

                <div class="container">
                    <div class="content container-fluid bootstrap snippets bootdey">
                        <div class="row row-broken rowbroken">
                            <div class="col-sm-12 col-xs-12 chat chatt" style="overflow: hidden; outline: none;"
                                tabindex="5001">
                                <div class="col-inside-lg decor-default">
                                    <div class="chat-body">
                                        <div class="answer left">
                                            <div class="avatar">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png"
                                                    alt="User name">
                                                <div class="status offline"></div>
                                            </div>
                                            <div class="name">Alexander Herthic</div>
                                            <div class="text">
                                                Lorem ipsum dolor amet, consectetur adipisicing elit Lorem ipsum
                                                dolor amet, consectetur adipisicing
                                                elit Lorem ipsum dolor amet, consectetur adiping elit
                                            </div>
                                            <div class="time">5 min ago</div>
                                        </div>
                                        <div class="answer right">
                                            <div class="avatar">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar2.png"
                                                    alt="User name">
                                                <div class="status offline"></div>
                                            </div>
                                            <div class="name">Alexander Herthic</div>
                                            <div class="text">
                                                Lorem ipsum dolor amet, consectetur adipisicing elit Lorem ipsum
                                                dolor amet, consectetur adipisicing
                                                elit Lorem ipsum dolor amet, consectetur adiping elit
                                            </div>
                                            <div class="time">5 min ago</div>
                                        </div>
                                        <div class="answer left">
                                            <div class="avatar">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png"
                                                    alt="User name">
                                                <div class="status online"></div>
                                            </div>
                                            <div class="name">Alexander Herthic</div>
                                            <div class="text">
                                                ...
                                            </div>
                                            <div class="time">5 min ago</div>
                                        </div>
                                        <div class="answer right">
                                            <div class="avatar">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar2.png"
                                                    alt="User name">
                                                <div class="status busy"></div>
                                            </div>
                                            <div class="name">Alexander Herthic</div>
                                            <div class="text">
                                                It is a long established fact that a reader will be. Thanks Mate!
                                            </div>
                                            <div class="time">5 min ago</div>
                                        </div>
                                        <div class="answer right">
                                            <div class="avatar">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png"
                                                    alt="User name">
                                                <div class="status off"></div>
                                            </div>
                                            <div class="name">Alexander Herthic</div>
                                            <div class="text">
                                                It is a long established fact that a reader will be. Thanks Mate!
                                            </div>
                                            <div class="time">5 min ago</div>
                                        </div>
                                        <div class="answer left">
                                            <div class="avatar">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar2.png"
                                                    alt="User name">
                                                <div class="status offline"></div>
                                            </div>
                                            <div class="name">Alexander Herthic</div>
                                            <div class="text">
                                                Lorem ipsum dolor amet, consectetur adipisicing elit Lorem ipsum
                                                dolor amet, consectetur adipisicing
                                                elit Lorem ipsum dolor amet, consectetur adiping elit
                                            </div>
                                            <div class="time">5 min ago</div>
                                        </div>
                                        <div class="answer right">
                                            <div class="avatar">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png"
                                                    alt="User name">
                                                <div class="status offline"></div>
                                            </div>
                                            <div class="name">Alexander Herthic</div>
                                            <div class="text">
                                                Lorem ipsum dolor amet, consectetur adipisicing elit Lorem ipsum
                                                dolor amet, consectetur adipisicing
                                                elit Lorem ipsum dolor amet, consectetur adiping elit
                                            </div>
                                            <div class="time">5 min ago</div>
                                        </div>
                                        <div class="answer left">
                                            <div class="avatar">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar2.png"
                                                    alt="User name">
                                                <div class="status online"></div>
                                            </div>
                                            <div class="name">Alexander Herthic</div>
                                            <div class="text">
                                                ...
                                            </div>
                                            <div class="time">5 min ago</div>
                                        </div>
                                        <div class="answer right">
                                            <div class="avatar">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png"
                                                    alt="User name">
                                                <div class="status busy"></div>
                                            </div>
                                            <div class="name">Alexander Herthic</div>
                                            <div class="text">
                                                It is a long established fact that a reader will be. Thanks Mate!
                                            </div>
                                            <div class="time">5 min ago</div>
                                        </div>
                                        <div class="answer right">
                                            <div class="avatar">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar2.png"
                                                    alt="User name">
                                                <div class="status off"></div>
                                            </div>
                                            <div class="name">Alexander Herthic</div>
                                            <div class="text">
                                                It is a long established fact that a reader will be. Thanks Mate!
                                            </div>
                                            <div class="time">5 min ago</div>
                                        </div>
                                        <div class="answer right">
                                            <div class="avatar">
                                                <img src="https://bootdey.com/img/Content/avatar/avatar2.png"
                                                    alt="User name">
                                                <div class="status off"></div>
                                            </div>
                                            <div class="name">Alexander Herthic</div>
                                            <div class="text">
                                                It is a long established fact that a reader will be. Thanks Mate!
                                            </div>
                                            <div class="time">5 min ago</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ////////////////// --}}


            {{-- <div class="table-responsive">
                <div class="all-users row">
                    <h4 class="text-dark font-weight-bold col-9">Faq Queries Chat</h4>
                    <br>
                    <div class="col-md-12 mt-3">
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
                    </div>

                </div>
            </div> --}}
        </div>
    </div>

    <script>
        $(function () {
      $(".chat").niceScroll();
    })
    </script>

    @endsection