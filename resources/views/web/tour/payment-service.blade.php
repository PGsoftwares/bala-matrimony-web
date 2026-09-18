@extends('web.layouts.layout')
@section('title', $metaTags->title ?? '')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <section id="content " style="background-image:url({{ asset('web/assets/images/homepages/pattern-4.fhss9Ko-.jpg') }});">
        <div class="container">

            <div class="row pricing-table justify-content-center">

                <div class="content col-md-10">
                    <div class="card">
                        <div class="primary_bg shadow p-4 text-light text-left">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="h4">Payment Plans</span>
                                    <p class="text-muted m-0">Choose the Plan That Best Suits Your Needs</p>
                                </div>
                                <div>
                                    <form action="{{ route('tourComplete') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                        <button type="submit" class="btn secondary_button">SKIP</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                @foreach($db['packages'] as $package)
                                    <div class="col-sm-6 col-lg-6">
                                        <div class="plan {{ $package->name === 'Gold' ? 'featured' : '' }} shadow  bg-white rounded">
                                            <div class="plan-header theme-bg">
                                                @if($package->name === 'Gold')
                                                    <!--<span class="pop-pln text-light">Most popular plan</span>-->
                                                @endif
                                                <h4 class="text-light">{{ $package->name }}</h4>
                                                <p class="text-muted text-light">{{ $package->description ?? '' }}</p>
                                                <div class="plan-price text-light">
                                                    <sup class="text-light">&#8377;</sup>{{ $package->amount }}<span>/ {{ $package->month }} month</span>
                                                </div>

                                            </div>
                                            <div class="plan-list">
                                                <ul>
                                                    <li><i class="fa fa-thumbs-up" aria-hidden="true"></i>You can view {{ $package->no_of_contact }} profile contact details</li>
                                                    <li><i class="fa fa-thumbs-up" aria-hidden="true"></i>You can send interest to {{ $package->no_of_interests }} profiles.</li>
                                                    <li><i class="fa fa-thumbs-up" aria-hidden="true"></i>You can chat with {{ $package->no_of_chats }} messages.</li>
                                                </ul>

                                                <div class="plan-button">
                                                    @if(empty($userPackage['package']))
                                                        {{-- If user has no package --}}
                                                        <a href="{{ url('payment') . '?amount=' . $package->amount . '&user_id=' . Auth::id() . '&package=' . $package->name . '&month=' . $package->month . '&no_of_contact=' . $package->no_of_contact . '&no_of_chats=' . $package->no_of_chats . '&no_of_interests=' . $package->no_of_interests }}" class="btn secondary_button">Buy Now</a>
                                                    @elseif($userPackage['is_active'])
                                                        {{-- If user has an active package --}}
                                                        <a href="{{ url('plan') }}" class="btn secondary_button">View Plan Details</a>
                                                    @else
                                                        {{-- If user has a closed or expired package --}}
                                                        <a href="{{ url('payment') . '?amount=' . $package->amount . '&user_id=' . Auth::id() . '&package=' . $package->name . '&month=' . $package->month . '&no_of_contact=' . $package->no_of_contact . '&no_of_chats=' . $package->no_of_chats . '&no_of_interests=' . $package->no_of_interests }}" class="btn secondary_button">Upgrade Now</a>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>




            </div>

        </div>
    </section>

    @foreach($db['paymentInfos'] as $paymentInfo)
        <section class="intro">
            <div class="bg-image" style="background-color: #f5f7fa;">
                <div class="mask d-flex align-items-center">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <div class="card shadow-2-strong " >
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-4 col-lg-4 d-flex d-md-block justify-content-center justify-content-md-start">
                                                <div class="card" style="height: 400px">
                                                    <img class="" src="{{ asset('paymentImage/' .$paymentInfo->qr_image) }}" alt="" style="height: 400px">
                                                </div>
                                            </div>
                                            <div class="col-sm-8 col-lg-8 mt-3 mt-md-0">
                                                <table class="table table-striped table-bordered">
                                                    <tbody>
                                                    <tr>
                                                        <td>Name</td>
                                                        <td>{{ $paymentInfo->acc_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Account Number</td>
                                                        <td>{{ $paymentInfo->acc_number }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Ifsc code</td>
                                                        <td>{{ $paymentInfo->ifsc_code }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Bank</td>
                                                        <td>{{ $paymentInfo->bank }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Pay Number</td>
                                                        <td>{{ $paymentInfo->pay_number }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>UPI Id</td>
                                                        <td>{{ $paymentInfo->upi_id }}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>

                                                <span class="p-2">Once Amount Paid. Contact admin & Inform. Contact: 8015460188.</span>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endforeach

    @include('web.includes.footer')
@endsection
