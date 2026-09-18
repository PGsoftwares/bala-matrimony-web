@extends('web.layouts.layout')
@section('title', $metaTags->title ?? 'Horoscope Details')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <section class="container-fluid">

        <div class="card border-0 p-4 mt-3 mb-5 shadow rounded-4 gradient-bg-primary">
            <div class="row">

                <div class="col-md-4 d-flex flex-column justify-content-between text-white" style="height: 500px">
                    <div>
                        <h5 class="fw-bold">Horoscope Details</h5>
                        <p>Please fill with your horoscope details</p>
                    </div>
                    <div class="d-flex justify-content-center text-center">
                        <img src="{{ asset('asset/img/registration.png') }}" alt="registration" class="img-fluid" style="max-height: 400px;">
                    </div>
                </div>

                <div class="col-md-8 d-flex flex-column" style="height: 500px;">
                    <div class="card shadow border-0 p-0 d-flex flex-column h-100" >

                        <div class="p-3">
                            <h5 class="fw-semibold mb-0">Horoscope Details</h5>
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                        </div>

                        <div class="form-body px-3 overflow-auto" style="flex: 1 1 auto;">
                            <form id="horoscope-form" method="POST" action="{{ route('storeRegisterStep6') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user_id }}">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label for="rashi" class="form-label">Rashi <span class="text-danger">*</span></label>
                                        <select id="rashi" class="form-select rounded-pill shadow-none @error('rashi') is-invalid @enderror" name="rashi">
                                            <option value="">Select Rashi</option>
                                            @foreach($db['rashies'] as $rashi)
                                                <option value="{{ $rashi->name }}" {{ old('rashi') == $rashi->name ? 'selected' : '' }}>{{ $rashi->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('rashi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="nakshatra" class="form-label">Nakshatra <span class="text-danger">*</span></label>
                                        <select id="nakshatra" class="form-select rounded-pill shadow-none @error('nakshatra') is-invalid @enderror" name="nakshatra">
                                            <option value="">Select Nakshatra</option>
                                            @foreach($db['nakshatras'] as $star)
                                                <option value="{{ $star->name }}" {{ old('nakshatra') == $star->name ? 'selected' : '' }}>{{ $star->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('nakshatra')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="gothram" class="form-label">Gothram</label>
                                        <select id="gothram" class="form-select rounded-pill shadow-none" name="gothram">
                                            <option value="">Select Gothram</option>
                                            @foreach($db['gothrams'] as $gothram)
                                                <option value="{{ $gothram->name }}" {{ old('gothram') == $gothram->name ? 'selected' : '' }}>{{ $gothram->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="dosham" class="form-label">Dosha <span class="text-danger">*</span></label>
                                        <select id="dosham" class="form-select rounded-pill shadow-none @error('dosham') is-invalid @enderror" name="dosham">
                                            <option value="">Select Dosha</option>
                                            @foreach($db['dosham'] as $dosham)
                                                <option value="{{ $dosham->name }}" {{ old('dosham') == $dosham->name ? 'selected' : '' }}>{{ $dosham->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('dosham')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="horoscopeImageInput" class="form-label">Horoscope Image</label>
                                        <input type="file" class="form-control rounded-pill shadow-none"  id="horoscopeImageInput">
                                        <input type="hidden" name="horoscope_image" id="horoscopeCroppedImageInput">
                                    </div>

                                    <div>
                                        <div class="image-container" style="max-width: 100%; margin-bottom: 10px;">
                                            <img id="horoscopePreviewImage" style="max-width: 100%;  display:none;" alt="" src="">
                                        </div>
                                        <button type="button" id="horoscopeCropButton" class="btn button2 mb-2" style="display:none;">Crop Image</button>
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
{{--                                <a href="{{ url('family-details') }}" class="btn button1 rounded-pill">Back</a>--}}
                                <button form="horoscope-form" type="submit" class="btn button2 rounded-pill">Save & Next</button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <link href="{{ asset('asset/cropper/cropper.min.css') }}" rel="stylesheet">
    <script src="{{ asset('asset/cropper/cropper.js') }}"></script>
    <script src="{{ asset('asset/cropper/image-cropper.js') }}"></script>
    <script>
        new ImageCropper({
            inputId: 'horoscopeImageInput',
            previewId: 'horoscopePreviewImage',
            croppedInputId: 'horoscopeCroppedImageInput',
            cropButtonId: 'horoscopeCropButton',
            aspectRatio: 16/9
        });
    </script>
    @include('web.includes.footer')
@endsection
