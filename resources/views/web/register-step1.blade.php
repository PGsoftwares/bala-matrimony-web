@extends('web.layouts.layout')
@section('title', $metaTags->title ?? 'Personal Details')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <section class="container-fluid">

        <div class="card border-0 p-4 mt-3 mb-5 shadow rounded-4 gradient-bg-primary">
            <div class="row">

                <div class="col-md-4 d-flex flex-column justify-content-between text-white" style="height: 500px">
                    <div>
                        <h5 class="fw-bold">Personal Details</h5>
                        <p>Please fill with your personal details</p>
                    </div>
                    <div class="d-flex justify-content-center text-center">
                        <img src="{{ asset('asset/img/registration.png') }}" alt="registration" class="img-fluid" style="max-height: 400px;">
                    </div>
                </div>

                <div class="col-md-8 d-flex flex-column" >
                    <div class="card shadow border-0 p-0 d-flex flex-column" >

                        <div class="p-3">
                            <h5 class="fw-semibold mb-0">Basic Details</h5>
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                        </div>

                        <div class="form-body px-3 overflow-auto" style="flex: 1 1 auto;">
                            <form method="POST" action="{{ route('storeRegisterStep1') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user_id }}">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label for="dob" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control rounded-pill shadow-none @error('dob') is-invalid @enderror" name="dob" id="dob" value="{{ old('dob') }}" required>
                                        @error('dob')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Birth Time <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <!-- Hours -->
                                            <select class="form-control rounded-start-pill shadow-none @error('birth_time') is-invalid @enderror @error('hour') is-invalid @enderror" name="hour" required>
                                                <option value="" disabled selected>Hour</option>
                                                @for ($i = 1; $i <= 12; $i++)
                                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                                        {{ old('hour') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                                        {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                                    </option>
                                                @endfor
                                            </select>

                                            <!-- Minutes -->
                                            <select class="form-control shadow-none @error('birth_time') is-invalid @enderror @error('minute') is-invalid @enderror" name="minute" required>
                                                <option value="" disabled selected>Minute</option>
                                                @for ($i = 0; $i < 60; $i++)
                                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                                        {{ old('minute') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                                        {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                                    </option>
                                                @endfor
                                            </select>

                                            <!-- AM/PM -->
                                            <select class="form-control rounded-end-pill shadow-none @error('birth_time') is-invalid @enderror @error('ampm') is-invalid @enderror" name="ampm" required>
                                                <option value="" disabled selected>AM/PM</option>
                                                <option value="AM" {{ old('ampm') == 'AM' ? 'selected' : '' }}>AM</option>
                                                <option value="PM" {{ old('ampm') == 'PM' ? 'selected' : '' }}>PM</option>
                                            </select>
                                        </div>
                                        @error('birth_time')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        @error('hour')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                        @error('ampm')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="birth_country" class="form-label">Birth Country <span class="text-danger">*</span></label>
                                        <select id="birth_country" class="form-select rounded-pill shadow-none @error('birth_country') is-invalid @enderror" name="birth_country" required>
                                            <option value="">Select Country</option>
                                            @foreach($db['countries'] as $country)
                                                <option value="{{ $country }}" {{ old('birth_country') == $country ? 'selected' : '' }}>{{ $country }}</option>
                                            @endforeach
                                        </select>
                                        @error('birth_country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="birth_state" class="form-label">Birth State <span class="text-danger">*</span></label>
                                        <select id="birth_state" class="form-select rounded-pill shadow-none @error('birth_state') is-invalid @enderror" name="birth_state" required>
                                            <option value="">Select State</option>
                                        </select>
                                        @error('birth_state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="birth_city" class="form-label">Birth City <span class="text-danger">*</span></label>
                                        <select id="birth_city" class="form-select rounded-pill shadow-none @error('birth_city') is-invalid @enderror" name="birth_city" required>
                                            <option value="">Select City</option>
                                        </select>
                                        @error('birth_city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>


                                    <div class="col-md-6">
                                        <label for="mother_tongue" class="form-label">Mother Tongue <span class="text-danger">*</span></label>
                                        <select id="mother_tongue" class="form-select rounded-pill shadow-none @error('mother_tongue') is-invalid @enderror" name="mother_tongue" required>
                                            <option value="">Select Mother Tongue</option>
                                            @foreach($db['languages'] as $language)
                                                <option value="{{ $language->language }}" {{ old('mother_tongue') == $language->language ? 'selected' : '' }}>{{ $language->language }}</option>
                                            @endforeach
                                        </select>
                                        @error('mother_tongue')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="marital_status" class="form-label">Marital Status <span class="text-danger">*</span></label>
                                        <select id="marital_status" class="form-select rounded-pill shadow-none @error('marital_status') is-invalid @enderror" name="marital_status" required>
                                            <option value="">Select Marital Status</option>
                                            @foreach($db['maritalStatuses'] as $maritalStatus)
                                                <option value="{{ $maritalStatus->name }}" {{ old('marital_status') == $maritalStatus->name ? 'selected' : '' }}>{{ $maritalStatus->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('marital_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="religion" class="form-label">Religion <span class="text-danger">*</span></label>
                                        <select id="religion" class="form-select rounded-pill shadow-none @error('religion') is-invalid @enderror" name="religion" required>
                                            <option value="">Select Religion</option>
                                            @foreach($db['religions'] as $religion)
                                                <option value="{{ $religion->name }}" {{ old('religion') == $religion->name ? 'selected' : '' }}>{{ $religion->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('religion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="caste" class="form-label">Caste <span class="text-danger">*</span></label>
                                        <select id="caste" class="form-select rounded-pill shadow-none @error('caste') is-invalid @enderror" name="caste" required>
                                            <option value="">Select Caste</option>
                                            @foreach($db['castes'] as $caste)
                                                <option value="{{ $caste }}" {{ old('caste') == $caste ? 'selected' : '' }}>{{ $caste }}</option>
                                            @endforeach
                                        </select>
                                        @error('caste')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="sub_caste" class="form-label">Sub Caste <span class="text-danger">*</span></label>
                                        <select id="sub_caste" class="form-select rounded-pill shadow-none @error('sub_caste') is-invalid @enderror" name="sub_caste" required>
                                            <option value="">Select Sub Caste</option>
                                        </select>
                                        @error('sub_caste')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="ethnicity" class="form-label">Ethnicity</label>
                                        <select id="ethnicity" class="form-select rounded-pill shadow-none @error('ethnicity') is-invalid @enderror" name="ethnicity">
                                            <option value="">Select Ethnicity</option>
                                            @foreach($db['ethnicity'] as $ethnicity)
                                                <option value="{{ $ethnicity->name }}" {{ old('ethnicity') == $ethnicity->name ? 'selected' : '' }}>{{ $ethnicity->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('ethnicity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="nationality" class="form-label">Nationality</label>
                                        <select id="nationality" class="form-select rounded-pill shadow-none @error('nationality') is-invalid @enderror" name="nationality">
                                            <option value="">Select Nationality</option>
                                            @foreach($db['nationality'] as $nationality)
                                                <option value="{{ $nationality->name }}" {{ old('nationality') == $nationality->name ? 'selected' : '' }}>{{ $nationality->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('nationality')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>


                                    <div class="col-md-6">
                                        <label for="profile_image" class="form-label">Profile Image</label>
                                        <input type="file" class="form-control rounded-pill shadow-none @error('profile_image') is-invalid @enderror"  id="profileImageInput">
                                        @error('profile_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        <small class="form-text text-muted">Upload maximum 1 mb of your image</small>
                                        <input type="hidden" name="profile_image" id="croppedImageInput">
                                    </div>

                                    <div>
                                        <div class="image-container" style="max-width: 100%;max-height: 400px; margin-bottom: 10px;">
                                            <img id="previewImage" style="max-width: 100%; display:none;" alt="" src="">
                                        </div>
                                        <button type="button" id="cropButton" class="btn button2" style="display:none;">Crop Image</button>
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

    <link href="{{ asset('asset/cropper/cropper.min.css') }}" rel="stylesheet">
    <script src="{{ asset('asset/cropper/cropper.js') }}"></script>
    <script src="{{ asset('asset/cropper/image-cropper.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new ImageCropper({
                inputId: 'profileImageInput',
                previewId: 'previewImage',
                croppedInputId: 'croppedImageInput',
                cropButtonId: 'cropButton',
                width: 300,
                height: 300,
                aspectRatio: 1
            });
        });

        document.querySelector('select[name="gender"]').addEventListener('change', function(e) {
            const dobInput = document.getElementById('dob');
            const selectedGender = e.target.value;

            if (selectedGender === 'Female') {
                dobInput.max = new Date(new Date().setFullYear(new Date().getFullYear() - 18)).toISOString().split('T')[0];
            } else if (selectedGender === 'Male') {
                dobInput.max = new Date(new Date().setFullYear(new Date().getFullYear() - 21)).toISOString().split('T')[0];
            }

            if (dobInput.value && new Date(dobInput.value) > new Date(dobInput.max)) {
                dobInput.value = '';
            }
        });

        window.addEventListener('load', function() {
            const genderSelect = document.querySelector('select[name="gender"]');
            if (genderSelect.value) {
                genderSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
    @include('web.includes.footer')
@endsection
