@extends('web.layouts.layout')
@section('title', $metaTags->title ?? 'Physical Details')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <section class="container-fluid">

        <div class="card border-0 p-4 mt-3 mb-5 shadow rounded-4 gradient-bg-primary">
            <div class="row">

                <div class="col-md-4 d-flex flex-column justify-content-between text-white" style="height: 500px">
                    <div>
                        <h5 class="fw-bold">Physical Details</h5>
                        <p>Please fill with your physical details</p>
                    </div>
                    <div class="d-flex justify-content-center text-center">
                        <img src="{{ asset('asset/img/registration.png') }}" alt="registration" class="img-fluid" style="max-height: 400px;">
                    </div>
                </div>

                <div class="col-md-8 d-flex flex-column" >
                    <div class="card shadow border-0 p-0 d-flex flex-column" >

                        <div class="p-3">
                            <h5 class="fw-semibold mb-0">Physical Details</h5>
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                        </div>

                        <div class="form-body px-3 overflow-auto" style="flex: 1 1 auto;">
                            <form method="POST" action="{{ route('storeRegisterStep2') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user_id }}">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label for="physical_status" class="form-label">Physical Status <span class="text-danger">*</span></label>
                                        <select id="physical_status" class="form-select rounded-pill shadow-none @error('physical_status') is-invalid @enderror" name="physical_status">
                                            <option value="">Select Physical Status</option>
                                            <option value="Normal" {{ old('physical_status') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                            <option value="Physically Challenged" {{ old('physical_status') == 'Physically Challenged' ? 'selected' : '' }}>Physically Challenged</option>
                                        </select>
                                        @error('physical_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="skin_tone" class="form-label">Skin Tone <span class="text-danger">*</span></label>
                                        <select id="skin_tone" class="form-select rounded-pill shadow-none @error('skin_tone') is-invalid @enderror" name="skin_tone" required>
                                            <option value="">Select Skin Tone</option>
                                            @foreach($db['skinTones'] as $skinTone)
                                                <option value="{{ $skinTone->name }}" {{ old('skin_tone') == $skinTone->name ? 'selected' : '' }}>{{ $skinTone->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('skin_tone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="height" class="form-label">Height <span class="text-danger">*</span></label>
                                        <select id="height" class="form-select rounded-pill shadow-none @error('height') is-invalid @enderror" name="height" required>
                                            <option value="">Select Height</option>
                                            @foreach($db['heights'] as $height)
                                                <option value="{{ $height->name }}" {{ old('height') == $height->name ? 'selected' : '' }}>{{ $height->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('height')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="weight" class="form-label">Weight (in Kg) <span class="text-danger">*</span></label>
                                        <input class="form-control rounded-pill shadow-none @error('weight') is-invalid @enderror" type="number" name="weight" id="weight" placeholder="Enter weight in Kg" value="{{ old('weight') }}" required>
                                        @error('weight')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="body_type" class="form-label">Body Type <span class="text-danger">*</span></label>
                                        <select id="body_type" class="form-select rounded-pill shadow-none @error('body_type') is-invalid @enderror" name="body_type" required>
                                            <option value="">Select Body Type</option>
                                            @foreach($db['bodyTypes'] as $bodyType)
                                                <option value="{{ $bodyType->name }}" {{ old('body_type') == $bodyType->name ? 'selected' : '' }}>{{ $bodyType->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('body_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                </div>

                                <div class="bg-white border-0 p-3 d-flex justify-content-between align-items-center shadow-top sticky-bottom">
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn button2 rounded-pill">Logout</button>
                                    </form>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn button2 rounded-pill">Save & Next</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </section>

    @include('web.includes.footer')
@endsection
