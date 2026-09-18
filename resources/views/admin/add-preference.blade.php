@extends('admin.layouts.layout')
@section('title', 'Admin | Partner Preference')

@section('content')
    @include('admin.includes.header')
    @include('admin.includes.sidebar')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">{{ $user->name }} - ({{  $user->id  }})</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Partner Preferences</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @php
                    $selectedMotherTongues = isset($preference->mother_tongue) ? explode(',', $preference->mother_tongue) : [];
                    $selectedMarital = isset($preference->marital_status) ? explode(',', $preference->marital_status) : [];
                    $selectedDrinkingHabits = isset($preference->drinking_habit) ? explode(',', $preference->drinking_habit) : [];
                    $selectedSmokingHabits = isset($preference->smoking_habit) ? explode(',', $preference->smoking_habit) : [];
                    $selectedEatingHabits = isset($preference->eating_habit) ? explode(',', $preference->eating_habit) : [];
                    $selectedPhysicalStatus = isset($preference->physical_status) ? explode(',', $preference->physical_status) : [];
                    $selectedReligion = isset($preference->religion) ? explode(',', $preference->religion) : [];
                    $selectedCaste = isset($preference->caste) ? explode(',', $preference->caste) : [];
                    $selectedEducations = isset($preference->education) ? explode(',', $preference->education) : [];
                    $selectedOccupations = isset($preference->occupation) ? explode(',', $preference->occupation) : [];
                    $selectedEmployedIn = isset($preference->employed_in) ? explode(',', $preference->employed_in) : [];
                    $selectedRashi = isset($preference->rashi) ? explode(',', $preference->rashi) : [];
                    $selectedDoshams = isset($preference->dosham) ? explode(',', $preference->dosham) : [];
                    $selectedCountries = isset($preference->country) ? explode(',', $preference->country) : [];
                    $selectedStates = isset($preference->state) ? explode(',', $preference->state) : [];
                    $selectedCities = isset($preference->city) ? explode(',', $preference->city) : [];
                    $selectedEthnicities = isset($preference->ethnicity) ? explode(',', $preference->ethnicity) : [];
                    $selectedNationalities = isset($preference->nationality) ? explode(',', $preference->nationality) : [];
                    $selectedWorkCountry = isset($preference->work_country) ? explode(',', $preference->work_country) : [];
                    $selectedVisaStatus = isset($preference->visa_status) ? explode(',', $preference->visa_status) : [];
                @endphp

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Personal Details</h4>
                        <form method="POST" action="{{ route('storePreference', $user->id) }}">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="min_age">Age From</label>
                                    <select id="min_age" name="min_age" class="form-control">
                                        <option value="">Select Age From</option>
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="max_age">Age To</label>
                                    <select id="max_age" name="max_age" class="form-control">
                                        <option value="">Select Age To</option>
                                    </select>
                                </div>

                                <style>
                                    .custom-multiselect {
                                        position: relative;
                                        width: 100%;
                                        border: 1px solid #ccc;
                                        border-radius: 4px;
                                    }
                                    .select-box {
                                        padding: 8px;
                                        cursor: pointer;
                                        background-color: #fff;
                                    }
                                    .checkboxes {
                                        display: none;
                                        border: 1px solid #ccc;
                                        max-height: 200px;
                                        overflow-y: auto;
                                        position: absolute;
                                        width: 100%;
                                        background-color: #fff;
                                        z-index: 1;
                                    }
                                    .checkboxes label {
                                        display: block;
                                        padding: 5px 10px;
                                    }
                                    .checkboxes.show {
                                        display: block;
                                    }
                                    .checkboxes label {
                                        display: block;
                                        padding: 5px 10px;
                                    }
                                </style>

                                {{-- Mother Tongue --}}
                                <div class="col-lg-4 mb-3">
                                    <label for="mother_tongue">Mother Tongue</label>
                                    <select name="mother_tongue[]" id="mother_tongue" class="form-control select2" multiple>
                                        @foreach($db['languages'] as $lang)
                                            <option value="{{ $lang->language }}" {{ in_array($lang->language, $selectedMotherTongues) ? 'selected' : '' }}>
                                                {{ $lang->language }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="height_from">Height From</label>
                                    <select id="height_from" class="form-select select2" name="height_from">
                                        <option value="">Select Height From</option>
                                        @foreach($db['heights'] as $height)
                                            <option value="{{ $height->name }}" {{ isset($preference) && $height->name == $preference->height_from ? 'selected' : '' }}>
                                                {{ $height->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="height_to">Height To</label>
                                    <select id="height_to" class="form-select select2" name="height_to">
                                        <option value="">Select Height To</option>
                                        @foreach($db['heights'] as $height)
                                            <option value="{{ $height->name }}" {{ isset($preference) && $height->name == $preference->height_to ? 'selected' : '' }}>
                                                {{ $height->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Marital Status --}}
                                <div class="col-lg-4 mb-3">
                                    <label for="marital_status">Marital Status</label>
                                    <select name="marital_status[]" id="marital_status" class="form-control select2" multiple>
                                        @foreach($db['maritalStatuses'] as $maritalStatus)
                                            <option value="{{ $maritalStatus->name }}" {{ in_array($maritalStatus->name, $selectedMarital) ? 'selected' : '' }}>
                                                {{ $maritalStatus->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Physical Status --}}
                                <div class="col-lg-4 mb-3">
                                    <label for="physical_status">Physical Status</label>
                                    <select id="physical_status" class="form-select select2" name="physical_status[]" multiple>
                                        <option value="Normal" {{ in_array('Normal', $selectedPhysicalStatus) ? 'selected' : '' }}>Normal</option>
                                        <option value="Physically Challenged" {{ in_array('Physically Challenged', $selectedPhysicalStatus) ? 'selected' : '' }}>Physically Challenged</option>
                                    </select>
                                </div>

                                {{-- Religion --}}
                                <div class="col-lg-4 mb-3">
                                    <label for="religion">Religion</label>
                                    <select name="religion[]" id="religion" class="form-control select2" multiple>
                                        @foreach($db['religions'] as $religion)
                                            <option value="{{ $religion->name }}" {{ in_array($religion->name, $selectedReligion) ? 'selected' : '' }}>
                                                {{ $religion->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Caste --}}
                                <div class="col-lg-4 mb-3">
                                    <label for="caste">Caste</label>
                                    <select name="caste[]" id="caste" class="form-control select2" multiple>
                                        @foreach($dropdownData['castes'] as $caste)
                                            <option value="{{ $caste->name }}" {{ in_array($caste->name, $selectedCaste) ? 'selected' : '' }}>
                                                {{ $caste->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="ethnicity">Ethnicity</label>
                                    <select class="form-select select2" name="ethnicity[]" id="ethnicity" multiple>
                                        @foreach($db['ethnicity'] as $ethnicity)
                                            <option value="{{ $ethnicity->name }}" {{ in_array($ethnicity->name, $selectedEthnicities) ? 'selected' : '' }}>
                                                {{ $ethnicity->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="nationality">Nationality</label>
                                    <select class="form-select select2" name="nationality[]" id="nationality" multiple>
                                        @foreach($db['nationality'] as $nationality)
                                            <option value="{{ $nationality->name }}" {{ in_array($nationality->name, $selectedNationalities) ? 'selected' : '' }}>
                                                {{ $nationality->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Drinking Habit --}}
                                <div class="col-lg-4 mb-3">
                                    <label for="drinking_habit">Drinking Habit</label>
                                    <select name="drinking_habit[]" id="drinking_habit" class="form-control select2" multiple>
                                        @foreach($db['drinkingHabits'] as $drinkingHabit)
                                            <option value="{{ $drinkingHabit->name }}" {{ in_array($drinkingHabit->name, $selectedDrinkingHabits) ? 'selected' : '' }}>
                                                {{ $drinkingHabit->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Smoking Habit --}}
                                <div class="col-lg-4 mb-3">
                                    <label for="smoking_habit">Smoking Habit</label>
                                    <select name="smoking_habit[]" id="smoking_habit" class="form-control select2" multiple>
                                        @foreach($db['smokingHabits'] as $smokingHabit)
                                            <option value="{{ $smokingHabit->name }}" {{ in_array($smokingHabit->name, $selectedSmokingHabits) ? 'selected' : '' }}>
                                                {{ $smokingHabit->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Eating Habit --}}
                                <div class="col-lg-4 mb-3">
                                    <label for="eating_habit">Eating Habit</label>
                                    <select name="eating_habit[]" id="eating_habit" class="form-control select2" multiple>
                                        @foreach($db['eatingHabits'] as $eatingHabit)
                                            <option value="{{ $eatingHabit->name }}" {{ in_array($eatingHabit->name, $selectedEatingHabits) ? 'selected' : '' }}>
                                                {{ $eatingHabit->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>


                            <h4 class="card-title mb-4">Education & Jobs Details</h4>
                            <div class="row">

                                <div class="col-lg-4 mb-3">
                                    <label for="education">Education</label>
                                    <select class="form-select select2" name="education[]" id="education" multiple>
                                        @foreach($db['combinedEducations'] as $level)
                                            <optgroup label="{{ $level->level_name }}">
                                                @foreach($level->educations as $education)
                                                    <option value="{{ $education->name }}" {{ in_array($education->name, $selectedEducations) ? 'selected' : '' }}>
                                                        {{ $education->name }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="occupation">Occupation</label>
                                    <select class="form-select select2" name="occupation[]" id="occupation" multiple>
                                        @foreach($db['combinedOccupations'] as $combinedOccupation)
                                            <optgroup label="{{ $combinedOccupation->type_name }}">
                                                @foreach($combinedOccupation->occupations as $occupation)
                                                    <option value="{{ $occupation->name }}" {{ in_array($occupation->name, $selectedOccupations) ? 'selected' : '' }}>
                                                        {{ $occupation->name }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- EmployedIn --}}
                                <div class="col-lg-4 mb-3">
                                    <label for="employed_in">Employed In</label>
                                    <select class="form-select select2" name="employed_in[]" id="employed_in" multiple>
                                        @foreach($db['employedIns'] as $employedIn)
                                            <option value="{{ $employedIn->name }}" {{ in_array($employedIn->name, $selectedEmployedIn) ? 'selected' : '' }}>
                                                {{ $employedIn->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-lg-4 mb-3">
                                    <label for="monthly_income_from">Monthly Income From</label>
                                    <select id="monthly_income_from" class="form-select select2" name="monthly_income_from">
                                        <option value="">Select Monthly Income From</option>
                                        @foreach($db['salaries'] as $salary)
                                            <option value="{{ $salary->name }}" {{ isset($preference) && $salary->name == $preference->monthly_income_from ? 'selected' : '' }}>
                                                {{ $salary->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="monthly_income_to">Monthly Income To</label>
                                    <select id="monthly_income_to" class="form-select select2" name="monthly_income_to">
                                        <option value="">Select Monthly Income To</option>
                                        @foreach($db['salaries'] as $salary)
                                            <option value="{{ $salary->name }}" {{ isset($preference) && $salary->name == $preference->monthly_income_to ? 'selected' : '' }}>
                                                {{ $salary->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="work_country">Work Country</label>
                                    <select class="form-select select2" name="work_country[]" id="work_country" multiple>
                                        @foreach($locationData['work_countries'] as $country)
                                            <option value="{{ $country->name }}" {{ in_array($country->name, $selectedWorkCountry) ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="visa_status">Visa Status</label>
                                    <select class="form-select select2" name="visa_status[]" id="visa_status" multiple>
                                        @foreach($db['visaStatus'] as $visaStatus)
                                            <option value="{{ $visaStatus->name }}" {{ in_array($visaStatus->name, $selectedVisaStatus) ? 'selected' : '' }}>
                                                {{ $visaStatus->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <h4 class="card-title mb-4">Horoscope Details</h4>
                            <div class="row">
                                {{-- Rashi --}}
                                <div class="col-lg-4 mb-3">
                                    <label for="rashi">Rashi</label>
                                    <select class="form-select select2" name="rashi[]" id="rashi" multiple>
                                        @foreach($db['rashies'] as $rashi)
                                            <option value="{{ $rashi->name }}" {{ in_array($rashi->name, $selectedRashi) ? 'selected' : '' }}>
                                                {{ $rashi->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                {{-- Dosham --}}
                                <div class="col-lg-4 mb-3">
                                    <label for="dosham">Dosham</label>
                                    <select class="form-select select2" name="dosham[]" id="dosham" multiple>
                                        @foreach($db['dosham'] as $dosham)
                                            <option value="{{ $dosham->name }}" {{ in_array($dosham->name, $selectedDoshams) ? 'selected' : '' }}>
                                                {{ $dosham->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>


                            <h4 class="card-title mb-4">Address Details</h4>
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="country">Country</label>
                                    <select class="form-select select2" name="country[]" id="country" multiple>
                                        @foreach($locationData['countries'] as $country)
                                            <option value="{{ $country->name }}" {{ in_array($country->name, $selectedCountries) ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="state">State</label>
                                    <select class="form-select select2" name="state[]" id="state" multiple>
                                        @foreach($locationData['states'] as $state)
                                            <option value="{{ $state->name }}" {{ in_array($state->name, $selectedStates) ? 'selected' : '' }}>
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label for="city">City</label>
                                    <select class="form-select select2" name="city[]" id="city" multiple>
                                        @foreach($locationData['cities'] as $city)
                                            <option value="{{ $city->name }}" {{ in_array($city->name, $selectedCities) ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn m-t-30 mt-3 btn-primary">Submit</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <link href="{{ asset('asset/css/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('asset/js/select2.min.js') }}"></script>
    <script src="{{ asset('asset/js/script.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: 'Any',
                allowClear: true
            });
        });

        const authUserGender = "{{ strtolower($userAndUserDetails->gender) }}";
        const savedMinAge = @json($preference->min_age ?? null);
        const savedMaxAge = @json($preference->max_age ?? null);
    </script>
    @include('admin.includes.footer')
@endsection
