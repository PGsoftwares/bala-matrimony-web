@extends('web.layouts.layout')
@section('title', 'Razorpay Secure Payment - ' . ($data['package'] ?? 'Membership'))
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="gradient-bg-1 text-center py-4 text-white">
                        <div class="mb-2">
                            <span class="badge bg-white text-dark px-3 py-2 rounded-pill fw-semibold shadow-sm">
                                <i class="bi bi-shield-lock-fill text-success me-1"></i> Razorpay 256-Bit Secure
                            </span>
                        </div>
                        <h4 class="fw-bold mb-1">{{ $data['package'] }} Package</h4>
                        <h2 class="fw-bold mb-0">&#8377; {{ number_format($data['amount_rupees'] ?? ($data['amount'] / 100), 2) }}</h2>
                        <small class="opacity-75">Validity: {{ $data['month'] ?? 1 }} {{ ($data['month'] ?? 1) > 1 ? 'Months' : 'Month' }}</small>
                    </div>

                    <div class="card-body p-4">
                        <h6 class="text-muted text-uppercase fw-semibold mb-3 fs-7">Package Benefits</h6>
                        <ul class="list-group list-group-flush mb-4 rounded-3 border">
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <span><i class="bi bi-person-lines-fill primary_color me-2"></i> Contact Views</span>
                                <span class="badge bg-primary rounded-pill">{{ $data['no_of_contact'] ?? 0 }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <span><i class="bi bi-chat-dots-fill primary_color me-2"></i> Chat Messages</span>
                                <span class="badge bg-primary rounded-pill">{{ $data['no_of_chats'] ?? 0 }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <span><i class="bi bi-heart-fill primary_color me-2"></i> Express Interests</span>
                                <span class="badge bg-primary rounded-pill">{{ $data['no_of_interests'] ?? 0 }}</span>
                            </li>
                        </ul>

                        <form method="POST" action="{{ route('payment.success') }}" id="razorpay-form">
                            @csrf
                            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                            <input type="hidden" name="razorpay_order_id" id="razorpay_order_id" value="{{ $data['order_id'] }}">
                            <input type="hidden" name="razorpay_signature" id="razorpay_signature">

                            <div class="d-grid gap-2">
                                <button type="button" id="pay-button" class="btn button1 btn-lg fw-semibold shadow-sm">
                                    <i class="bi bi-credit-card me-2"></i> Pay &#8377;{{ number_format($data['amount_rupees'] ?? ($data['amount'] / 100), 2) }} via Razorpay
                                </button>
                                <a href="{{ url('payment-plans') }}" class="btn btn-outline-secondary btn-sm mt-2">
                                    <i class="bi bi-arrow-left me-1"></i> Choose Another Plan
                                </a>
                            </div>

                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    <i class="bi bi-lock me-1"></i> UPI, Cards, NetBanking, Wallets supported.
                                    <br>By proceeding, you agree to our
                                    <a href="{{ url('terms-and-condition') }}" target="_blank" class="text-decoration-underline">Terms & Conditions</a>.
                                </small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let razorpayOptions = {
            "key": "{{ $data['razorpayKey'] }}",
            "amount": "{{ $data['amount'] }}",
            "currency": "INR",
            "name": "{{ config('app.name', 'Bala Matrimony') }}",
            "description": "Subscription for {{ $data['package'] }} Plan",
            "image": "{{ asset('asset/img/logo/fav-icon-pg.png') }}",
            "order_id": "{{ $data['order_id'] }}",
            "handler": function (response) {
                document.getElementById('pay-button').disabled = true;
                document.getElementById('pay-button').innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Verifying Payment...';
                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                document.getElementById('razorpay_signature').value = response.razorpay_signature;
                document.getElementById('razorpay-form').submit();
            },
            "prefill": {
                "name": "{{ $data['name'] ?? '' }}",
                "email": "{{ $data['email'] ?? '' }}",
                "contact": "{{ $data['contact'] ?? '' }}"
            },
            "theme": {
                "color": "#e91e63"
            },
            "modal": {
                "ondismiss": function() {
                    console.log('Payment modal closed');
                }
            }
        };

        let rzp = new Razorpay(razorpayOptions);

        document.getElementById('pay-button').onclick = function(e){
            rzp.open();
            e.preventDefault();
        };

        // Automatically open payment popup on load
        window.onload = function() {
            setTimeout(function() {
                rzp.open();
            }, 600);
        };
    </script>

    @include('web.includes.footer')
@endsection
