@extends('admin.layouts.layout')
@section('title', 'Matching Profile Analysis')

<style>
    .search-form .card {
        border-radius: 8px;
        margin-bottom: 10px !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border: none;
    }
    .search-form .card-header {
        border-radius: 8px 8px 0 0;
        padding: 15px;
    }
    .search-form .form-control {
        border-radius: 6px;
        border: 1px solid #e2e5e8;
        padding: 8px 12px;
        height: auto;
        transition: all 0.3s ease;
    }
    .search-form .form-control:focus {
        border-color: #6c757d;
        box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.25);
    }
    .search-form .btn {
        display: flex;
        border-radius: 6px;
        padding: 8px 16px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .search-form .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    .search-form .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #5a6268;
    }
    .search-form .card-body {
        padding: 15px !important;
    }
    .search-form-title {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
    }
    .search-form .select-icon {
        margin-right: 8px;
    }
</style>

@section('content')
    @include('admin.includes.header')
    @include('admin.includes.sidebar')

    <div id="layout-wrapper">

        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">
                            <form method="GET" action="{{ route('profileAnalysis') }}" class="search-form">

                                <div class="card">
                                    <div class="card-header bg-primary d-flex justify-content-between align-items-center text-white">
                                        <h5 class="search-form-title"><i class="mdi mdi-account-search select-icon"></i> Search Matches</h5>
                                        <button class="btn btn-light btn-sm" type="submit"><i class="mdi mdi-filter select-icon"></i>Filter</button>
                                    </div>

                                    <div class="p-2">
                                        <select id="gender" class="form-control select2" name="gender">
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>

                                    <div class="p-2">
                                        <select id="language" class="form-control select2" name="mother_tongue">
                                            <option value="">Select Mother Tongue</option>
                                            @foreach($db['languages'] as $language)
                                                <option value="{{ $language->language }}">{{ $language->language }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="p-2">
                                        <select id="age" class="form-control select2" name="age">
                                            <option value="">Select Age</option>
                                            <option value="18 to 20">18 to 20</option>
                                            <option value="21 to 25">21 to 25</option>
                                            <option value="26 to 30">26 to 30</option>
                                            <option value="31 to 35">31 to 35</option>
                                            <option value="36 to 40">36 to 40</option>
                                            <option value="41 to 45">41 to 45</option>
                                            <option value="46 to 50">46 to 50</option>
                                            <option value="51 to 55">51 to 55</option>
                                            <option value="56 to 60">56 to 60</option>
                                            <option value="61 to 65">61 to 65</option>
                                            <option value="66 to 70">66 to 70</option>
                                        </select>
                                    </div>

                                    <div class="p-2">
                                        <select id="marital_status" class="form-control select2" name="marital_status">
                                            <option value="">Select Marital Status</option>
                                            @foreach($db['maritalStatuses'] as $maritalStatus)
                                                <option value="{{ $maritalStatus->name }}">{{ $maritalStatus->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="p-2">
                                        <select id="religion" class="form-control select2" name="religion">
                                            <option value="">Select Religion</option>
                                            @foreach($db['religions'] as $religion)
                                                <option value="{{ $religion->name }}">{{ $religion->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="p-2">
                                        <select id="caste" class="form-control select2" name="caste">
                                            <option value="">Select Caste</option>
                                            @foreach($db['castes'] as $caste)
                                                <option value="{{ $caste }}">{{ $caste }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="p-2">
                                        <select id="ethnicity" class="form-control select2" name="ethnicity">
                                            <option value="">Select Ethnicity</option>
                                            @foreach($db['ethnicity'] as $ethnicity)
                                                <option value="{{ $ethnicity->name }}">{{ $ethnicity->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="p-2">
                                        <select id="nationality" class="form-control select2" name="nationality">
                                            <option value="">Select Nationality</option>
                                            @foreach($db['nationality'] as $nationality)
                                                <option value="{{ $nationality->name }}">{{ $nationality->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="p-2">
                                        <select id="body_type" class="form-control select2" name="body_type">
                                            <option value="">Select Body Type</option>
                                            @foreach($db['bodyTypes'] as $bodyType)
                                                <option value="{{ $bodyType->name }}">{{ $bodyType->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="p-2">
                                        <select id="eating_habit" class="form-control select2" name="eating_habit">
                                            <option value="">Select Eating Habit</option>
                                            @foreach($db['eatingHabits'] as $eatingHabit)
                                                <option value="{{ $eatingHabit->name }}">{{ $eatingHabit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="p-2">
                                        <select id="drinking_habit" class="form-control select2" name="drinking_habit">
                                            <option value="">Select Drinking Habit</option>
                                            @foreach($db['drinkingHabits'] as $drinkingHabit)
                                                <option value="{{ $drinkingHabit->name }}">{{ $drinkingHabit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="p-2">
                                        <select id="smoking_habit" class="form-control select2" name="smoking_habit">
                                            <option value="">Select Smoking Habit</option>
                                            @foreach($db['smokingHabits'] as $smokingHabit)
                                                <option value="{{ $smokingHabit->name }}">{{ $smokingHabit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="p-2">
                                        <select id="educations" name="education" class="form-select select2">
                                            <option value="">Select Education</option>
                                            @foreach($db['combinedEducations'] as $level)
                                                <optgroup label="{{ $level->level_name }}">
                                                    @foreach($level->educations as $education)
                                                        <option value="{{ $education->name }}">{{ $education->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="p-2">
                                        <select id="occupations" name="occupation" class="form-select select2">
                                            <option value="">Select Occupation</option>
                                            @foreach($db['combinedOccupations'] as $combinedOccupation)
                                                <optgroup label="{{ $combinedOccupation->type_name }}">
                                                    @foreach($combinedOccupation->occupations as $occupation)
                                                        <option value="{{ $occupation->name }}">{{ $occupation->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="p-2">
                                        <select id="work_country" class="form-control select2" name="work_country">
                                            <option value="">Select Work Country</option>
                                            @foreach($db['countries'] as $country)
                                                <option value="{{ $country }}">{{ $country }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="p-2">
                                        <select id="visa_status" class="form-control select2" name="visa_status">
                                            <option value="">Select Visa Status</option>
                                            @foreach($db['visaStatus'] as $visaStatus)
                                                <option value="{{ $visaStatus->name }}">{{ $visaStatus->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="p-2">
                                        <select id="rashi" class="form-control select2" name="rashi">
                                            <option value="">Select Rashi</option>
                                            @foreach($db['rashies'] as $rashi)
                                                <option value="{{ $rashi->name }}">{{ $rashi->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="p-2">
                                        <select id="gothram" class="form-control select2" name="gothram">
                                            <option value="">Select Gothram</option>
                                            @foreach($db['gothrams'] as $gothram)
                                                <option value="{{ $gothram->name }}">{{ $gothram->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="p-2">
                                        <select id="dosham" class="form-control select2" name="dosham">
                                            <option value="">Select Dosha</option>
                                            @foreach($db['dosham'] as $dosham)
                                                <option value="{{ $dosham->name }}">{{ $dosham->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="p-2">
                                        <select id="country" class="form-control select2" name="country">
                                            <option value="">Select Country</option>
                                            @foreach($db['countries'] as $country)
                                                <option value="{{ $country }}">{{ $country }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="p-2">
                                        <select id="state" class="form-control select2" name="state">
                                            <option value="">Select State</option>
                                        </select>
                                    </div>

                                    <div class="p-2">
                                        <select id="city" class="form-control select2" name="city">
                                            <option value="">Select City</option>
                                        </select>
                                    </div>

                                    <div class="card-header bg-primary d-flex justify-content-center align-items-center text-white p-15">
{{--                                        <button class="btn btn-secondary btn-md" type="reset"><i class="mdi mdi-refresh mr-1"></i> Reset</button>--}}
                                        <button class="btn btn-light btn-md" type="submit"><i class="mdi mdi-filter mr-1"></i> Apply Filters</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="col-md-9">

                            @if($profiles->isEmpty())
                                <p>No Search Profiles Found</p>
                            @else

                                <div class="row">

                                    {{--                                    <h4 class="">Matching Profiles Analysis ({{ $profilesCount }})</h4>--}}

                                    @if(!empty($appliedFilters))
                                        <h4>Search Results for:</h4>
                                        <div>
                                            @php
                                                $filterLabels = [
                                                    'mother_tongue' => 'Mother Tongue',
                                                    'gender' => 'Gender',
                                                    'age' => 'Age',
                                                    'marital_status' => 'Marital Status',
                                                    'skin_tone' => 'Skin Tone',
                                                    'height' => 'Height',
                                                    'body_type' => 'Body Type',
                                                    'ethnicity' => 'Ethnicity',
                                                    'nationality' => 'Nationality',
                                                    'drinking_habit' => 'Drinking Habit',
                                                    'smoking_habit' => 'Smoking Habit',
                                                    'religion' => 'Religion',
                                                    'caste' => 'Caste',
                                                    'education' => 'Education',
                                                    'occupation' => 'Occupation',
                                                    'work_country' => 'Work Country',
                                                    'visa_status' => 'Visa Status',
                                                    'country' => 'Country',
                                                    'state' => 'State',
                                                    'city' => 'City',
                                                    'rashi' => 'Rashi',
                                                    'gothram' => 'Gothram',
                                                    'dosham' => 'Dosha',
                                                ];
                                            @endphp

                                            @foreach($appliedFilters as $filterKey => $filterValue)
                                                @if(array_key_exists($filterKey, $filterLabels))
                                                    <p>{{ $filterLabels[$filterKey] }}: {{ $filterValue }} [{{ $profilesCount }}]</p>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <div class="row g-4">
                                    @foreach ($profiles as $profile)
                                        @php
                                            $defaultImage = asset('asset/img/default/default.png');
                                            if (isset($profile->gender)) {
                                                $defaultImage = $profile->gender === 'Male'
                                                    ? asset('asset/img/default/male.webp')
                                                    : ($profile->gender === 'Female'
                                                        ? asset('asset/img/default/female.webp')
                                                        : $defaultImage);
                                            }

                                            $imageSrc = $defaultImage;
                                            if (!empty($profile->profile_image) && file_exists(public_path('Profile Image/' . $profile->profile_image))) {
                                                $imageSrc = asset('Profile Image/' . $profile->profile_image);
                                            }
                                        @endphp

                                        <div class="col-md-6">
                                            <div class="card profile-card h-100 border-0 shadow-sm">
                                                <div class="row g-0 h-100">
                                                    {{-- Image --}}
                                                    <div class="col-4">
                                                        <img class="img-fluid h-100 rounded-start object-fit-cover"
                                                             src="{{ $imageSrc }}"
                                                             alt="{{ $profile->name ?? '' }}"
                                                        >
                                                    </div>

                                                    {{-- Text --}}
                                                    <div class="col-8 d-flex flex-column">
                                                        <div class="card-body pb-2">
                                                            <h5 class="card-title mb-1 fw-semibold text-truncate">
                                                                {{ $profile->name ?? '-' }}
                                                            </h5>

                                                            <ul class="list-unstyled small mb-0">
                                                                <li class="mb-1">
                                                                    <span class="text-secondary">Age:</span>
                                                                    {{ $profile->age ?? '-' }}
                                                                </li>
                                                                <li class="mb-1">
                                                                    <span class="text-secondary">Occupation:</span>
                                                                    {{ $profile->occupation ?? '-' }}
                                                                </li>
                                                                <li>
                                                                    <span class="text-secondary">City:</span>
                                                                    {{ $profile->city ?? '-' }}
                                                                </li>
                                                            </ul>
                                                        </div>

                                                        {{-- CTA button pinned to bottom right --}}
                                                        <div class="mt-auto px-3 pb-3 text-end">
                                                            <a href="{{ url('admin/user-details/'.$profile->id) }}"
                                                               class="btn btn-primary btn-sm px-3">
                                                                View
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Pagination --}}
                                <div class="d-flex justify-content-end mt-4">
                                    {{ $profiles->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
                                </div>

                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{--Dropdown js--}}
        <script src="{{ asset('asset/js/select2.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    width: '100%'
                });
            });
        </script>
    @include('admin.includes.footer')
@endsection
