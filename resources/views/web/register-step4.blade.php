@extends('web.layouts.layout')
@section('title', $metaTags->title ?? 'Professional Details')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <section class="container-fluid">

        <div class="card border-0 p-4 mt-3 mb-5 shadow rounded-4 gradient-bg-primary">
            <div class="row">

                <div class="col-md-4 d-flex flex-column justify-content-between text-white" >
                    <div>
                        <h5 class="fw-bold">Professional Details</h5>
                        <p>Please fill with your professional details</p>
                    </div>
                    <div class="d-flex justify-content-center text-center">
                        <img src="{{ asset('asset/img/registration.png') }}" alt="registration" class="img-fluid" style="max-height: 400px;">
                    </div>
                </div>

                <div class="col-md-8 d-flex flex-column" >
                    <div class="card shadow border-0 p-0 d-flex flex-column h-100" >

                        <div class="p-3">
                            <h5 class="fw-semibold mb-0">Professional Details</h5>
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                        </div>

                        <div class="form-body px-3 overflow-auto" style="flex: 1 1 auto;">
                            <form id="education-job-form" method="POST" action="{{ route('storeRegisterStep4') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user_id }}">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label for="educations" class="form-label">Education <span class="text-danger">*</span></label>
                                        @php
                                            $selectedEducations = !empty(old('education'))
                                                ? (is_array(old('education')) ? old('education') : array_map('trim', explode(',', old('education'))))
                                                : (!empty($userAndUserDetails->education) ? array_map('trim', explode(',', $userAndUserDetails->education)) : []);
                                        @endphp
                                        <select id="educations" name="education[]" class="form-select rounded-pill select2 shadow-none @error('education') is-invalid @enderror" multiple="multiple" data-placeholder="Select Education">
                                            @foreach($db['combinedEducations'] as $level)
                                                <optgroup label="{{ $level->level_name }}">
                                                    @foreach($level->educations as $education)
                                                        <option value="{{ $education->name }}" {{ in_array($education->name, $selectedEducations) ? 'selected' : '' }}>{{ $education->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                            @php
                                                $allStandardEducations = collect($db['combinedEducations'] ?? [])->pluck('educations')->flatten()->pluck('name')->toArray();
                                                $customEducations = array_diff($selectedEducations, $allStandardEducations);
                                            @endphp
                                            @foreach($customEducations as $customEdu)
                                                @if(!empty($customEdu))
                                                    <option value="{{ $customEdu }}" selected>{{ $customEdu }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('education')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="occupations" class="form-label">Occupation <span class="text-danger">*</span></label>
                                        <select id="occupations" name="occupation" class="form-select rounded-pill shadow-none @error('occupation') is-invalid @enderror" required>
                                            <option value="">Select Occupation</option>
                                            @foreach($db['combinedOccupations'] as $combinedOccupation)
                                                <optgroup label="{{ $combinedOccupation->type_name }}">
                                                    @foreach($combinedOccupation->occupations as $occupation)
                                                        <option value="{{ $occupation->name }}" {{ old('occupation', $userAndUserDetails->occupation ?? '') == $occupation->name ? 'selected' : '' }}>{{ $occupation->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        @error('occupation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="employed_in" class="form-label">Employed In <span class="text-danger">*</span></label>
                                        <select id="employed_in" class="form-select rounded-pill shadow-none @error('employed_in') is-invalid @enderror" name="employed_in" required>
                                            <option value="">Select Employed In</option>
                                            @foreach($db['employedIns'] as $employedIn)
                                                <option value="{{ $employedIn->name }}" {{ old('employed_in', $userAndUserDetails->employed_in ?? '') == $employedIn->name ? 'selected' : '' }}>{{ $employedIn->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('employed_in')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="monthly_income" class="form-label">Monthly Income <span class="text-danger">*</span></label>
                                        <select id="monthly_income" class="form-select rounded-pill shadow-none @error('monthly_income') is-invalid @enderror" name="monthly_income" required>
                                            <option value="">Select Monthly Income</option>
                                            @foreach($db['salaries'] as $salary)
                                                <option value="{{ $salary->name }}" {{ old('monthly_income', $userAndUserDetails->monthly_income ?? '') == $salary->name ? 'selected' : '' }}>{{ $salary->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('monthly_income')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="work_country" class="form-label">Work Country</label>
                                        <select id="work_country" class="form-select rounded-pill shadow-none" name="work_country">
                                            <option value="">Select Work Country</option>
                                            @foreach($db['countries'] as $country)
                                                <option value="{{ $country }}" {{ old('work_country', $userAndUserDetails->work_country ?? '') == $country ? 'selected' : '' }}>{{ $country }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="visa_status" class="form-label">Visa Status</label>
                                        <select id="visa_status" class="form-select rounded-pill shadow-none" name="visa_status">
                                            <option value="">Select Visa Status</option>
                                            @foreach($db['visaStatus'] as $visaStatus)
                                                <option value="{{ $visaStatus->name }}" {{ old('visa_status', $userAndUserDetails->visa_status ?? '') == $visaStatus->name ? 'selected' : '' }}>{{ $visaStatus->name }}</option>
                                            @endforeach
                                        </select>
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
{{--                                <a href="{{ url('personal-details') }}" class="btn button1 rounded-pill">Back</a>--}}
                                <button form="education-job-form" type="submit" class="btn button2 rounded-pill">Save & Next</button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <link href="{{ asset('asset/css/select2.min.css') }}" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--multiple {
            min-height: 38px;
            border: 1px solid #dee2e6;
            border-radius: 50rem !important;
            padding: 2px 10px;
            background-color: #fff;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #0d6b38;
            box-shadow: 0 0 0 0.25rem rgba(13, 107, 56, 0.25);
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #0d6b38;
            border: 1px solid #0d6b38;
            color: #fff;
            border-radius: 20px;
            padding: 2px 8px;
            margin-top: 4px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff;
            margin-right: 5px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #eee;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
            padding-left: 20px;
        }
        .select2-container {
            width: 100% !important;
        }
    </style>
    <script src="{{ asset('asset/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: 'Select Education',
                width: '100%'
            });
        });
    </script>
    @include('web.includes.footer')
@endsection
