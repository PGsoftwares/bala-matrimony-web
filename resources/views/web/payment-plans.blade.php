@extends('web.layouts.layout')
@section('title', $metaTags->title ?? '')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <section class="container py-5">

        {{--Online Payment Mode--}}
        <div class="row justify-content-center g-4">

            @foreach($db['packages'] as $package)
            <div class="col-md-4 col-sm-6">
                <div class="card border-0 shadow rounded-3 overflow-hidden h-100">
                    <div class="gradient-bg-1 text-center py-4">
                        <h5 class="mb-1 fw-semibold">{{ $package->name }}</h5>
                        <h3 class="fw-bold mb-0">&#8377; {{ $package->amount }}<span class="fs-6 fw-normal"> / {{ (empty($package->month) || $package->month == 0) ? 'Without Expiry Date' : $package->month . ' month' . ($package->month > 1 ? 's' : '') }}</span></h3>
                    </div>
                    <div class="card-body px-2 py-2">
                        <ul class="list-unstyled border rounded p-0 overflow-hidden">
                            <li class="d-flex justify-content-between align-items-center px-3 py-3 border-bottom">
                                <span>You can view {{ $package->no_of_contact }} profile contacts.</span>
                                <i class="bi bi-check-circle-fill primary_color"></i>
                            </li>
                            <li class="d-flex justify-content-between align-items-center px-3 py-3 border-bottom">
                                <span>You can send interest to {{ $package->no_of_interests }} profiles</span>
                                <i class="bi bi-check-circle-fill primary_color"></i>
                            </li>
                            <li class="d-flex justify-content-between align-items-center px-3 py-3">
                                <span>You can chat with {{ $package->no_of_chats }} messages.</span>
                                <i class="bi bi-check-circle-fill primary_color"></i>
                            </li>
                        </ul>

                        <div class="d-grid">
                            @if(!$userPackage['is_active'])
                                <form action="{{ route('payment.razorpay') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="amount" value="{{ $package->amount }}">
                                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                    <input type="hidden" name="package" value="{{ $package->name }}">
                                    <input type="hidden" name="month" value="{{ $package->month }}">
                                    <input type="hidden" name="no_of_contact" value="{{ $package->no_of_contact }}">
                                    <input type="hidden" name="no_of_chats" value="{{ $package->no_of_chats }}">
                                    <input type="hidden" name="no_of_interests" value="{{ $package->no_of_interests }}">
                                    <button type="submit" class="btn button1 fw-semibold w-100">
                                        {{ empty($userPackage['package']) ? 'Buy Now' : 'Upgrade Now' }}
                                    </button>
                                </form>
                            @else
                                <a href="{{ url('plan') }}" class="btn button1 fw-semibold">View Plan Details</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

        </div>

        {{--Offline Payment Mode--}}
        @foreach($db['paymentInfos'] as $paymentInfo)
        <div class="card shadow rounded-4 p-4 my-4">
            <div class="row align-items-center">
                <!-- QR Code -->
                <div class="col-md-4 text-center mb-4 mb-md-0">
                    <img src="{{ asset('paymentImage/' .$paymentInfo->qr_image) }}" alt="QR Code" class="img-fluid rounded shadow" style="max-width: 200px;">
                </div>

                <!-- Bank Details -->
                <div class="col-md-8">
                    <h5 class="fw-semibold mb-3">Payment Details</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <tbody>
                            <tr>
                                <th class="text-nowrap">Bank Name</th>
                                <td>{{ $paymentInfo->bank }}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap">Account Name</th>
                                <td>{{ $paymentInfo->acc_name }}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap">Account Number</th>
                                <td>{{ $paymentInfo->acc_number }}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap">IFSC Code</th>
                                <td>{{ $paymentInfo->ifsc_code }}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap">Pay Number</th>
                                <td>{{ $paymentInfo->pay_number }}</td>
                            </tr>
                            <tr>
                                <th class="text-nowrap">UPI ID</th>
                                <td>{{ $paymentInfo->upi_id }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Notice -->
            <div class="mt-4 text-center bg-light p-3 rounded-3 border">
                <strong>Once Amount Paid, contact support & inform.</strong><br>
                Contact: <a href="tel:9597066077" class="text-decoration-none fw-semibold">+91 95970 66077</a> , <a href="tel:8122350963" class="text-decoration-none fw-semibold">+91 81223 50963</a>
            </div>
        </div>
        @endforeach

    </section>

    @include('web.includes.footer')
@endsection
