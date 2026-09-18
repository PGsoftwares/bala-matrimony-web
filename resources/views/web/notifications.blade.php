@extends('web.layouts.layout')
@section('title', $metaTags->title ?? 'Notifications')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
{{--    @include('web.includes.header')--}}

    <section id="page-content py-5">
        <div class="container-fluid">
            <h3 class="mb-4">Notifications</h3>

            <div class="alert alert-info">
                <strong>Unread Notifications:</strong> {{ $unreadCount }}
            </div>

            @if ($notifications->isEmpty())
                <div class="alert alert-secondary">
                    No notifications available.
                </div>
            @else
                @foreach ($notifications as $notification)
                    @php
                        $isUnread = is_null($notification['read_at']);
                        $status = $isUnread ? 'unread' : 'read';
                    @endphp

                    <div class="card mb-2 {{ $isUnread ? 'border-primary' : '' }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $notification['title'] }}</h5>
                            <p class="card-text">{{ $notification['message'] }}</p>
                            <p class="card-text d-flex justify-content-between align-items-center">
                                <small class="text-muted">{{ $notification['datetime'] }}</small>
                                <span class="badge bg-{{ $status === 'read' ? 'secondary' : 'primary' }}">
                            {{ ucfirst($status) }}
                        </span>
                            </p>

                            @if ($isUnread)
                                <a href="{{ route('notifications.markAsRead', ['notifiableId' => $notification['notifiable_id'], 'id' => $notification['id']]) }}"
                                   class="btn theme-btn">
                                    Read
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </section>

{{--    @include('web.includes.footer')--}}
@endsection
