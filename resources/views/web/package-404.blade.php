@extends('web.layouts.layout')

@section('title', $metaTags->title ?? 'Package not found')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')


@section('content')
    @include('web.includes.header')


    <section class=" p-b-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="page-error-404">404</div>
                </div>
                <div class="col-lg-6">
                    <div class="text-left">
                        <h1 class="text-medium">You don't have an active package!</h1>
                        <p class="lead">To access these details, you need a payment plan. Please upgrade your account to view the user's details</p>
                        <div class="seperator m-t-20 m-b-20"></div>
                        <div class="search-form">
                            <p>Please upgrade package</p>
                            <div class="">
                                <span class="">
                                    <a href="{{ url('payment-plans') }}" class="btn">Upgrade Now</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    @include('web.includes.footer')
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
@endsection
