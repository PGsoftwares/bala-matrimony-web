@extends('web.layouts.layout')
@section('title', $metaTags->title ?? 'Forgot Password')
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

                        @if (session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif

                        @if ($errors->has('email'))
                            <div class="alert alert-danger">{{ $errors->first('email') }}</div>
                        @endif

                        <h4 class="fw-medium text-center mb-3">Forgot Password</h4>

                        <form method="post" action="{{ route('password.email') }}">
                            @csrf

                            <div class="mb-3">
                                <input type="email" name="email" class="form-control rounded-pill shadow-none w-100 @error('email') is-invalid @enderror" placeholder="Enter your email">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <button type="submit" class="btn button2 w-100 rounded-pill">Reset Password</button>
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
