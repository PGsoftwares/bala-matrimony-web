@extends('web.layouts.layout')
@section('title', $metaTags->title ?? 'Login with OTP')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <section class="container-fluid p-0">
        <div class="row g-0 min-vh-100">

            <!-- Centered Form -->
            <div class="col-md-6 d-flex mb-md-0 mb-5 align-items-center justify-content-center p-4">
                <div class="card shadow rounded-4 w-100" style="max-width: 450px;">
                    <div class="card-body p-4">

                        @if ($errors->has('success'))
                            <div class="alert alert-success">{{ $errors->first('success') }}</div>
                        @endif

                        @if ($errors->has('email'))
                            <div class="alert alert-danger">{{ $errors->first('email') }}</div>
                        @endif

                        @if ($errors->has('error'))
                            <div class="alert alert-danger">{{ $errors->first('error') }}</div>
                        @endif

                        <h4 class="fw-bold text-center mb-2">Login</h4>
                        <p class="text-muted text-center mb-4">Login your account</p>

                        <form method="post" action="{{ route('mailOTP') }}">
                            @csrf

                            <div class="mb-3">
                                <input type="email" name="email" class="form-control rounded-pill shadow-none w-100 @error('email') is-invalid @enderror" placeholder="Enter your email">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <button type="submit" class="btn button2 w-100 rounded-pill">Send OTP</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Image Column -->
            <div class="col-md-6 d-md-block d-none">
                <img src="{{ asset('asset/img/IMG01.png') }}" alt="Left Image" class="img-fluid w-100 h-100" style="object-fit: cover;">
            </div>

        </div>
    </section>

    @include('web.includes.footer')
@endsection
