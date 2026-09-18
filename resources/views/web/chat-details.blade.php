@extends('web.layouts.layout')
@section('title', $metaTags->title ?? 'chat with ' .$chatUser->name)
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <section id="page-content" class="sidebar-right">
        <div class="container-fluid">
            <div class="row">
                @include('web.includes.aside')

                <div class="content col-md-9">
                    <div class="card p-2">
                        <div class="card-header d-flex align-items-center mb-3 primary_bg text-center text-white ">
                            <img src="{{ $chatUser->profile_image }}" alt="" class="avatar avatar-lg">
                            <div class="ml-3">
                                <h4 class="mb-0 text-light">{{ $chatUser->name ?? 'Unknown User' }}</h4>
                                <small class="text-muted" style="color: #FFFFFF !important;">
                                    @if($chatUser->login_at && !$chatUser->logout_at)
                                        Online
                                    @elseif($chatUser->logout_at)
                                        last seen {{ \Carbon\Carbon::parse($chatUser->logout_at)->diffForHumans() }}
                                    @else
                                        Offline
                                    @endif
                                </small>
                            </div>
                        </div>

                        <div class="chat-details">
                            @if($chatDetails->isNotEmpty())
                                @foreach($chatDetails as $message)
                                    <div class="message {{ $message->user_id === Auth::user()->id ? 'message-sent' : 'message-received' }}">
                                        <p>{{ $message->messages }}</p>
                                        <span>{{ \Carbon\Carbon::parse($message->created_at)->format('h:i a') }}</span>
                                    </div>
                                @endforeach
                            @else
                                <p>No chat history available.</p>
                            @endif
                        </div>

                        <div class="card-footer">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            @if (session('info'))
                                <div class="alert alert-info">{{ session('info') }}</div>
                            @endif

                            <form class="" action="{{ route('chats.store') }}"  method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                <input type="hidden" name="chat_user_id" value="{{ $chatUser->user_id }}">

                                <div class="input-group">
                                    <input  name="message" class="form-control" placeholder="Type a message..." type="text">
                                    <span class="input-group-btn">
                                    <button type="submit"  class="btn primary_button" style="height: 40px;width: 50px"><i class="fa fa-paper-plane"></i></button>
                                </span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <style>
        .chat-details {
            padding: 20px;
            max-height: 500px;
            overflow-y: auto;
        }

        .message {
            padding: 10px 15px;
            margin: 10px 0;
            border-radius: 20px;
            max-width: 70%;
            position: relative;
        }

        .message-sent {
            background-color: #d1e7dd;
            margin-left: auto;
            text-align: right;
        }

        .message-received {
            background-color: #f8d7da;
            margin-right: auto;
            text-align: left;
        }

        .message span {
            font-size: 12px;
            display: block;
            margin-top: 5px;
            color: #6c757d;
        }

    </style>
    @include('web.includes.footer')
@endsection
