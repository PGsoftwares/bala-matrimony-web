@extends('web.layouts.layout')
@section('title', $metaTags->title ?? 'Partner Preference')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <section class="container-fluid">
        <div class="row">

            <div class="col-md-8 mt-4 mb-5">

                <div class="card border-0 shadow-lg p-3 rounded-4">
                    <h5 class="fw-bold">Edit Partner Preferences</h5>
                    <p class="small text-muted mb-3">Define Your Ideal Lifestyle Expectations in a Partner.</p>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @php
                        $selectedMarital = isset($preference->marital_status) ? explode(',', $preference->marital_status) : [];
                        $selectedMotherTongues = isset($preference->mother_tongue) ? explode(',', $preference->mother_tongue) : [];
                        $selectedPhysicalStatus = isset($preference->physical_status) ? explode(',', $preference->physical_status) : [];
                        $selectedReligion = isset($preference->religion) ? explode(',', $preference->religion) : [];
                        $selectedCaste = isset($preference->caste) ? explode(',', $preference->caste) : [];
                        $selectedEatingHabits = isset($preference->eating_habit) ? explode(',', $preference->eating_habit) : [];
                        $selectedDrinkingHabits = isset($preference->drinking_habit) ? explode(',', $preference->drinking_habit) : [];
                        $selectedSmokingHabits = isset($preference->smoking_habit) ? explode(',', $preference->smoking_habit) : [];
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

                    <form method="POST" action="{{ route('set-preference.store') }}">
                        @csrf
                        <div class="row">

                            {{--Basic details--}}
                            <h5 class="fw-medium">Basic Details</h5>
                            <div class="col-md-6">
                                <label class="form-label fw-medium small">Age</label>
                                <div class="mb-3">
                                    <select id="min_age" name="min_age" class="form-select rounded-pill mb-2">
                                        <option value="">Min Age</option>
                                    </select>
                                    <select id="max_age" name="max_age" class="form-select rounded-pill">
                                        <option value="">Max Age</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium small">Height</label>
                                <div class="mb-3">
                                    <select id="min_height" name="height_from" class="form-select rounded-pill mb-2">
                                        <option value="">Minimum Height</option>
                                        @foreach($db['heights'] as $height)
                                            <option value="{{ $height->name }}" {{ isset($preference) && $height->name == $preference->height_from ? 'selected' : '' }}>{{ $height->name }}</option>
                                        @endforeach
                                    </select>
                                    <select id="max_height" name="height_to" class="form-select rounded-pill">
                                        <option value="">Maximum Height</option>
                                        @foreach($db['heights'] as $height)
                                            <option value="{{ $height->name }}" {{ isset($preference) && $height->name == $preference->height_to ? 'selected' : '' }}>{{ $height->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium small">Marital Status</label>
                                    <select name="marital_status[]" class="form-select rounded-pill select2" multiple>
                                        <option value="">Select</option>
                                        @foreach($db['maritalStatuses'] as $maritalStatus)
                                            <option value="{{ $maritalStatus->name }}" {{ in_array($maritalStatus->name, $selectedMarital) ? 'selected' : '' }}>
                                                {{ $maritalStatus->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium small">Mother Tongue</label>
                                    <select name="mother_tongue[]" class="form-select rounded-pill select2" multiple>
                                        <option value="">Select</option>
                                        @foreach($db['languages'] as $language)
                                            <option value="{{ $language->language }}" {{ in_array($language->language, $selectedMotherTongues) ? 'selected' : '' }}>
                                                {{ $language->language }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium small">Physical Status</label>
                                    <select name="physical_status[]" class="form-select rounded-pill select2" multiple>
                                        <option value="">Select</option>
                                        <option value="Normal" {{ in_array('Normal', $selectedPhysicalStatus) ? 'selected' : '' }}>Normal</option>
                                        <option value="Physically Challenged" {{ in_array('Physically Challenged', $selectedPhysicalStatus) ? 'selected' : '' }}>Physically Challenged</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium small">Religion</label>
                                    <select name="religion[]" class="form-select rounded-pill select2" multiple>
                                        <option value="">Select</option>
                                        @foreach($db['religions'] as $religion)
                                            <option value="{{ $religion->name }}" {{ in_array($religion->name, $selectedReligion) ? 'selected' : '' }}>
                                                {{ $religion->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium small">Ethnicity</label>
                                    <select name="ethnicity[]" class="form-select rounded-pill select2" multiple>
                                        <option value="">Select</option>
                                        @foreach($db['ethnicity'] as $ethnicity)
                                            <option value="{{ $ethnicity->name }}" {{ in_array($ethnicity->name, $selectedEthnicities) ? 'selected' : '' }}>
                                                {{ $ethnicity->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium small">Nationality</label>
                                    <select name="nationality[]" class="form-select rounded-pill select2" multiple>
                                        <option value="">Select</option>
                                        @foreach($db['nationality'] as $nationality)
                                            <option value="{{ $nationality->name }}" {{ in_array($nationality->name, $selectedNationalities) ? 'selected' : '' }}>
                                                {{ $nationality->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium small">Caste</label>
                                    <select name="caste[]" class="form-select rounded-pill select2" multiple>
                                        <option value="">Select</option>
                                        @foreach($dropdownData['castes'] as $caste)
                                            <option value="{{ $caste->name }}" {{ in_array($caste->name, $selectedCaste) ? 'selected' : '' }}>
                                                {{ $caste->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium small">Eating Habit</label>
                                    <select name="eating_habit[]" class="form-select rounded-pill select2" multiple>
                                        <option value="">Select</option>
                                        @foreach($db['eatingHabits'] as $eatingHabit)
                                            <option value="{{ $eatingHabit->name }}" {{ in_array($eatingHabit->name, $selectedEatingHabits) ? 'selected' : '' }}>
                                                {{ $eatingHabit->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium small">Drinking Habit</label>
                                    <select name="drinking_habit[]" class="form-select rounded-pill select2" multiple>
                                        <option value="">Select</option>
                                        @foreach($db['drinkingHabits'] as $drinkingHabit)
                                            <option value="{{ $drinkingHabit->name }}" {{ in_array($drinkingHabit->name, $selectedDrinkingHabits) ? 'selected' : '' }}>
                                                {{ $drinkingHabit->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium small">Smoking Habit</label>
                                    <select name="smoking_habit[]" class="form-select rounded-pill select2" multiple>
                                        <option value="">Select</option>
                                        @foreach($db['smokingHabits'] as $smokingHabit)
                                            <option value="{{ $smokingHabit->name }}" {{ in_array($smokingHabit->name, $selectedSmokingHabits) ? 'selected' : '' }}>
                                                {{ $smokingHabit->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{--Education details--}}
                            <h5 class="fw-medium">Education & Career</h5>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium small">Education</label>
                                    <select name="education[]" class="form-select rounded-pill select2" multiple>
                                        <option value="">Select</option>
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
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium small">Occupation</label>
                                    <select name="occupation[]" class="form-select rounded-pill select2" multiple>
                                        <option value="">Select</option>
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
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium small">Employed In</label>
                                    <select name="employed_in[]" class="form-select rounded-pill select2" multiple>
                                        <option value="">Select</option>
                                        @foreach($db['employedIns'] as $employedIn)
                                            <option value="{{ $employedIn->name }}" {{ in_array($employedIn->name, $selectedEmployedIn) ? 'selected' : '' }}>
                                                {{ $employedIn->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="work_country" class="form-label fw-medium small">Work Country</label>
                                    <select id="work_country" name="work_country[]" class="form-select rounded-pill select2" multiple>
                                        <option value="">Select</option>
                                        @foreach($locationData['work_countries'] as $country)
                                            <option value="{{ $country->name }}" {{ in_array($country->name, $selectedWorkCountry) ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium small">Visa Status</label>
                                    <select name="visa_status[]" class="form-select rounded-pill select2" multiple>
                                        <option value="">Select</option>
                                        @foreach($db['visaStatus'] as $visaStatus)
                                            <option value="{{ $visaStatus->name }}" {{ in_array($visaStatus->name, $selectedVisaStatus) ? 'selected' : '' }}>
                                                {{ $visaStatus->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium small">Monthly Income</label>
                                <div class="mb-3">
                                    <select id="monthly_income_from" name="monthly_income_from" class="form-select rounded-pill mb-2">
                                        <option value="">Monthly Income From</option>
                                        @foreach($db['salaries'] as $salary)
                                            <option value="{{ $salary->name }}" {{ isset($preference) && $salary->name == $preference->monthly_income_from ? 'selected' : '' }}>
                                                {{ $salary->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select id="monthly_income_to" name="monthly_income_to" class="form-select rounded-pill">
                                        <option value="">Monthly Income To</option>
                                        @foreach($db['salaries'] as $salary)
                                            <option value="{{ $salary->name }}" {{ isset($preference) && $salary->name == $preference->monthly_income_to ? 'selected' : '' }}>{{ $salary->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{--Horoscope details--}}
                            <h5 class="fw-medium">Horoscope</h5>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium small">Rashi</label>
                                    <select name="rashi[]" class="form-select rounded-pill select2" multiple>
                                        <option value="">Select</option>
                                        @foreach($db['rashies'] as $rashi)
                                            <option value="{{ $rashi->name }}" {{ in_array($rashi->name, $selectedRashi) ? 'selected' : '' }}>
                                                {{ $rashi->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium small">Dosham</label>
                                    <select name="dosham[]" class="form-select rounded-pill select2" multiple>
                                        <option value="">Select</option>
                                        @foreach($db['dosham'] as $dosham)
                                            <option value="{{ $dosham->name }}" {{ in_array($dosham->name, $selectedDoshams) ? 'selected' : '' }}>
                                                {{ $dosham->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            {{--Address details--}}
                            <h5 class="fw-medium">Location</h5>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="country" class="form-label fw-medium small">Country</label>
                                    <select name="country[]" id="country" class="form-select rounded-pill select2" multiple>
                                        <option value="">Select</option>
                                        @foreach($locationData['countries'] as $country)
                                            <option value="{{ $country->name }}" {{ in_array($country->name, $selectedCountries) ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="state" class="form-label fw-medium small">State</label>
                                    <select name="state[]" id="state" class="form-select rounded-pill select2" multiple>
                                        <option value="">Select</option>
                                        @foreach($locationData['states'] as $state)
                                            <option value="{{ $state->name }}" {{ in_array($state->name, $selectedStates) ? 'selected' : '' }}>
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="city" class="form-label fw-medium small">City</label>
                                    <select name="city[]" id="city" class="form-select rounded-pill select2" multiple>
                                        <option value="">Select</option>
                                        @foreach($locationData['cities'] as $city)
                                            <option value="{{ $city->name }}" {{ in_array($city->name, $selectedCities) ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>

                        {{--Actions--}}
                        <div class="row g-2 d-flex justify-content-end">
                            <div class="col-md-4">
                                <button type="submit" class="btn button2 w-100 px-4 py-2">Save Preference</button>
                            </div>
                        </div>

                    </form>
                </div>

            </div>

            @include('web.includes.right-aside')
        </div>
    </section>


    <link href="{{ asset('asset/css/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('asset/js/select2.min.js') }}"></script>
    <script src="{{ asset('asset/js/script.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: 'Any',
            });
        });

        const authUserGender = "{{ strtolower($userAndUserDetails->gender) }}";
        const savedMinAge = @json($preference->min_age ?? null);
        const savedMaxAge = @json($preference->max_age ?? null);
    </script>
    @include('web.includes.footer')
@endsection
