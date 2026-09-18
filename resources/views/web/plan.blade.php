@extends('web.layouts.layout')
@section('title', $metaTags->title ?? '')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <div class="container my-5">
        <div class="row g-4">
            <!-- Left Card -->

            <div class="col-md-4 col-sm-6">
                <div class="card border-0 shadow rounded-3 overflow-hidden h-100">
                    <div class="gradient-bg-1 text-center py-4">
                        <h5 class="mb-1 fw-semibold">{{ $receipt->package ?? 'No Package' }}</h5>
                        <h3 class="fw-bold mb-0">&#8377; {{ $receipt->amount ?? '0' }}<span class="fs-6 fw-normal"> / {{ (!empty($receipt) && (empty($receipt->month) || $receipt->month == 0)) ? 'Without Expiry Date' : ($receipt->month ?? '0') . ' month' . (($receipt->month ?? 0) > 1 ? 's' : '') }}</span></h3>
                    </div>
                    <div class="card-body px-2 py-2">

                        <ul class="list-unstyled border rounded p-0 overflow-hidden">
                            <li class="d-flex justify-content-between align-items-center px-3 py-3 border-bottom">
                                <p class="text-muted m-0">Plan Name : <span class="fw-medium">{{ $receipt->package ?? 'No Package' }}</span></p>
                            </li>
                            <li class="d-flex justify-content-between align-items-center px-3 py-3 border-bottom">
                                <p class="text-muted m-0">Validity : <span class="fw-medium">{{ (!empty($receipt) && (empty($receipt->month) || $receipt->month == 0)) ? 'Without Expiry Date' : ($receipt->month ?? '0') . ' Months' }}</span></p>
                            </li>
                            <li class="d-flex justify-content-between align-items-center px-3 py-3 border-bottom">
                                <p class="text-muted m-0">Valid till :
                                <span class="fw-medium">
                                    {{ $receipt ? (empty($receipt->expiry_date) || $receipt->month == 0 ? 'Without Expiry Date' : \Carbon\Carbon::parse($receipt->expiry_date)->format('d F Y')) : 'N/A' }}
                                </span>
                                </p>
                            </li>

                        </ul>

                        <ul class="list-unstyled border rounded p-0 overflow-hidden">
                            <li class="d-flex justify-content-between align-items-center px-3 py-3 border-bottom">
                                <span>You can view {{ $receipt->no_of_contact ?? '0' }} profile contacts.</span>
                                <i class="bi bi-check-circle-fill primary_color"></i>
                            </li>
                            <li class="d-flex justify-content-between align-items-center px-3 py-3 border-bottom">
                                <span>You can send interest to {{ $receipt->no_of_interests ?? '0' }} profiles</span>
                                <i class="bi bi-check-circle-fill primary_color"></i>
                            </li>
                            <li class="d-flex justify-content-between align-items-center px-3 py-3">
                                <span>You can chat with {{ $receipt->no_of_chats ?? '0' }} messages.</span>
                                <i class="bi bi-check-circle-fill primary_color"></i>
                            </li>
                        </ul>

                        <div class="d-grid">
                            @if(!$isPackageValid)
                                <a href="{{ url('payment-plans') }}" class="btn button1 fw-semibold">Upgrade Now</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


            <!-- Right Card -->
            <div class="col-md-8">
                <div class="card p-4 rounded-4 shadow border-0">
                    <h5 class="mb-4">Package Usage</h5>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if (session('warning'))
                        <div class="alert alert-warning">{{ session('warning') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="fw-semibold">Contacts</h5>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between px-3 py-3">
                                    <span>Total Contact</span><span>{{ $receipt->no_of_contact ?? '0' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-3 py-3">
                                    <span>Viewed Contact</span><span>{{ $receipt->no_of_viewed ?? '0' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-3 py-3">
                                    <span>Available Contact</span><span>{{ $receipt->balance ?? '0' }}</span>
                                </li>
                            </ul>
                        </div>

                        <div class="col-md-6">
                            <h5 class="fw-semibold">Interest</h5>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between px-3 py-3">
                                    <span>Total Interest</span><span>{{ $receipt->no_of_interests ?? '0' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-3 py-3">
                                    <span>Interests Sent</span><span>{{ $receipt->viewed_interests ?? '0' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-3 py-3">
                                    <span>Available Interest</span><span>{{ $receipt->balance_interests ?? '0' }}</span>
                                </li>
                            </ul>
                        </div>

                        <div class="col-md-6 mt-2">
                            <h5 class="fw-semibold">Chat</h5>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between px-3 py-3">
                                    <span>Total Message</span><span>{{ $receipt->no_of_chats ?? '0' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-3 py-3">
                                    <span>Messages Sent</span><span>{{ $receipt->viewed_chats ?? '0' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-3 py-3">
                                    <span>Available Message</span><span>{{ $receipt->balance_chats ?? '0' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('web.includes.footer')
@endsection
