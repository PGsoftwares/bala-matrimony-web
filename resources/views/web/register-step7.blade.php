@extends('web.layouts.layout')
@section('title', $metaTags->title ?? 'Address Details')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <section class="container-fluid">

        <div class="card border-0 p-4 mt-3 mb-5 shadow rounded-4 gradient-bg-primary">
            <div class="row">

                <div class="col-md-4 d-flex flex-column justify-content-between text-white" style="height: 500px">
                    <div>
                        <h5 class="fw-bold">Address Details</h5>
                        <p>Please fill with your address details</p>
                    </div>
                    <div class="d-flex justify-content-center text-center">
                        <img src="{{ asset('asset/img/registration.png') }}" alt="registration" class="img-fluid" style="max-height: 400px;">
                    </div>
                </div>

                <div class="col-md-8 d-flex flex-column" style="height: 500px;">
                    <div class="card shadow border-0 p-0 d-flex flex-column h-100" >

                        <div class="p-3">
                            <h5 class="fw-semibold mb-0">Address Details</h5>
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                        </div>

                        <div class="form-body px-3 overflow-auto" style="flex: 1 1 auto;">
                            <form id="address-form" method="POST" action="{{ route('storeRegisterStep7') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user_id }}">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                                        <select id="country" class="form-select rounded-pill shadow-none @error('country') is-invalid @enderror" name="country">
                                            <option value="">Select Country</option>
                                            @foreach($db['countries'] as $country)
                                                <option value="{{ $country }}">{{ $country }}</option>
                                            @endforeach
                                        </select>
                                        @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                                        <select id="state" class="form-select rounded-pill shadow-none @error('state') is-invalid @enderror" name="state">
                                            <option value="">Select State</option>
                                        </select>
                                        @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                        <select id="city" class="form-select rounded-pill shadow-none @error('city') is-invalid @enderror" name="city">
                                            <option value="">Select City</option>
                                        </select>
                                        @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="pin_code" class="form-label">Pincode <span class="text-danger">*</span></label>
                                        <input type="text" name="pin_code" id="pin_code" class="form-control rounded-pill shadow-none @error('pin_code') is-invalid @enderror" placeholder="Enter Pincode" value="{{ old('pin_code') }}" required>
                                        @error('pin_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                        <textarea name="address" id="address" rows="3" class="form-control rounded-4 shadow-none @error('address') is-invalid @enderror" placeholder="Enter Address" required>{{ old('address') }}</textarea>
                                        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                </div>

                            </form>
                        </div>

                        <div class="bg-white border-0 p-3 d-flex justify-content-between align-items-center shadow-top sticky-bottom">
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="btn button2 rounded-pill">Logout</button>
                            </form>
                            <div class="d-flex gap-2">
{{--                                <a href="{{ url('horoscope-details') }}" class="btn button1 rounded-pill">Back</a>--}}
                                <button form="address-form" type="submit" class="btn button2 rounded-pill">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    @include('web.includes.footer')
@endsection
