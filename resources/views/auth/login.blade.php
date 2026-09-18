@extends('web.layouts.layout')
@section('title', $metaTags->title)
@section('description', $metaTags->description)
@section('keywords', $metaTags->keywords)

@section('content')
    @include('web.includes.header')

    <section class="container-fluid p-0">
        <div class="row g-0 min-vh-100">

            <!-- Left Full Image Column -->
            <div class="col-md-6 d-md-block d-none">
                <img src="{{ asset('asset/img/IMG01.png') }}" alt="Left Image" class="img-fluid w-100 h-100" style="object-fit: cover;">
            </div>

            <!-- Right Centered Form -->
            <div class="col-md-6 d-flex mb-md-0 mb-5 align-items-center justify-content-center p-4">
                <div class="card shadow rounded-4 w-100" style="max-width: 450px;">
                    <div class="card-body p-4">
                        @if ($errors->has('login_name'))
                            <div class="alert alert-danger">{{ $errors->first('login_name') }}</div>
                        @endif
                        @if ($errors->has('account'))
                            <div class="alert alert-info">{{ $errors->first('account') }}</div>
                        @endif
                        @if(session('message'))
                            <div class="alert alert-info">{{ session('message') }}</div>
                        @endif
                            @if(session('status'))
                                <div class="alert alert-success">{{ session('status') }}</div>
                            @endif
                        <h4 class="fw-bold text-center mb-2">Login</h4>
                        <p class="text-muted text-center mb-4">Login to your account</p>

                        <form method="post" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <input type="text" name="login_name" class="form-control rounded-pill shadow-none" placeholder="Email/Phone">
                            </div>
                            <div class="mb-3">
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control rounded-pill shadow-none" placeholder="Password">
                                    <span class="input-group-text rounded-pill shadow-none" onclick="togglePassword()" style="cursor: pointer; margin-left: -45px; z-index: 5;">👁️</span>
                                </div>
                            </div>

                            <!-- Remember Me -->
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex gap-2 mb-3">
                                <button type="submit" class="btn button2 w-100 rounded-pill">Login</button>
                                <a href="{{ url('login-otp') }}" class="btn button1 w-100 rounded-pill">Login with OTP</a>
                            </div>

                            <!--Links -->
                            <div class="d-flex justify-content-between small">
                                @if(Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-decoration-none">Forgot password?</a>
                                @endif
                                <span>Don't have an account? <a href="{{ url('register') }}" class="primary_color">Register</a></span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <script>
        function togglePassword() {
            const input = document.getElementById("password");
            input.type = input.type === "password" ? "text" : "password";
        }
    </script>
    @include('web.includes.footer')
@endsection
