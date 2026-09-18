@extends('web.layouts.layout')
@section('title', $metaTags->title ?? '')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')
    @php
        $userGender = strtolower($userAndUserDetails->gender ?? 'male');
        $minStartAge = $userGender === 'female' ? 21 : 18;
    @endphp

    <section id="page-content py-5">
        <div class="container">
            <div class="row justify-content-center">

                <div class="content col-md-9">
                    <div class="card">
                        <div class="card-header theme-bg">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="h4 text-light">Edit Partner Preferences</span>
                                    <p class="text-muted text-light">Define Your Ideal Lifestyle Expectations in a Partner</p>
                                </div>
                                <div>
                                    <a href="{{ route('tour2') }}" class="btn secondary_button">SKIP</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                                <form id="form1" method="POST" action="{{ route('set-preference.store') }}">
                                    @csrf
                                    <div class="h4 inner_title">Personal Details</div>
                                    <div class="form-row mt-5 ">
                                        <div class="form-group col-md-3">
                                            <label for="min_age">Age From</label>
                                            <select id="min_age" name="min_age" class="form-control">
                                                <option value="">Select Age From</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3">
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
                                        @php
                                            $selectedMotherTongues = isset($preference->mother_tongue) ? explode(',', $preference->mother_tongue) : [];
                                        @endphp

                                        <div class="form-group col-md-6">
                                            <label for="mother_tongue">Mother Tongue</label>
                                            <select name="mother_tongue[]" id="mother_tongue" class="form-control select2" multiple>
                                                @foreach($db['languages'] as $lang)
                                                    <option value="{{ $lang->language }}" {{ in_array($lang->language, $selectedMotherTongues) ? 'selected' : '' }}>
                                                        {{ $lang->language }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Marital Status --}}
                                        @php
                                            $selectedMarital = isset($preference->marital_status) ? explode(',', $preference->marital_status) : [];
                                        @endphp
                                        <div class="form-group col-md-6">
                                            <label for="marital_status">Marital Status</label>
                                            <select name="marital_status[]" id="marital_status" class="form-control select2" multiple>
                                                @foreach($db['maritalStatuses'] as $maritalStatus)
                                                    <option value="{{ $maritalStatus->name }}" {{ in_array($maritalStatus->name, $selectedMarital) ? 'selected' : '' }}>
                                                        {{ $maritalStatus->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Skin Tone --}}
                                        {{--                                    @php--}}
                                        {{--                                        $selectedSkinTones = isset($preference->skin_tone) ? explode(',', $preference->skin_tone) : [];--}}
                                        {{--                                    @endphp--}}
                                        {{--                                    <div class="form-group col-md-6">--}}
                                        {{--                                        <label for="skin_tone">Skin Tone</label>--}}
                                        {{--                                        <select name="skin_tone[]" id="skin_tone" class="form-control select2" multiple>--}}
                                        {{--                                            @foreach($db['skinTones'] as $skinTone)--}}
                                        {{--                                                <option value="{{ $skinTone->name }}" {{ in_array($skinTone->name, $selectedSkinTones) ? 'selected' : '' }}>--}}
                                        {{--                                                    {{ $skinTone->name }}--}}
                                        {{--                                                </option>--}}
                                        {{--                                            @endforeach--}}
                                        {{--                                        </select>--}}
                                        {{--                                    </div>--}}


                                        <div class="form-group col-md-6">
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

                                        <div class="form-group col-md-6">
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


                                        {{-- Body Type --}}
                                        {{--                                    @php--}}
                                        {{--                                        $selectedBodyTypes = isset($preference->body_type) ? explode(',', $preference->body_type) : [];--}}
                                        {{--                                    @endphp--}}
                                        {{--                                    <div class="form-group col-md-6">--}}
                                        {{--                                        <label for="body_type">Body Type</label>--}}
                                        {{--                                        <select name="body_type[]" id="body_type" class="form-control select2" multiple>--}}
                                        {{--                                            @foreach($db['bodyTypes'] as $bodyType)--}}
                                        {{--                                                <option value="{{ $bodyType->name }}" {{ in_array($bodyType->name, $selectedBodyTypes) ? 'selected' : '' }}>--}}
                                        {{--                                                    {{ $bodyType->name }}--}}
                                        {{--                                                </option>--}}
                                        {{--                                            @endforeach--}}
                                        {{--                                        </select>--}}
                                        {{--                                    </div>--}}

                                        {{-- Drinking Habit --}}
                                        @php
                                            $selectedDrinkingHabits = isset($preference->drinking_habit) ? explode(',', $preference->drinking_habit) : [];
                                        @endphp
                                        <div class="form-group col-md-6">
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
                                        @php
                                            $selectedSmokingHabits = isset($preference->smoking_habit) ? explode(',', $preference->smoking_habit) : [];
                                        @endphp
                                        <div class="form-group col-md-6">
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
                                        @php
                                            $selectedEatingHabits = isset($preference->eating_habit) ? explode(',', $preference->eating_habit) : [];
                                        @endphp
                                        <div class="form-group col-md-6">
                                            <label for="eating_habit">Eating Habit</label>
                                            <select name="eating_habit[]" id="eating_habit" class="form-control select2" multiple>
                                                @foreach($db['eatingHabits'] as $eatingHabit)
                                                    <option value="{{ $eatingHabit->name }}" {{ in_array($eatingHabit->name, $selectedEatingHabits) ? 'selected' : '' }}>
                                                        {{ $eatingHabit->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>


                                        {{-- Physical Status --}}
                                        @php
                                            $selectedPhysicalStatus = isset($preference->physical_status) ? explode(',', $preference->physical_status) : [];
                                        @endphp
                                        <div class="form-group col-md-6">
                                            <label for="physical_status">Physical Status</label>
                                            <select id="physical_status" class="form-select select2" name="physical_status[]" multiple>
                                                <option value="Normal" {{ in_array('Normal', $selectedPhysicalStatus) ? 'selected' : '' }}>Normal</option>
                                                <option value="Physically Challenged" {{ in_array('Physically Challenged', $selectedPhysicalStatus) ? 'selected' : '' }}>Physically Challenged</option>
                                            </select>
                                        </div>

                                        {{-- Religion --}}
                                        @php
                                            $selectedReligion = isset($preference->religion) ? explode(',', $preference->religion) : [];
                                        @endphp
                                        <div class="form-group col-md-6">
                                            <label for="religion">Religion</label>
                                            <select name="religion[]" id="religion" class="form-control select2" multiple>
                                                @foreach($db['religions'] as $religion)
                                                    <option value="{{ $religion->name }}" {{ in_array($religion->name, $selectedReligion) ? 'selected' : '' }}>
                                                        {{ $religion->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Community --}}
                                        @php
                                            $selectedCommunity = isset($preference->caste) ? explode(',', $preference->caste) : [];
                                        @endphp
                                        <div class="form-group col-md-6">
                                            <label for="caste">Community</label>
                                            <select name="caste[]" id="caste" class="form-control select2" multiple>
                                                @foreach($dropdownData['castes'] as $caste)
                                                    <option value="{{ $caste->name }}" {{ in_array($caste->name, $selectedCommunity) ? 'selected' : '' }}>
                                                        {{ $caste->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>


                                    <div class="h4  inner_title">Education & Jobs Details</div>
                                    <div class="form-row">

                                        {{--Education--}}
                                        @php
                                            $selectedEducations = isset($preference->education) ? explode(',', $preference->education) : [];
                                        @endphp
                                        <div class="form-group col-md-6">
                                            <label for="educations">Education</label>
                                            <select id="educations" name="education[]" class="form-select select2" multiple>
                                                <option value="">Select Education</option>
                                                @foreach($db['combinedEducations'] as $level)
                                                    <optgroup label="{{ $level->level_name }}">
                                                        @foreach($level->educations as $education)
                                                            <option value="{{ $education->name }}" {{ in_array($education->name, $selectedEducations) ? 'selected' : '' }}>{{ $education->name }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        </div>

{{--                                        <div class="form-group col-md-6">--}}
{{--                                            <label for="education_level">Education type</label>--}}
{{--                                            <select class="form-select select2" name="qualification" id="education_level">--}}
{{--                                                <option value="">Select Education type</option>--}}
{{--                                                @foreach($dropdownData['educationLevels'] as $educationLevel)--}}
{{--                                                    <option value="{{ $educationLevel->name }}" {{ $preference?->qualification == $educationLevel->name ? 'selected' : '' }}>--}}
{{--                                                        {{ $educationLevel->name }}--}}
{{--                                                    </option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}


                                        {{--Education--}}
{{--                                        @php--}}
{{--                                            $selectedEducations = isset($preference->education) ? explode(',', $preference->education) : [];--}}
{{--                                        @endphp--}}
{{--                                        <div class="form-group col-md-6">--}}
{{--                                            <label for="education">Education</label>--}}
{{--                                            <select class="form-select select2" name="education[]" id="education" multiple>--}}
{{--                                                @foreach($dropdownData['educations'] as $education)--}}
{{--                                                    <option value="{{ $education->name }}" {{ in_array($education->name, $selectedEducations) ? 'selected' : '' }}>--}}
{{--                                                        {{ $education->name }}--}}
{{--                                                    </option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}

                                        {{-- Occupation --}}
                                        @php
                                            $selectedOccupations = isset($preference->occupation) ? explode(',', $preference->occupation) : [];
                                        @endphp
                                        <div class="form-group col-md-6">
                                            <label for="occupations">Occupation</label>
                                            <select id="occupations" name="occupation[]" class="form-select select2" multiple>
                                                <option value="">Select Occupation</option>
                                                @foreach($db['combinedOccupations'] as $combinedOccupation)
                                                    <optgroup label="{{ $combinedOccupation->type_name }}">
                                                        @foreach($combinedOccupation->occupations as $occupation)
                                                            <option value="{{ $occupation->name }}" {{ in_array($occupation->name, $selectedOccupations) ? 'selected' : '' }}>{{ $occupation->name }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        </div>

{{--                                        <div class="form-group col-md-6">--}}
{{--                                            <label for="occupation_type">Occupation Type</label>--}}
{{--                                            <select class="form-select select2" name="occupation_type" id="occupation_type">--}}
{{--                                                <option value="">Select Occupation Type</option>--}}
{{--                                                @foreach($dropdownData['occupationTypes'] as $occupationType)--}}
{{--                                                    <option value="{{ $occupationType->name }}" {{ $preference?->occupation_type == $occupationType->name ? 'selected' : '' }}>--}}
{{--                                                        {{ $occupationType->name }}--}}
{{--                                                    </option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}

                                        {{-- Occupation --}}
{{--                                        @php--}}
{{--                                            $selectedOccupations = isset($preference->occupation) ? explode(',', $preference->occupation) : [];--}}
{{--                                        @endphp--}}
{{--                                        <div class="form-group col-md-6">--}}
{{--                                            <label for="occupation">Occupation</label>--}}
{{--                                            <select class="form-select select2" name="occupation[]" id="occupation" multiple>--}}
{{--                                                @foreach($dropdownData['occupations'] as $occupation)--}}
{{--                                                    <option value="{{ $occupation->name }}" {{ in_array($occupation->name, $selectedOccupations) ? 'selected' : '' }}>--}}
{{--                                                        {{ $occupation->name }}--}}
{{--                                                    </option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </div>--}}

                                        {{-- EmployedIn --}}
                                        @php
                                            $selectedEmployedIn = isset($preference->employed_in) ? explode(',', $preference->employed_in) : [];
                                        @endphp
                                        <div class="form-group col-md-6">
                                            <label for="employed_in">Employed In</label>
                                            <select class="form-select select2" name="employed_in[]" id="employed_in" multiple>
                                                @foreach($db['employedIns'] as $employedIn)
                                                    <option value="{{ $employedIn->name }}" {{ in_array($employedIn->name, $selectedEmployedIn) ? 'selected' : '' }}>
                                                        {{ $employedIn->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="form-group col-md-6">
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

                                        <div class="form-group col-md-6">
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
                                    </div>

                                    <div class="h4 inner_title">Horoscope Details</div>
                                    <div class="form-row">

                                        {{-- Rashi --}}
                                        @php
                                            $selectedRashi = isset($preference->rashi) ? explode(',', $preference->rashi) : [];
                                        @endphp
                                        <div class="form-group col-md-6">
                                            <label for="rashi">Rashi</label>
                                            <select class="form-select select2" name="rashi[]" id="rashi" multiple>
                                                @foreach($db['rashies'] as $rashi)
                                                    <option value="{{ $rashi->name }}" {{ in_array($rashi->name, $selectedRashi) ? 'selected' : '' }}>
                                                        {{ $rashi->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Nakshatra --}}
                                        @php
                                            $selectedNakshatra = isset($preference->nakshatra) ? explode(',', $preference->nakshatra) : [];
                                        @endphp
                                        <div class="form-group col-md-6">
                                            <label for="nakshatra">Nakshatra</label>
                                            <select class="form-select select2" name="nakshatra[]" id="nakshatra" multiple>
                                                @foreach($db['nakshatras'] as $star)
                                                    <option value="{{ $star->name }}" {{ in_array($star->name, $selectedNakshatra) ? 'selected' : '' }}>
                                                        {{ $star->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Lagnam --}}
                                        @php
                                            $selectedLagnams = isset($preference->lagnam) ? explode(',', $preference->lagnam) : [];
                                        @endphp
                                        <div class="form-group col-md-6">
                                            <label for="lagnam">Lagnam</label>
                                            <select class="form-select select2" name="lagnam[]" id="lagnam" multiple>
                                                @foreach($db['lagnams'] as $lagnam)
                                                    <option value="{{ $lagnam->name }}" {{ in_array($lagnam->name, $selectedLagnams) ? 'selected' : '' }}>
                                                        {{ $lagnam->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Padam --}}
                                        {{--                                    @php--}}
                                        {{--                                        $selectedPadams = isset($preference->padam) ? explode(',', $preference->padam) : [];--}}
                                        {{--                                    @endphp--}}
                                        {{--                                    <div class="form-group col-md-6">--}}
                                        {{--                                        <label for="padam">Padam</label>--}}
                                        {{--                                        <select class="form-select select2" name="padam[]" id="padam" multiple>--}}
                                        {{--                                            @foreach($db['padams'] as $padam)--}}
                                        {{--                                                <option value="{{ $padam->name }}" {{ in_array($padam->name, $selectedPadams) ? 'selected' : '' }}>--}}
                                        {{--                                                    {{ $padam->name }}--}}
                                        {{--                                                </option>--}}
                                        {{--                                            @endforeach--}}
                                        {{--                                        </select>--}}
                                        {{--                                    </div>--}}

                                        {{-- Kulam --}}
                                        {{--                                    @php--}}
                                        {{--                                        $selectedKulams = isset($preference->kulam) ? explode(',', $preference->kulam) : [];--}}
                                        {{--                                    @endphp--}}
                                        {{--                                    <div class="form-group col-md-6">--}}
                                        {{--                                        <label for="kulam">Kulam</label>--}}
                                        {{--                                        <select class="form-select select2" name="kulam[]" id="kulam" multiple>--}}
                                        {{--                                            @foreach($db['kulams'] as $kulam)--}}
                                        {{--                                                <option value="{{ $kulam->name }}" {{ in_array($kulam->name, $selectedKulams) ? 'selected' : '' }}>--}}
                                        {{--                                                    {{ $kulam->name }}--}}
                                        {{--                                                </option>--}}
                                        {{--                                            @endforeach--}}
                                        {{--                                        </select>--}}
                                        {{--                                    </div>--}}

                                        {{-- Gothram --}}
                                        {{--                                    @php--}}
                                        {{--                                        $selectedGothrams = isset($preference->gothram) ? explode(',', $preference->gothram) : [];--}}
                                        {{--                                    @endphp--}}
                                        {{--                                    <div class="form-group col-md-6">--}}
                                        {{--                                        <label for="gothram">Gothram</label>--}}
                                        {{--                                        <select class="form-select select2" name="gothram[]" id="gothram" multiple>--}}
                                        {{--                                            @foreach($db['gothrams'] as $gothram)--}}
                                        {{--                                                <option value="{{ $gothram->name }}" {{ in_array($gothram->name, $selectedGothrams) ? 'selected' : '' }}>--}}
                                        {{--                                                    {{ $gothram->name }}--}}
                                        {{--                                                </option>--}}
                                        {{--                                            @endforeach--}}
                                        {{--                                        </select>--}}
                                        {{--                                    </div>--}}

                                        {{-- Dosham --}}
                                        @php
                                            $selectedDoshams = isset($preference->dosham) ? explode(',', $preference->dosham) : [];
                                        @endphp
                                        <div class="form-group col-md-6">
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

                                    <div class="h4 inner_title">Address Details</div>
                                    <div class="form-row">
                                        @php
                                            $selectedCountries = isset($preference->country) ? explode(',', $preference->country) : [];
                                            $selectedStates = isset($preference->state) ? explode(',', $preference->state) : [];
                                            $selectedCities = isset($preference->city) ? explode(',', $preference->city) : [];
                                        @endphp

                                        <div class="form-group col-md-6">
                                            <label for="country">Country</label>
                                            <select class="form-select select2" name="country[]" id="country" multiple>
                                                @foreach($locationData['countries'] as $country)
                                                    <option value="{{ $country->name }}" {{ in_array($country->name, $selectedCountries) ? 'selected' : '' }}>
                                                        {{ $country->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="state">State</label>
                                            <select class="form-select select2" name="state[]" id="state" multiple>
                                                @foreach($locationData['states'] as $state)
                                                    <option value="{{ $state->name }}" {{ in_array($state->name, $selectedStates) ? 'selected' : '' }}>
                                                        {{ $state->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
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
                                        <button type="submit" class="btn m-t-30 mt-3 theme-btn">Submit</button>
                                    </div>
                                </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const minAgeSelect = document.getElementById("min_age");
            const maxAgeSelect = document.getElementById("max_age");
            const minAgeValue = "{{ $preference->min_age ?? '' }}";
            const maxAgeValue = "{{ $preference->max_age ?? '' }}";
            const startAge = {{ $minStartAge }};
            const endAge = 70;

            // Populate min_age select
            for (let age = startAge; age <= endAge; age++) {
                const option = new Option(age, age, false, age == minAgeValue);
                minAgeSelect.appendChild(option);
            }
            // Function to update max_age based on selected min_age
            function updateMaxAgeOptions(selectedMinAge) {
                // Clear existing options
                maxAgeSelect.innerHTML = '<option value="">Select Age To</option>';
                for (let age = selectedMinAge; age <= endAge; age++) {
                    const isSelected = age == maxAgeValue;
                    const option = new Option(age, age, false, isSelected);
                    maxAgeSelect.appendChild(option);
                }
            }
            // Initialize max_age if min_age is already selected
            if (minAgeValue) {
                updateMaxAgeOptions(parseInt(minAgeValue));
            }
            // Listen for changes on min_age
            minAgeSelect.addEventListener('change', function () {
                const selectedMinAge = parseInt(this.value) || startAge;
                updateMaxAgeOptions(selectedMinAge);
            });
        });
    </script>

    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: 'Any',
            });
        });
    </script>
    @include('web.includes.footer')
@endsection
