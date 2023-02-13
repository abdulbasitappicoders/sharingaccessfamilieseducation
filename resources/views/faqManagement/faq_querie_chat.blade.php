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

    .rowbroken {
        display: contents !important;
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


            <div class="chatboxarea">
                <br>
                <div class="container">
                    <div class="content container-fluid bootstrap snippets bootdey">
                        <div class="row row-broken rowbroken">
                            <div class="col-sm-12 col-xs-12 chat chatt" style="outline: none;">
                                <div class="col-inside-lg decor-default">
                                    <div class="chat-body">
                                        @forelse($chat_list_messages as $chat)
                                        @if($chat->chatList->to != $chat->from)

                                        @if($chat->type == 'text')
                                        <div class="answer left">
                                            <div class="avatar">
                                                <img src="{{$chat->fromUser->getProfileImage()}}" alt="User name">
                                                <div class="status offline"></div>
                                            </div>
                                            <div class="name">{{ ucfirst($chat->fromUser->username)??'' }} </div>
                                            <div class="text">
                                                {{ $chat->message??'' }}
                                            </div>
                                            <div class="time">{{ $chat->created_at->diffForHumans() }}</div>
                                        </div>
                                        @endif

                                        @if($chat->type == 'image')

                                        <div class="answer left">
                                            <div class="avatar">
                                                <img src="{{$chat->fromUser->getProfileImage()}}" alt="User name">
                                                <div class="status offline"></div>
                                            </div>
                                            <div class="name">{{ ucfirst($chat->fromUser->username)??'' }} </div>
                                            <div class="text">
                                                @forelse($chat->messagesFiles as $image)
                                                <img src='{{ asset(' images/'."$image->name") }}'
                                                alt="description of myimage" WIDTH="100%">
                                                @empty
                                                @endforelse
                                            </div>
                                            <div class="time">{{ $chat->created_at->diffForHumans() }}</div>
                                        </div>
                                        @endif

                                        @else

                                        @if($chat->type == 'text')
                                        <div class="answer right">
                                            <div class="avatar">
                                                <img src="{{$chat->fromUser->getProfileImage()}}" alt="User name">
                                                <div class="status offline"></div>
                                            </div>
                                            <div class="name">{{ ucfirst($chat->fromUser->username)??'' }}</div>
                                            <div class="text">
                                                {{ $chat->message??'' }}
                                            </div>
                                            <div class="time">{{ $chat->created_at->diffForHumans() }}</div>
                                        </div>
                                        @endif

                                        @if($chat->type == 'image')
                                        <div class="answer right">
                                            <div class="avatar">
                                                <img src="{{$chat->fromUser->getProfileImage()}}" alt="User name">
                                                <div class="status offline"></div>
                                            </div>
                                            <div class="name">{{ ucfirst($chat->fromUser->username)??'' }} </div>
                                            <div class="text">
                                                @forelse($chat->messagesFiles as $image)
                                                <img src='{{ asset(' images/'."$image->name") }}'
                                                alt="description of myimage">
                                                @empty
                                                @endforelse
                                            </div>
                                            <div class="time">{{ $chat->created_at->diffForHumans() }}</div>
                                        </div>
                                        @endif

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

        </div>
    </div>

    <script>
        $(function () {
                $(".chat").niceScroll();
            })
    </script>

    @endsection