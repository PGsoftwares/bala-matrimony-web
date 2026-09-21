@extends('admin.layouts.layout')
@section('title', 'Admin - Registration Details')

@section('content')
    @include('admin.includes.header')
    @include('admin.includes.sidebar')

    <div id="layout-wrapper">
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Please fill our details</h4>
                                    <p class="card-title-desc">Complete your details to enjoy!</p>

                                    {{-- Nav Tabs --}}
                                    <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" href="#personal_details" role="tab">
                                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                                <span class="d-none d-sm-block">Personal</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#education_job" role="tab">
                                                <span class="d-block d-sm-none"><i class="fas fa-user-tie"></i></span>
                                                <span class="d-none d-sm-block">Professional</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#family_details" role="tab">
                                                <span class="d-block d-sm-none"><i class="fas fa-users"></i></span>
                                                <span class="d-none d-sm-block">Family</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#horoscope_details" role="tab">
                                                <span class="d-block d-sm-none"><i class="fas fa-star"></i></span>
                                                <span class="d-none d-sm-block">Horoscope</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#address_details" role="tab">
                                                <span class="d-block d-sm-none"><i class="fas fa-map-marker-alt"></i></span>
                                                <span class="d-none d-sm-block">Address</span>
                                            </a>
                                        </li>
                                    </ul>


                                        <form method="POST" action="{{ route('register-details.store') }}"  enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $user_id }}">
                                        {{-- Tab Pane start --}}
                                        <div class="tab-content p-3 text-muted">

                                                {{-- Personal Details  start --}}
                                                <div class="tab-pane active" id="personal_details" role="tabpanel">
                                                    <div class="row">

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                                            <input type="date" class="form-control @error('dob') is-invalid @enderror" name="dob" value="{{ old('dob') }}" required>
                                                            @error('dob')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>


                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Birth Time <span class="text-danger">*</span></label>
                                                            <div class="input-group">
                                                                <!-- Hours -->
                                                                <select class="form-control @error('birth_time') is-invalid @enderror" name="birth_hour" required>
                                                                    <option value="" disabled selected>Hour</option>
                                                                    @for ($i = 1; $i <= 12; $i++)
                                                                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                                                            {{ old('birth_hour') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                                                            {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                                                        </option>
                                                                    @endfor
                                                                </select>

                                                                <!-- Minutes -->
                                                                <select class="form-control @error('birth_time') is-invalid @enderror" name="birth_minute" required>
                                                                    <option value="" disabled selected>Minute</option>
                                                                    @for ($i = 0; $i < 60; $i++)
                                                                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                                                            {{ old('birth_minute') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                                                            {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                                                        </option>
                                                                    @endfor
                                                                </select>

                                                                <!-- AM/PM -->
                                                                <select class="form-control @error('birth_time') is-invalid @enderror" name="birth_ampm" required>
                                                                    <option value="" disabled selected>AM/PM</option>
                                                                    <option value="AM" {{ old('birth_ampm') == 'AM' ? 'selected' : '' }}>AM</option>
                                                                    <option value="PM" {{ old('birth_ampm') == 'PM' ? 'selected' : '' }}>PM</option>
                                                                </select>
                                                            </div>

                                                            <!-- Validation Error Message -->
                                                            @error('birth_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>


                                                        <div class="col-lg-4 mb-3">
                                                            <label for="birth_country" class="form-label">Birth Country <span class="text-danger">*</span></label>
                                                            <select id="birth_country" class="form-select @error('birth_country') is-invalid @enderror"  name="birth_country" required>
                                                                <option value="">Select Country</option>
                                                                @foreach($db['countries'] as $country)
                                                                    <option value="{{ $country }}">{{ $country }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('birth_country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label for="birth_state" class="form-label">Birth State <span class="text-danger">*</span></label>
                                                            <select id="birth_state" class="form-select @error('birth_state') is-invalid @enderror" name="birth_state" required>
                                                                <option value="">Select State</option>
                                                            </select>
                                                            @error('birth_state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label for="birth_city" class="form-label">Birth City <span class="text-danger">*</span></label>
                                                            <select id="birth_city" class="form-select @error('birth_city') is-invalid @enderror" name="birth_city" required>
                                                                <option value="">Select City</option>
                                                            </select>
                                                            @error('birth_city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Mother Tongue <span class="text-danger">*</span></label>
                                                            <select class="form-select @error('mother_tongue') is-invalid @enderror" name="mother_tongue" required>
                                                                <option value="">Select Mother Tongue</option>
                                                                @foreach($db['languages'] as $language)
                                                                    <option value="{{ $language->language }}" {{ old('mother_tongue') == $language->language ? 'selected' : '' }}>{{ $language->language }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('mother_tongue')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Marital Status <span class="text-danger">*</span></label>
                                                            <select class="form-select @error('marital_status') is-invalid @enderror" name="marital_status" required>
                                                                <option value="">Select Marital Status</option>
                                                                @foreach($db['maritalStatuses'] as $maritalStatus)
                                                                    <option value="{{ $maritalStatus->name }}" {{ old('marital_status') == $maritalStatus->name ? 'selected' : '' }}>{{ $maritalStatus->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('marital_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Ethnicity</label>
                                                            <select class="form-select" name="ethnicity">
                                                                <option value="">Select Ethnicity</option>
                                                                @foreach($db['ethnicity'] as $ethnicity)
                                                                    <option value="{{ $ethnicity->name }}" {{ old('ethnicity') == $ethnicity->name ? 'selected' : '' }}>{{ $ethnicity->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Nationality</label>
                                                            <select class="form-select" name="nationality">
                                                                <option value="">Select Nationality</option>
                                                                @foreach($db['nationality'] as $nationality)
                                                                    <option value="{{ $nationality->name }}" {{ old('nationality') == $nationality->name ? 'selected' : '' }}>{{ $nationality->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Religion <span class="text-danger">*</span></label>
                                                            <select class="form-select @error('religion') is-invalid @enderror" name="religion" required>
                                                                <option value="">Select Religion</option>
                                                                @foreach($db['religions'] as $religion)
                                                                    <option value="{{ $religion->name }}" {{ old('religion') == $religion->name ? 'selected' : '' }}>{{ $religion->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('religion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Caste <span class="text-danger">*</span></label>
                                                            <select class="form-select @error('caste') is-invalid @enderror" id="caste" name="caste" required>
                                                                <option value="">Select Caste</option>
                                                                @foreach($db['castes'] as $caste)
                                                                    <option value="{{ $caste }}" {{ old('caste') == $caste ? 'selected' : '' }}>{{ $caste }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('caste')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Sub Caste <span class="text-danger">*</span></label>
                                                            <select class="form-select @error('sub_caste') is-invalid @enderror" id="sub_caste" name="sub_caste" required>
                                                                <option value="">Select Sub Caste</option>
                                                            </select>
                                                            @error('sub_caste')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Physical Status <span class="text-danger">*</span></label>
                                                            <select class="form-select @error('physical_status') is-invalid @enderror" name="physical_status" required>
                                                                <option value="">Select Physical Status</option>
                                                                <option value="Normal" {{ old('physical_status') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                                                <option value="Physically Challenged" {{ old('physical_status') == 'Physically Challenged' ? 'selected' : '' }}>Physically Challenged</option>
                                                            </select>
                                                            @error('physical_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Skin Tone <span class="text-danger">*</span></label>
                                                            <select class="form-select @error('skin_tone') is-invalid @enderror" name="skin_tone" required>
                                                                <option value="">Select Skin Tone</option>
                                                                @foreach($db['skinTones'] as $skinTone)
                                                                    <option value="{{ $skinTone->name }}" {{ old('skin_tone') == $skinTone->name ? 'selected' : '' }}>{{ $skinTone->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('skin_tone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Height <span class="text-danger">*</span></label>
                                                            <select class="form-select @error('height') is-invalid @enderror" name="height" required>
                                                                <option value="">Select Height</option>
                                                                @foreach($db['heights'] as $height)
                                                                    <option value="{{ $height->name }}" {{ old('height') == $height->name ? 'selected' : '' }}>{{ $height->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('height')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Weight (in Kg) <span class="text-danger">*</span></label>
                                                            <input class="form-control @error('weight') is-invalid @enderror" type="number" name="weight" id="weight" value="{{ old('weight') }}" placeholder="Enter weight in Kg" required>
                                                            @error('weight')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Body Type <span class="text-danger">*</span></label>
                                                            <select class="form-select @error('body_type') is-invalid @enderror" name="body_type" required>
                                                                <option value="">Select Body Type</option>
                                                                @foreach($db['bodyTypes'] as $bodyType)
                                                                    <option value="{{ $bodyType->name }}" {{ old('body_type') == $bodyType->name ? 'selected' : '' }}>{{ $bodyType->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('body_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Eating Habit <span class="text-danger">*</span></label>
                                                            <select class="form-select @error('eating_habit') is-invalid @enderror" name="eating_habit" required>
                                                                <option value="">Select Eating Habit</option>
                                                                @foreach($db['eatingHabits'] as $eatingHabit)
                                                                    <option value="{{ $eatingHabit->name }}" {{ old('eating_habit') == $eatingHabit->name ? 'selected' : '' }}>{{ $eatingHabit->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('eating_habit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Drinking Habit <span class="text-danger">*</span></label>
                                                            <select class="form-select @error('drinking_habit') is-invalid @enderror" name="drinking_habit" required>
                                                                <option value="">Select Drinking Habit</option>
                                                                @foreach($db['drinkingHabits'] as $drinkingHabit)
                                                                    <option value="{{ $drinkingHabit->name }}" {{ old('drinking_habit') == $drinkingHabit->name ? 'selected' : '' }}>{{ $drinkingHabit->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('drinking_habit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Smoking Habit <span class="text-danger">*</span></label>
                                                            <select class="form-select @error('smoking_habit') is-invalid @enderror" name="smoking_habit" required>
                                                                <option value="">Select Smoking Habit</option>
                                                                @foreach($db['smokingHabits'] as $smokingHabit)
                                                                    <option value="{{ $smokingHabit->name }}" {{ old('smoking_habit') == $smokingHabit->name ? 'selected' : '' }}>{{ $smokingHabit->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('smoking_habit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>


                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Profile Image</label>
                                                            <input type="file" class="form-control @error('profile_image') is-invalid @enderror" name="profile_image">
                                                            @error('profile_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="d-flex justify-content-end gap-2 mt-3">
                                                            <button type="button" class="btn btn-primary next-tab" data-bs-target="#education_job">Next <i class="fas fa-arrow-right ms-1"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Education & Job Details  start --}}
                                                <div class="tab-pane" id="education_job" role="tabpanel">
                                                    <div class="row">

                                                         {{--Education--}}
                                                        <div class="col-lg-4 mb-3">
                                                            <label for="educations" class="form-label">Education <span class="text-danger">*</span></label>
                                                            @php
                                                                $selectedEducations = !empty(old('education'))
                                                                    ? (is_array(old('education')) ? old('education') : array_map('trim', explode(',', old('education'))))
                                                                    : (!empty($userDetails->education) ? array_map('trim', explode(',', $userDetails->education)) : []);
                                                            @endphp
                                                            <select id="educations" name="education[]" class="form-select select2 @error('education') is-invalid @enderror" multiple="multiple" data-placeholder="Select Education" required>
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

                                                        {{--Occupation--}}
                                                        <div class="col-lg-4 mb-3">
                                                            <label for="occupations" class="form-label">Occupation <span class="text-danger">*</span></label>
                                                            <select id="occupations" name="occupation" class="form-select @error('occupation') is-invalid @enderror" required>
                                                                <option value="">Select Occupation</option>
                                                                @foreach($db['combinedOccupations'] as $combinedOccupation)
                                                                    <optgroup label="{{ $combinedOccupation->type_name }}">
                                                                        @foreach($combinedOccupation->occupations as $occupation)
                                                                            <option value="{{ $occupation->name }}">{{ $occupation->name }}</option>
                                                                        @endforeach
                                                                    </optgroup>
                                                                @endforeach
                                                            </select>
                                                            @error('occupation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Employed In <span class="text-danger">*</span></label>
                                                            <select class="form-select @error('employed_in') is-invalid @enderror" name="employed_in" required>
                                                                <option value="">Select Employed In</option>
                                                                @foreach($db['employedIns'] as $employedIn)
                                                                    <option value="{{ $employedIn->name }}" {{ old('employed_in') == $employedIn->name ? 'selected' : '' }}>{{ $employedIn->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('employed_in')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Monthly Income <span class="text-danger">*</span></label>
                                                            <select class="form-select @error('monthly_income') is-invalid @enderror" name="monthly_income" required>
                                                                <option value="">Select Monthly Income</option>
                                                                @foreach($db['salaries'] as $salary)
                                                                    <option value="{{ $salary->name }}" {{ old('monthly_income') == $salary->name ? 'selected' : '' }}>{{ $salary->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('monthly_income')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Work Country</label>
                                                            <select class="form-select" id="work_country" name="work_country">
                                                                <option value="">Select Work Country</option>
                                                                @foreach($db['countries'] as $country)
                                                                    <option value="{{ $country }}" {{ old('work_country') == $country ? 'selected' : '' }}>{{ $country }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Visa Status</label>
                                                            <select class="form-select" id="visa_status" name="visa_status">
                                                                <option value="">Select Visa Status</option>
                                                                @foreach($db['visaStatus'] as $visaStatus)
                                                                    <option value="{{ $visaStatus->name }}" {{ old('visa_status') == $visaStatus->name ? 'selected' : '' }}>{{ $visaStatus->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>


                                                        <div class="d-flex justify-content-between gap-2 mt-3">
                                                            <button type="button" class="btn btn-secondary prev-tab" data-bs-target="#personal_details"><i class="fas fa-arrow-left me-1"></i> Previous</button>
                                                            <button type="button" class="btn btn-primary next-tab" data-bs-target="#family_details">Next <i class="fas fa-arrow-right ms-1"></i></button>
                                                        </div>
                                                    </div>
                                                </div>



                                                {{-- Family Details  start --}}
                                                <div class="tab-pane" id="family_details" role="tabpanel">
                                                    <div class="row">

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Father Name <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="father_name" placeholder="Enter Father Name" value="{{ old('father_name') }}" required>
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Father Status</label>
                                                            <select class="form-select" name="father_profession">
                                                                <option value="">Select Father Status</option>
                                                                <option value="Employed" {{ old('father_profession') == 'Employed' ? 'selected' : '' }}>Employed</option>
                                                                <option value="Not Working" {{ old('father_profession') == 'Not Working' ? 'selected' : '' }}>Not Working</option>
                                                                <option value="Passed Away" {{ old('father_profession') == 'Passed Away' ? 'selected' : '' }}>Passed Away</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Mother Name <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="mother_name" placeholder="Enter Mother Name" value="{{ old('mother_name') }}" required>
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Mother Status</label>
                                                            <select class="form-select" name="mother_profession">
                                                                <option value="">Select Mother Status</option>
                                                                <option value="Employed" {{ old('mother_profession') == 'Employed' ? 'selected' : '' }}>Employed</option>
                                                                <option value="Not Working" {{ old('mother_profession') == 'Not Working' ? 'selected' : '' }}>Not Working</option>
                                                                <option value="Passed Away" {{ old('mother_profession') == 'Passed Away' ? 'selected' : '' }}>Passed Away</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Family Type</label>
                                                            <select class="form-select" name="family_type">
                                                                <option value="">Select Family Type</option>
                                                                <option value="Joint" {{ old('family_type') == 'Joint' ? 'selected' : '' }}>Joint</option>
                                                                <option value="Nuclear" {{ old('family_type') == 'Nuclear' ? 'selected' : '' }}>Nuclear</option>
                                                            </select>
                                                        </div>


                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Family Status</label>
                                                            <select class="form-select" name="family_status">
                                                                <option value="">Select Family Status</option>
                                                                <option value="Middle Class" {{ old('family_status') == 'Middle Class' ? 'selected' : '' }}>Middle Class</option>
                                                                <option value="Upper Middle Class" {{ old('family_status') == 'Upper Middle Class' ? 'selected' : '' }}>Upper Middle Class</option>
                                                                <option value="Rich" {{ old('family_status') == 'Rich' ? 'selected' : '' }}>Rich</option>
                                                                <option value="Affluent" {{ old('family_status') == 'Affluent' ? 'selected' : '' }}>Affluent</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Family Values</label>
                                                            <select class="form-select" name="family_values">
                                                                <option value="">Select Family Values</option>
                                                                <option value="Orthodox" {{ old('family_values') == 'Orthodox' ? 'selected' : '' }}>Orthodox</option>
                                                                <option value="Traditional" {{ old('family_values') == 'Traditional' ? 'selected' : '' }}>Traditional</option>
                                                                <option value="Moderate" {{ old('family_values') == 'Moderate' ? 'selected' : '' }}>Moderate</option>
                                                                <option value="Liberal" {{ old('family_values') == 'Liberal' ? 'selected' : '' }}>Liberal</option>
                                                            </select>
                                                        </div>


                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Elder Brothers</label>
                                                            <select class="form-select" name="elder_brother">
                                                                <option value="">Select Elder Brothers</option>
                                                                <option value="None" {{ old('elder_brother') == 'None' ? 'selected' : '' }}>None</option>
                                                                <option value="1" {{ old('elder_brother') == '1' ? 'selected' : '' }}>1</option>
                                                                <option value="2" {{ old('elder_brother') == '2' ? 'selected' : '' }}>2</option>
                                                                <option value="3" {{ old('elder_brother') == '3' ? 'selected' : '' }}>3</option>
                                                                <option value="4" {{ old('elder_brother') == '4' ? 'selected' : '' }}>4</option>
                                                                <option value="5" {{ old('elder_brother') == '5' ? 'selected' : '' }}>5</option>
                                                                <option value="6" {{ old('elder_brother') == '6' ? 'selected' : '' }}>6</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Younger Brothers</label>
                                                            <select class="form-select" name="younger_brother">
                                                                <option value="">Select Younger Brothers</option>
                                                                <option value="None" {{ old('younger_brother') == 'None' ? 'selected' : '' }}>None</option>
                                                                <option value="1" {{ old('younger_brother') == '1' ? 'selected' : '' }}>1</option>
                                                                <option value="2" {{ old('younger_brother') == '2' ? 'selected' : '' }}>2</option>
                                                                <option value="3" {{ old('younger_brother') == '3' ? 'selected' : '' }}>3</option>
                                                                <option value="4" {{ old('younger_brother') == '4' ? 'selected' : '' }}>4</option>
                                                                <option value="5" {{ old('younger_brother') == '5' ? 'selected' : '' }}>5</option>
                                                                <option value="6" {{ old('younger_brother') == '6' ? 'selected' : '' }}>6</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Elder Married Brothers</label>
                                                            <select class="form-select" name="elder_married_brother">
                                                                <option value="">Select Elder Married Brothers</option>
                                                                <option value="None" {{ old('elder_married_brother') == 'None' ? 'selected' : '' }}>None</option>
                                                                <option value="1" {{ old('elder_married_brother') == '1' ? 'selected' : '' }}>1</option>
                                                                <option value="2" {{ old('elder_married_brother') == '2' ? 'selected' : '' }}>2</option>
                                                                <option value="3" {{ old('elder_married_brother') == '3' ? 'selected' : '' }}>3</option>
                                                                <option value="4" {{ old('elder_married_brother') == '4' ? 'selected' : '' }}>4</option>
                                                                <option value="5" {{ old('elder_married_brother') == '5' ? 'selected' : '' }}>5</option>
                                                                <option value="6" {{ old('elder_married_brother') == '6' ? 'selected' : '' }}>6</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Younger Married Brothers</label>
                                                            <select class="form-select" name="younger_married_brother">
                                                                <option value="">Select Younger Married Brothers</option>
                                                                <option value="None" {{ old('younger_married_brother') == 'None' ? 'selected' : '' }}>None</option>
                                                                <option value="1" {{ old('younger_married_brother') == '1' ? 'selected' : '' }}>1</option>
                                                                <option value="2" {{ old('younger_married_brother') == '2' ? 'selected' : '' }}>2</option>
                                                                <option value="3" {{ old('younger_married_brother') == '3' ? 'selected' : '' }}>3</option>
                                                                <option value="4" {{ old('younger_married_brother') == '4' ? 'selected' : '' }}>4</option>
                                                                <option value="5" {{ old('younger_married_brother') == '5' ? 'selected' : '' }}>5</option>
                                                                <option value="6" {{ old('younger_married_brother') == '6' ? 'selected' : '' }}>6</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Elder Sisters</label>
                                                            <select class="form-select" name="elder_sister">
                                                                <option value="">Select Elder Sisters</option>
                                                                <option value="None" {{ old('elder_sister') == 'None' ? 'selected' : '' }}>None</option>
                                                                <option value="1" {{ old('elder_sister') == '1' ? 'selected' : '' }}>1</option>
                                                                <option value="2" {{ old('elder_sister') == '2' ? 'selected' : '' }}>2</option>
                                                                <option value="3" {{ old('elder_sister') == '3' ? 'selected' : '' }}>3</option>
                                                                <option value="4" {{ old('elder_sister') == '4' ? 'selected' : '' }}>4</option>
                                                                <option value="5" {{ old('elder_sister') == '5' ? 'selected' : '' }}>5</option>
                                                                <option value="6" {{ old('elder_sister') == '6' ? 'selected' : '' }}>6</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Younger Sisters</label>
                                                            <select class="form-select" name="younger_sister">
                                                                <option value="">Select Younger Sisters</option>
                                                                <option value="None" {{ old('younger_sister') == 'None' ? 'selected' : '' }}>None</option>
                                                                <option value="1" {{ old('younger_sister') == '1' ? 'selected' : '' }}>1</option>
                                                                <option value="2" {{ old('younger_sister') == '2' ? 'selected' : '' }}>2</option>
                                                                <option value="3" {{ old('younger_sister') == '3' ? 'selected' : '' }}>3</option>
                                                                <option value="4" {{ old('younger_sister') == '4' ? 'selected' : '' }}>4</option>
                                                                <option value="5" {{ old('younger_sister') == '5' ? 'selected' : '' }}>5</option>
                                                                <option value="6" {{ old('younger_sister') == '6' ? 'selected' : '' }}>6</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Elder Married Sisters</label>
                                                            <select class="form-select" name="elder_married_sister">
                                                                <option value="">Select Elder Married Sisters</option>
                                                                <option value="None" {{ old('elder_married_sister') == 'None' ? 'selected' : '' }}>None</option>
                                                                <option value="1" {{ old('elder_married_sister') == '1' ? 'selected' : '' }}>1</option>
                                                                <option value="2" {{ old('elder_married_sister') == '2' ? 'selected' : '' }}>2</option>
                                                                <option value="3" {{ old('elder_married_sister') == '3' ? 'selected' : '' }}>3</option>
                                                                <option value="4" {{ old('elder_married_sister') == '4' ? 'selected' : '' }}>4</option>
                                                                <option value="5" {{ old('elder_married_sister') == '5' ? 'selected' : '' }}>5</option>
                                                                <option value="6" {{ old('elder_married_sister') == '6' ? 'selected' : '' }}>6</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Younger Married Sisters</label>
                                                            <select class="form-select" name="younger_married_sister">
                                                                <option value="">Select Younger Married Sisters</option>
                                                                <option value="None" {{ old('younger_married_sister') == 'None' ? 'selected' : '' }}>None</option>
                                                                <option value="1" {{ old('younger_married_sister') == '1' ? 'selected' : '' }}>1</option>
                                                                <option value="2" {{ old('younger_married_sister') == '2' ? 'selected' : '' }}>2</option>
                                                                <option value="3" {{ old('younger_married_sister') == '3' ? 'selected' : '' }}>3</option>
                                                                <option value="4" {{ old('younger_married_sister') == '4' ? 'selected' : '' }}>4</option>
                                                                <option value="5" {{ old('younger_married_sister') == '5' ? 'selected' : '' }}>5</option>
                                                                <option value="6" {{ old('younger_married_sister') == '6' ? 'selected' : '' }}>6</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-lg-12 mb-3">
                                                            <label class="form-label">Property Details <span class="small text-muted fw-normal">(Enter number of properties e.g. 1, 2, 3)</span></label>
                                                            @php
                                                                $rawPropertyDetails = old('property_details', $userDetails->property_details ?? '');
                                                                $selectedProperties = \App\Http\Controllers\Helpers\DataController::parsePropertyDetails($rawPropertyDetails);
                                                            @endphp
                                                            <div class="row g-2">
                                                                @foreach($db['propertyDetails'] as $propertyDetail)
                                                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                                                        <div class="border rounded p-2 bg-light h-100">
                                                                            <label for="reg_prop_{{ $loop->index }}" class="form-label small fw-semibold text-truncate d-block mb-1" title="{{ $propertyDetail->name }}">{{ $propertyDetail->name }}</label>
                                                                            <input type="number" min="0" step="1" name="property_details[{{ $propertyDetail->name }}]" id="reg_prop_{{ $loop->index }}"
                                                                                   value="{{ old('property_details.' . $propertyDetail->name, $selectedProperties[$propertyDetail->name] ?? '') }}"
                                                                                   class="form-control form-control-sm"
                                                                                   placeholder="e.g. 1, 2">
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-12 mb-3">
                                                            <label class="form-label">Additional Property Details</label>
                                                            <textarea name="property_info" rows="3" class="form-control" placeholder="Enter additional property details...">{{ old('property_info', $userDetails->property_info ?? '') }}</textarea>
                                                        </div>


                                                        <div class="d-flex justify-content-between gap-2 mt-3">
                                                            <button type="button" class="btn btn-secondary prev-tab" data-bs-target="#education_job"><i class="fas fa-arrow-left me-1"></i> Previous</button>
                                                            <button type="button" class="btn btn-primary next-tab" data-bs-target="#horoscope_details">Next <i class="fas fa-arrow-right ms-1"></i></button>
                                                        </div>
                                                    </div>
                                                </div>


                                                {{-- Horoscope Details  start --}}
                                                <div class="tab-pane" id="horoscope_details" role="tabpanel">
                                                    <div class="row">

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Rashi <span class="text-danger">*</span></label>
                                                            <select class="form-select @error('rashi') is-invalid @enderror" name="rashi" required>
                                                                <option value="">Select Rashi</option>
                                                                @foreach($db['rashies'] as $rashi)
                                                                    <option value="{{ $rashi->name }}" {{ old('rashi') == $rashi->name ? 'selected' : '' }}>{{ $rashi->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('rashi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Nakshatra <span class="text-danger">*</span></label>
                                                            <select class="form-select @error('nakshatra') is-invalid @enderror" name="nakshatra" required>
                                                                <option value="">Select Nakshatra</option>
                                                                @foreach($db['nakshatras'] as $star)
                                                                    <option value="{{ $star->name }}" {{ old('nakshatra') == $star->name ? 'selected' : '' }}>{{ $star->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('nakshatra')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Gothram</label>
                                                            <select class="form-select" name="gothram">
                                                                <option value="">Select Gothram</option>
                                                                @foreach($db['gothrams'] as $gothram)
                                                                    <option value="{{ $gothram->name }}" {{ old('gothram') == $gothram->name ? 'selected' : '' }}>{{ $gothram->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Dosha <span class="text-danger">*</span></label>
                                                            <select class="form-select @error('dosham') is-invalid @enderror" name="dosham" required>
                                                                <option value="">Select Dosha</option>
                                                                @foreach($db['dosham'] as $dosham)
                                                                    <option value="{{ $dosham->name }}" {{ old('dosham') == $dosham->name ? 'selected' : '' }}>{{ $dosham->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('dosham')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Horoscope Image</label>
                                                            <input type="file" class="form-control @error('horoscope_image') is-invalid @enderror" name="horoscope_image">
                                                            @error('horoscope_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="d-flex justify-content-between gap-2 mt-3">
                                                            <button type="button" class="btn btn-secondary prev-tab" data-bs-target="#family_details"><i class="fas fa-arrow-left me-1"></i> Previous</button>
                                                            <button type="button" class="btn btn-primary next-tab" data-bs-target="#address_details">Next <i class="fas fa-arrow-right ms-1"></i></button>
                                                        </div>
                                                    </div>
                                                </div>


                                                {{-- Address Details  start --}}
                                                <div class="tab-pane" id="address_details" role="tabpanel">
                                                    <div class="row">

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Country <span class="text-danger">*</span></label>
                                                            <select class="form-select @error('country') is-invalid @enderror" id="country" name="country" required>
                                                                <option value="">Select Country</option>
                                                                @foreach($db['countries'] as $country)
                                                                    <option value="{{ $country }}" {{ old('country') == $country ? 'selected' : '' }}>{{ $country }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">State <span class="text-danger">*</span></label>
                                                            <select class="form-select @error('state') is-invalid @enderror" id="state" name="state" required>
                                                                <option value="">Select State</option>
                                                            </select>
                                                            @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">City <span class="text-danger">*</span></label>
                                                            <select class="form-select @error('city') is-invalid @enderror" id="city" name="city" required>
                                                                <option value="">Select City</option>
                                                            </select>
                                                            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Pincode <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control @error('pin_code') is-invalid @enderror" name="pin_code" placeholder="Enter Pincode" value="{{ old('pin_code') }}" required>
                                                            @error('pin_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>

                                                        <div class="col-lg-12 mb-3">
                                                            <label class="form-label">Address <span class="text-danger">*</span></label>
                                                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3" placeholder="Enter Address" required>{{ old('address') }}</textarea>
                                                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                        </div>


                                                        <div class="d-flex justify-content-between gap-2 mt-3">
                                                            <button type="button" class="btn btn-secondary prev-tab" data-bs-target="#horoscope_details"><i class="fas fa-arrow-left me-1"></i> Previous</button>
                                                            <button type="submit" class="btn btn-primary">Submit <i class="fas fa-check ms-1"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- End of Address Details  start --}}

                                        </div>

                                    </form>
                                </div>
                            </div>


                            <!-- end card -->
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                </div>
            </div>
        </div>
    </div>


    <link href="{{ asset('asset/css/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('asset/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: 'Select Property details',
                allowClear: true,
                width: '100%',
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form[action="{{ route("register-details.store") }}"]') || document.querySelector('form');
            const tabOrder = ['#personal_details', '#education_job', '#family_details', '#horoscope_details', '#address_details'];

            function getFieldLabel(input) {
                // Look for direct label in parent col
                let col = input.closest('.col-lg-4, .col-lg-6, .col-lg-12, .mb-3');
                if (col) {
                    let label = col.querySelector('label.form-label');
                    if (label) {
                        // Extract text excluding asterisk
                        let clone = label.cloneNode(true);
                        let danger = clone.querySelector('.text-danger');
                        if (danger) danger.remove();
                        let text = clone.textContent.trim();
                        if (text) return text;
                    }
                }
                return input.getAttribute('name') ? input.getAttribute('name').replace(/_/g, ' ') : 'This field';
            }

            function clearFieldInvalid(input) {
                input.classList.remove('is-invalid');
                let parent = input.closest('.mb-3') || input.parentElement;
                if (parent) {
                    let feedback = parent.querySelector('.client-invalid-feedback');
                    if (feedback) {
                        feedback.remove();
                    }
                }
            }

            function setFieldInvalid(input, message) {
                input.classList.add('is-invalid');
                let parent = input.closest('.mb-3') || input.parentElement;
                if (parent) {
                    let feedback = parent.querySelector('.client-invalid-feedback');
                    if (!feedback) {
                        feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback d-block client-invalid-feedback';
                        // Append after input-group or input or select2 container
                        let select2Container = parent.querySelector('.select2-container');
                        if (select2Container) {
                            select2Container.insertAdjacentElement('afterend', feedback);
                        } else {
                            let inputGroup = input.closest('.input-group');
                            if (inputGroup) {
                                inputGroup.insertAdjacentElement('afterend', feedback);
                            } else {
                                input.insertAdjacentElement('afterend', feedback);
                            }
                        }
                    }
                    feedback.textContent = message;
                }
            }

            function isFieldEmpty(input) {
                if (input.tagName.toLowerCase() === 'select') {
                    if (input.multiple) {
                        let selected = Array.from(input.selectedOptions).map(o => o.value).filter(v => v !== '');
                        return selected.length === 0;
                    }
                    return !input.value || input.value.trim() === '';
                }
                return !input.value || input.value.trim() === '';
            }

            function validateTab(tabPane) {
                if (!tabPane) return true;
                let requiredInputs = tabPane.querySelectorAll('input[required], select[required], textarea[required]');
                let isValid = true;
                let firstInvalid = null;

                requiredInputs.forEach(input => {
                    if (isFieldEmpty(input)) {
                        isValid = false;
                        let fieldLabel = getFieldLabel(input);
                        setFieldInvalid(input, `${fieldLabel} is required.`);
                        if (!firstInvalid) {
                            firstInvalid = input;
                        }
                    } else {
                        clearFieldInvalid(input);
                    }
                });

                if (!isValid && firstInvalid) {
                    firstInvalid.focus();
                    if (firstInvalid.scrollIntoView) {
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }

                return isValid;
            }

            // Real-time invalid clearance on change/input
            form.addEventListener('input', function (e) {
                if (e.target.classList.contains('is-invalid') && !isFieldEmpty(e.target)) {
                    clearFieldInvalid(e.target);
                }
            });

            form.addEventListener('change', function (e) {
                if (e.target.classList.contains('is-invalid') && !isFieldEmpty(e.target)) {
                    clearFieldInvalid(e.target);
                }
            });

            if (window.jQuery) {
                $(form).on('change select2:select select2:unselect select2:clear', 'select', function () {
                    if (this.classList.contains('is-invalid') && !isFieldEmpty(this)) {
                        clearFieldInvalid(this);
                    }
                });
            }

            // Next tab button click
            let nextButtons = document.querySelectorAll('.next-tab');
            nextButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    let currentTabPane = this.closest('.tab-pane');
                    if (validateTab(currentTabPane)) {
                        let targetTab = this.getAttribute('data-bs-target');
                        let tabElement = document.querySelector(`.nav-tabs a[href="${targetTab}"]`);
                        if (tabElement) {
                            let tabInstance = bootstrap.Tab.getOrCreateInstance(tabElement);
                            tabInstance.show();
                            let card = document.querySelector('.card');
                            if (card) {
                                window.scrollTo({ top: card.offsetTop - 20, behavior: 'smooth' });
                            }
                        }
                    }
                });
            });

            // Previous tab button click
            let prevButtons = document.querySelectorAll('.prev-tab');
            prevButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    let targetTab = this.getAttribute('data-bs-target');
                    let tabElement = document.querySelector(`.nav-tabs a[href="${targetTab}"]`);
                    if (tabElement) {
                        let tabInstance = bootstrap.Tab.getOrCreateInstance(tabElement);
                        tabInstance.show();
                        let card = document.querySelector('.card');
                        if (card) {
                            window.scrollTo({ top: card.offsetTop - 20, behavior: 'smooth' });
                        }
                    }
                });
            });

            // Direct nav-tabs header click validation
            let tabLinks = document.querySelectorAll('.nav-tabs .nav-link');
            tabLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    let targetSelector = this.getAttribute('href');
                    let currentActivePane = document.querySelector('.tab-pane.active');
                    if (!currentActivePane) return;

                    let currentIndex = tabOrder.indexOf('#' + currentActivePane.id);
                    let targetIndex = tabOrder.indexOf(targetSelector);

                    if (targetIndex === -1 || targetIndex === currentIndex) return;

                    // If navigating forward, validate all preceding tabs
                    if (targetIndex > currentIndex) {
                        for (let i = 0; i < targetIndex; i++) {
                            let paneToValidate = document.querySelector(tabOrder[i]);
                            if (!validateTab(paneToValidate)) {
                                // Switch to the earliest failed tab if not currently on it
                                let failedTabLink = document.querySelector(`.nav-tabs a[href="${tabOrder[i]}"]`);
                                if (failedTabLink) {
                                    bootstrap.Tab.getOrCreateInstance(failedTabLink).show();
                                }
                                return false;
                            }
                        }
                    }

                    // If navigating backwards or all preceding tabs are valid
                    let targetTabElement = document.querySelector(`.nav-tabs a[href="${targetSelector}"]`);
                    if (targetTabElement) {
                        bootstrap.Tab.getOrCreateInstance(targetTabElement).show();
                        let card = document.querySelector('.card');
                        if (card) {
                            window.scrollTo({ top: card.offsetTop - 20, behavior: 'smooth' });
                        }
                    }
                });
            });

            // Form Submit validation across all tabs
            form.addEventListener('submit', function (e) {
                for (let i = 0; i < tabOrder.length; i++) {
                    let tabPane = document.querySelector(tabOrder[i]);
                    if (!validateTab(tabPane)) {
                        e.preventDefault();
                        e.stopPropagation();
                        let failedTabLink = document.querySelector(`.nav-tabs a[href="${tabOrder[i]}"]`);
                        if (failedTabLink) {
                            bootstrap.Tab.getOrCreateInstance(failedTabLink).show();
                        }
                        return false;
                    }
                }
            });

            // Initialize Select2
            if (window.jQuery && $.fn.select2) {
                $('.select2').select2({
                    placeholder: 'Select Education',
                    width: '100%'
                });
            }
        });
    </script>

    @include('admin.includes.footer')
@endsection
