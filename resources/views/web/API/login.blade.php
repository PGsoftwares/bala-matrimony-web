@extends('web.layouts.layout')

@section('title', $metaTags->title)
@section('description', $metaTags->description)
@section('keywords', $metaTags->keywords)

@section('content')
    <header id="header" data-transparent="true" data-fullwidth="true" class="dark  submenu-light shadow ">
        <div class="header-inner ">
            <div class="container ">
                <!-- Logo -->
                <div id="logo">
                    <a href="{{ url('login?access=app') }}">
                    <span class="logo-default" >
                        <img src="{{ asset('web/images/logo.png' ) }}" alt="Bala Matrimony Bureau" style="width: 300px; height: 80px">
                    </span>
                        <span class="logo-dark" >
                        <img src="{{ asset('web/images/logo.png') }}" alt="Bala Matrimony Bureau" style="width: 300px; height: 80px">
                    </span>
                    </a>
                </div>
                <!-- End: Logo -->

                <!-- Navigation Responsive Trigger -->
                <div id="mainMenu-trigger">
                    <a class="lines-button x"><span class="lines theme-bg"></span></a>
                </div>
                <!-- End: Navigation Responsive Trigger -->


            </div>
        </div>
    </header>


    <section class=" " data-bg-parallax="{{ asset('web/assets/images/auth/login.webp') }}">
        <div class="bg-overlay"></div>
        <div id="particles-snow" class="particles"></div>
        <div class="container mt-md-0">
            <div>

                <div class="row">
                    <div class="col-lg-5 center p-30 background-white b-r-6">
                        <h3 class=" text-center tit">Login</h3>
                        <p class="text-center">Login in your account now.</p>
                        @if ($errors->has('login_name'))
                            <div class="alert alert-danger">
                                {{ $errors->first('login_name') }}
                            </div>
                        @endif

                        @if ($errors->has('account'))
                            <div class="alert alert-danger">
                                {{ $errors->first('account') }}
                            </div>
                        @endif

                        <form method="post" action="{{ route('login') }}">
                            @csrf
                            <input type="hidden" name="access" value="app">
                            <div class="form-group">
                                <label class="">Email</label>
                                <input type="text" class="form-control" name="login_name" placeholder="Enter your email">
                            </div>
                            <div class="form-group m-b-5">
                                <label for="password">Password</label>
                                <div class="input-group show-hide-password">
                                    <input class="form-control" name="password" placeholder="Enter password" type="password">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="icon-eye-off" aria-hidden="true" style="cursor: pointer;"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-inline text-left mt-3">
                                <div class="form-check">
                                    <label>
                                        <input type="checkbox" name="remember">
                                        <small class="m-l-10"> Remember me</small>
                                    </label>
                                </div>
                            </div>
                            <div class="text-right form-group justify-content-between">
                                <button type="submit" class="btn">Login</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
@endsection
