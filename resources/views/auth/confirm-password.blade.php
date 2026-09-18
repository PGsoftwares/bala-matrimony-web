@extends('web.layouts.layout')
@section('title', $metaTags->title ?? 'Confirm Password')

@section('content')
    @include('web.includes.header')

    <section class="" data-bg-parallax="{{ asset('web/assets/images/auth/login.webp') }}">
        <div class="bg-overlay"></div>
        <div id="particles-snow" class="particles"></div>
        <div class="container mt-md-0">
            <div class="row">
                <div class="col-lg-5 center p-30 background-white b-r-6">
                    <h3 class="text-center  tit">Confirm password</h3>

                    @if ($errors->has('login_name'))
                        <div class="alert alert-danger">{{ $errors->first('login_name') }}</div>
                    @endif

                    @if ($errors->has('account'))
                        <div class="alert alert-danger">{{ $errors->first('account') }}</div>
                    @endif

                    <form method="post" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <label class="">Email</label>
                            <input type="text" class="form-control" name="login_name" placeholder="Enter your email">
                        </div>
                        <div class="form-group m-b-5 ">
                            <label class="">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Enter your password">
                        </div>
                        <div class="form-group form-inline text-left mt-3">
                            <div class="form-check">
                                <label>
                                    <input type="checkbox" name="remember">
                                    <small class="m-l-10"> Remember me</small>
                                </label>
                            </div>
                        </div>
                        <div class="text-right form-group">
                            <a href="{{ url('login-otp') }}" class="">Login with OTP</a>
                            <button type="submit" class="btn">Login</button>
                        </div>
                    </form>

                    <div class="mt-4 text-center mb-4">
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-muted"><i class="mdi mdi-lock me-1"></i> Forgot Password?</a>
                        @endif
                    </div>

                    <p class="small text-center">Don't have an account? <a href="{{ url('register') }}">Register</a></p>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    @include('web.includes.footer')
@endsection
