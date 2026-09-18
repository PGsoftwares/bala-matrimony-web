@extends('admin.layouts.layout')

@section('content')
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mb-5 text-muted">
                        <a href="#" class="d-block auth-logo">
                            <img src="{{ asset('dashboard/admin/images/logo-dark.png') }}" alt="" height="20" class="auth-logo-dark mx-auto">
                            <img src="{{ asset('dashboard/admin/images/logo-light.png') }}" alt="" height="20" class="auth-logo-light mx-auto">
                        </a>
                    </div>
                </div>
            </div>
            <!-- end row -->
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="p-2">
                                <div class="text-center">
                                    <div class="avatar-md mx-auto">
                                        <div class="avatar-title rounded-circle bg-light">
                                            <i class="bx bxs-envelope h1 mb-0 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="p-2 mt-4">
                                        <h4 class="  text-center tit">Verify your email</h4>
                                        <p>
                                            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                                        </p>
                                        @if (session('status') == 'verification-link-sent')
                                            <div class="mb-4 font-medium text-sm text-green-600">
                                                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                                            </div>
                                        @endif
                                        <div class="mt-4">
                                            <form method="POST" action="{{ route('verification.send') }}">
                                                @csrf
                                                <button type="submit" class="btn btn-success w-md">
                                                    {{ __('Resend Verification Email') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p>
                        {{ __('Didn\'t receive an email?') }}
                        <form method="POST" action="{{ route('verification.send') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="fw-medium text-primary btn btn-link p-0 m-0 align-baseline">{{ __('Resend') }}</button>.
                        </form>
                        </p>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 btn btn-link p-0 m-0 align-baseline">{{ __('Log Out') }}</button>.
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
