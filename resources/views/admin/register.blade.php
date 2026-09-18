@extends('admin.layouts.layout')
@section('title', 'Admin - Registration')

@section('content')
    @include('admin.includes.header')
    @include('admin.includes.sidebar')

    <div id="layout-wrapper">
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <section class="p-3 p-md-4 p-xl-5">
                        <div class="container shadow-lg ">
                            <div class="row">
                                <div class="col-12 col-md-6" style="background-color: #0d6b38; border-radius: 10px">
                                    <div class="d-flex flex-column justify-content-between h-100 p-3 p-md-4 p-xl-5">
                                        <h3 class="m-0 text-white">Welcome!</h3>
                                        <img class="img-fluid rounded mx-auto my-4" loading="lazy" src="{{asset('asset/img/logo/fav-icon-pg.png')}}" width="245" height="80" alt="">
                                        <p class="mb-0 text-white">Join with us! <a href="#" class="link-dark text-decoration-none">Bala Matrimony Bureau</a></p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 bsb-tpl-bg-lotion" style="border-radius: 0 10px 10px 0; background-color: #FFFFFF">
                                    <div class="p-3 p-md-4 p-xl-5">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="mb-5">
                                                    <h2 class="h3">Registration</h2>
                                                    <h3 class="fs-6 fw-normal text-secondary m-0">Enter your details to register</h3>
                                                    @if(session('success'))
                                                        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <form action="{{ route('admin.register') }}" method="POST">
                                            @csrf
                                            <div class="row gy-3 gy-md-4 overflow-hidden">
                                                <input type="hidden" class="form-control" name="role" value="standard" >

                                                <div class="col-12">
                                                    <select id="profile_for" class="form-select shadow-none @error('profile_for') is-invalid @enderror" name="profile_for">
                                                        <option value="">Select Profile for</option>
                                                        <option value="Self" {{ old('profile_for') == 'Self' ? 'selected' : '' }}>Self</option>
                                                        <option value="Son" {{ old('profile_for') == 'Son' ? 'selected' : '' }}>Son</option>
                                                        <option value="Daughter" {{ old('profile_for') == 'Daughter' ? 'selected' : '' }}>Daughter</option>
                                                        <option value="Brother" {{ old('profile_for') == 'Brother' ? 'selected' : '' }}>Brother</option>
                                                        <option value="Sister" {{ old('profile_for') == 'Sister' ? 'selected' : '' }}>Sister</option>
                                                        <option value="Friend" {{ old('profile_for') == 'Friend' ? 'selected' : '' }}>Friend</option>
                                                        <option value="Relative" {{ old('profile_for') == 'Relative' ? 'selected' : '' }}>Relative</option>
                                                    </select>
                                                    @error('profile_for')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-12 mt-2">
                                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter Name" value="{{ old('name') }}" >
                                                    @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-12 mt-2">
                                                    <select class="form-select shadow-none @error('gender') is-invalid @enderror" name="gender">
                                                        <option value="">Select Gender</option>
                                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                                    </select>
                                                    @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-12 mt-2">
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Enter Email" value="{{ old('email') }}" >
                                                    @error('email')<div class="text-danger">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-12 mt-2">
                                                    <input type="tel" class="form-control @error('mobile') is-invalid @enderror" id="phone" name="mobile"  value="{{ old('mobile') }}" placeholder="Enter mobile number">
                                                    <input type="hidden" name="country_code" id="phone_country_code">
                                                    @error('mobile')<div class="text-danger">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-12 mt-2">
                                                    <div class="input-group">
                                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter Your Password" >
                                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('password', this)">
                                                            <i class="mdi mdi-eye-outline"></i>
                                                        </button>
                                                    </div>
                                                    @error('password')<div class="text-danger">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-12 mt-2">
                                                    <div class="d-grid">
                                                        <button class="btn bsb-btn-xl btn-primary" type="submit">Register</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
            </div>
        </div>
    </div>

    <script src="{{asset('asset/dialcode/dial-code.js')}}"></script>
    <script src="{{asset('asset/dialcode/intlTelInput.min.js')}}"></script>
    <script src="{{asset('asset/dialcode/utils.js')}}"></script>
    @include('admin.includes.footer')
@endsection
