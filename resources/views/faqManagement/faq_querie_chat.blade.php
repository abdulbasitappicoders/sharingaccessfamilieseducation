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
            <h4 class="text-dark font-weight-bold col-9">Faq Queries Chat</h4>
            <div class="chatboxarea">

                <br>

                <div class="container">
                    <div class="content container-fluid bootstrap snippets bootdey">
                        <div class="row row-broken rowbroken">
                            <div class="col-sm-12 col-xs-12 chat chatt" style="overflow: hidden; outline: none;"
                                tabindex="5001">
                                <div class="col-inside-lg decor-default">
                                    <div class="chat-body">
                                        @forelse($chat_list_messages as $chat)
                                            @if($chat->chatList->to != $chat->from)
                                                <div class="answer left">
                                                    <div class="avatar">
                                                        <img src="https://bootdey.com/img/Content/avatar/avatar1.png"
                                                             alt="User name">
                                                        <div class="status offline"></div>
                                                    </div>
                                                    <div class="name">{{ ucfirst($chat->toUser->username)??'' }} </div>
                                                    <div class="text">
                                                        {{ $chat->message??'' }}
                                                    </div>
                                                    <div class="time">{{ $chat->created_at }}</div>
                                                </div>
                                            @else
                                                <div class="answer right">
                                                    <div class="avatar">
                                                        <img src="https://bootdey.com/img/Content/avatar/avatar2.png"
                                                             alt="User name">
                                                        <div class="status offline"></div>
                                                    </div>
                                                    <div class="name">{{ ucfirst($chat->toUser->username)??'' }} </div>
                                                    <div class="text">
                                                        {{ $chat->message??'' }}
                                                    </div>
                                                    <div class="time">{{ $chat->created_at }}</div>
                                                </div>
                                            @endif
                                        @empty
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ////////////////// --}}
        </div>
    </div>

    <script>
        $(function () {
      $(".chat").niceScroll();
    })
    </script>

    @endsection
