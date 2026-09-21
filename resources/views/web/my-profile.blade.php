@extends('web.layouts.layout')
@section('title', $metaTags->title ?? '')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <div class="container my-5">
        <div class="row g-4">

            <div class="col-lg-8">
                @if (session('profile') || session('personal') || session('horoscope') || session('education') || session('family') || session('address') || session('success'))
                    <div class="alert alert-success">
                        {{ session('profile') ?? session('personal') ?? session('horoscope') ?? session('education') ?? session('family') ?? session('address') ?? session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif


                @php
                    $profileImage = $userAndUserDetails->profile_image ?? '';
                    $gender = $userAndUserDetails->gender ?? '';
                    $image = $gender === 'Female' ? 'female.webp' : 'male.webp';
                @endphp
                <div class="card p-4 shadow-sm rounded-4">
                        <form method="POST" action="{{ route('my-profile.update', $userAndUserDetails->user_id) }}">
                            @csrf
                            @method('PUT')
                            {{--Personal Info--}}
                            <h5 class="fw-semibold mb-4">Personal Details</h5>
                            <div class="text-center mb-4">
                                <div class="position-relative d-inline-block">
                                    <input type="file" id="profileImageInput"  class="d-none">
                                    <input type="hidden" name="profile_image" id="croppedImageInput">
                                    <label for="profileImageInput" class="d-block mb-0">
                                        <img
                                            src="{{ $profileImage && file_exists(public_path('Profile Image/' . $profileImage))
                                            ? asset('Profile Image/' . $profileImage)
                                            : asset('asset/img/default/' . $image) }}" alt="Profile Photo" class="rounded-circle" width="120" height="120" style="object-fit: cover; cursor: pointer;"
                                        >
                                        <span class="position-absolute bottom-0 start-50 translate-middle-x bg-white border rounded-circle p-1 shadow" style="margin-bottom: -10px;">
                                            <i class="bi bi-camera-fill text-danger"></i>
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <div class="image-container" style="max-width: 100%; margin-bottom: 10px;">
                                    <img id="previewImage" style="max-width: 100%; display:none;" alt="" src="">
                                </div>
                                <button type="button" id="cropButton" class="btn button2 mb-2" style="display:none;">Crop Image</button>
                            </div>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label for="dob" class="form-label">Date of Birth <span class="text-danger"> *</span></label>
                                    <input type="date" id="dob" name="dob" class="form-control rounded-pill shadow-none @error('dob') is-invalid @enderror" value="{{ $userAndUserDetails->dob }}" max="{{ \Carbon\Carbon::now()->subYears(18)->format('Y-m-d') }}" required>
                                    @error('dob')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Birth Time <span class="text-danger"> *</span></label>
                                    <div class="input-group">
                                        <!-- Hours -->
                                        <select class="form-control rounded-start-pill shadow-none" id="hour" name="hour" required>
                                            <option value="" disabled selected>Hour</option>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                                    {{ (!empty($userAndUserDetails->birth_time) && date('h', strtotime($userAndUserDetails->birth_time)) == str_pad($i, 2, '0', STR_PAD_LEFT)) ? 'selected' : '' }}>
                                                    {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                                </option>
                                            @endfor
                                        </select>
                                        <!-- Minutes -->
                                        <select class="form-control shadow-none" id="minute" name="minute" required>
                                            <option value="" disabled selected>Minute</option>
                                            @for ($i = 0; $i < 60; $i++)
                                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                                    {{ (!empty($userAndUserDetails->birth_time) && date('i', strtotime($userAndUserDetails->birth_time)) == str_pad($i, 2, '0', STR_PAD_LEFT)) ? 'selected' : '' }}>
                                                    {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                                </option>
                                            @endfor
                                        </select>
                                        <!-- AM/PM -->
                                        <select class="form-control rounded-end-pill shadow-none" id="ampm" name="ampm" required>
                                            <option value="" disabled selected>AM/PM</option>
                                            <option value="AM" {{ (!empty($userAndUserDetails->birth_time) && date('A', strtotime($userAndUserDetails->birth_time)) == 'AM') ? 'selected' : '' }}>AM</option>
                                            <option value="PM" {{ (!empty($userAndUserDetails->birth_time) && date('A', strtotime($userAndUserDetails->birth_time)) == 'PM') ? 'selected' : '' }}>PM</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="birth_country" class="form-label">Birth Country <span class="text-danger"> *</span></label>
                                    <select name="birth_country" id="birth_country" class="form-select rounded-pill shadow-none @error('birth_country') is-invalid @enderror" required>
                                        <option value="">Select Country</option>
                                        @foreach($locationData['birth_countries'] as $country)
                                            <option value="{{ $country->name }}" {{ $userAndUserDetails->birth_country == $country->name ? 'selected' : '' }}>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('birth_country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="birth_state" class="form-label">Birth State <span class="text-danger"> *</span></label>
                                    <select name="birth_state" id="birth_state" class="form-select rounded-pill shadow-none @error('birth_state') is-invalid @enderror" required>
                                        <option value="">Select State</option>
                                        @foreach($locationData['birth_states'] as $state)
                                            <option value="{{ $state->name }}" {{ $userAndUserDetails->birth_state == $state->name ? 'selected' : '' }}>{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('birth_state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="birth_city" class="form-label">Birth City <span class="text-danger"> *</span></label>
                                    <select name="birth_city" id="birth_city" class="form-select rounded-pill shadow-none @error('birth_city') is-invalid @enderror" required>
                                        <option value="">Select City</option>
                                        @foreach($locationData['birth_cities'] as $city)
                                            <option value="{{ $city->name }}" {{ $userAndUserDetails->birth_city == $city->name ? 'selected' : '' }}>{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('birth_city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="mother_tongue" class="form-label">Mother Tongue <span class="text-danger"> *</span></label>
                                    <select name="mother_tongue" id="mother_tongue" class="form-select rounded-pill shadow-none @error('mother_tongue') is-invalid @enderror" required>
                                        <option value="">Select Mother Tongue</option>
                                        @foreach($db['languages'] as $language)
                                            <option value="{{ $language->language }}" {{ $userAndUserDetails->mother_tongue == $language->language ? 'selected' : '' }}>
                                                {{ $language->language }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('mother_tongue')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="marital_status" class="form-label">Marital Status <span class="text-danger"> *</span></label>
                                    <select name="marital_status" id="marital_status" class="form-select rounded-pill shadow-none @error('marital_status') is-invalid @enderror">
                                        <option value="">Select Marital Status</option>
                                        @foreach($db['maritalStatuses'] as $maritalStatus)
                                            <option value="{{ $maritalStatus->name }}" {{  $userAndUserDetails->marital_status == $maritalStatus->name ? 'selected' : '' }}>{{ $maritalStatus->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('marital_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="ethnicity" class="form-label">Ethnicity</label>
                                    <select name="ethnicity" id="ethnicity" class="form-select rounded-pill shadow-none">
                                        <option value="">Select Ethnicity</option>
                                        @foreach($db['ethnicity'] as $ethnicity)
                                            <option value="{{ $ethnicity->name }}" {{ $userAndUserDetails->ethnicity == $ethnicity->name ? 'selected' : '' }}>
                                                {{ $ethnicity->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="nationality" class="form-label">Nationality</label>
                                    <select name="nationality" id="nationality" class="form-select rounded-pill shadow-none">
                                        <option value="">Select Nationality</option>
                                        @foreach($db['nationality'] as $nationality)
                                            <option value="{{ $nationality->name }}" {{ $userAndUserDetails->nationality == $nationality->name ? 'selected' : '' }}>
                                                {{ $nationality->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="religion" class="form-label">Religion <span class="text-danger"> *</span></label>
                                    <select name="religion" id="religion" class="form-select rounded-pill shadow-none @error('religion') is-invalid @enderror">
                                        <option value="">Select Religion</option>
                                        @foreach($db['religions'] as $religion)
                                            <option value="{{ $religion->name }}" {{ $userAndUserDetails->religion == $religion->name ? 'selected' : '' }}>{{ $religion->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('religion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="caste" class="form-label">Caste <span class="text-danger"> *</span></label>
                                    <select name="caste" id="caste" class="form-select rounded-pill shadow-none @error('caste') is-invalid @enderror">
                                        <option value="">Select Caste</option>
                                        @foreach($dropdownData['castes'] as $caste)
                                            <option value="{{ $caste->name }}" {{ $userAndUserDetails->caste == $caste->name ? 'selected' : '' }}>
                                                {{ $caste->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('caste')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="sub_caste" class="form-label">Sub Caste <span class="text-danger"> *</span></label>
                                    <select name="sub_caste" id="sub_caste" class="form-select rounded-pill shadow-none @error('sub_caste') is-invalid @enderror" required>
                                        <option value="">Select Sub Caste</option>
                                        @foreach($dropdownData['subCastes'] as $subCaste)
                                            <option value="{{ $subCaste->name }}" {{ $userAndUserDetails->sub_caste == $subCaste->name ? 'selected' : '' }}>
                                                {{ $subCaste->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('sub_caste')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="physical_status" class="form-label">Physical Status <span class="text-danger"> *</span></label>
                                    <select name="physical_status" id="physical_status" class="form-select rounded-pill shadow-none @error('physical_status') is-invalid @enderror">
                                        <option value="">Select Physical Status</option>
                                        <option value="Normal" {{ $userAndUserDetails->physical_status == 'Normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="Physically Challenged" {{ $userAndUserDetails->physical_status == 'Physically Challenged' ? 'selected' : '' }}>Physically Challenged</option>
                                    </select>
                                    @error('physical_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="skin_tone" class="form-label">Skin Tone <span class="text-danger"> *</span></label>
                                    <select name="skin_tone" id="skin_tone" class="form-select rounded-pill shadow-none @error('skin_tone') is-invalid @enderror" required>
                                        <option value="">Select Skin Tone</option>
                                        @foreach($db['skinTones'] as $skinTone)
                                            <option value="{{ $skinTone->name }}" {{ $userAndUserDetails->skin_tone == $skinTone->name ? 'selected' : '' }}>
                                                {{ $skinTone->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('skin_tone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="height" class="form-label">Height <span class="text-danger"> *</span></label>
                                    <select name="height" id="height" class="form-select rounded-pill shadow-none @error('height') is-invalid @enderror" required>
                                        <option value="">Select Height</option>
                                        @foreach($db['heights'] as $height)
                                            <option value="{{ $height->name }}" {{ $userAndUserDetails->height == $height->name ? 'selected' : '' }}>{{ $height->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('height')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="weight" class="form-label">Weight (in Kg) <span class="text-danger"> *</span></label>
                                    <input class="form-control rounded-pill shadow-none @error('weight') is-invalid @enderror" type="number" name="weight" id="weight" placeholder="Enter weight in Kg" value="{{ $userAndUserDetails->weight }}" required>
                                    @error('weight')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="body_type" class="form-label">Body Type <span class="text-danger"> *</span></label>
                                    <select name="body_type" id="body_type" class="form-select rounded-pill shadow-none @error('body_type') is-invalid @enderror" required>
                                        <option value="">Select Body Type</option>
                                        @foreach($db['bodyTypes'] as $bodyType)
                                            <option value="{{ $bodyType->name }}" {{ $userAndUserDetails->body_type == $bodyType->name ? 'selected' : '' }}>
                                                {{ $bodyType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('body_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="eating_habit" class="form-label">Eating Habit <span class="text-danger"> *</span></label>
                                    <select name="eating_habit" id="eating_habit" class="form-select rounded-pill shadow-none @error('eating_habit') is-invalid @enderror" required>
                                        <option value="">Select Eating Habit</option>
                                        @foreach($db['eatingHabits'] as $eatingHabit)
                                            <option value="{{ $eatingHabit->name }}" {{ $userAndUserDetails->eating_habit == $eatingHabit->name ? 'selected' : '' }}>{{ $eatingHabit->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('eating_habit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="drinking_habit" class="form-label">Drinking Habit <span class="text-danger"> *</span></label>
                                    <select name="drinking_habit" id="drinking_habit" class="form-select rounded-pill shadow-none @error('drinking_habit') is-invalid @enderror" required>
                                        <option value="">Select Drinking Habit</option>
                                        @foreach($db['drinkingHabits'] as $drinkingHabit)
                                            <option value="{{ $drinkingHabit->name }}" {{ $userAndUserDetails->drinking_habit == $drinkingHabit->name ? 'selected' : '' }}>{{ $drinkingHabit->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('drinking_habit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="smoking_habit" class="form-label">Smoking Habit <span class="text-danger"> *</span></label>
                                    <select name="smoking_habit" id="smoking_habit" class="form-select rounded-pill shadow-none @error('smoking_habit') is-invalid @enderror" required>
                                        <option value="">Select Smoking Habit</option>
                                        @foreach($db['smokingHabits'] as $smokingHabit)
                                            <option value="{{ $smokingHabit->name }}" {{ $userAndUserDetails->smoking_habit == $smokingHabit->name ? 'selected' : '' }}>{{ $smokingHabit->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('smoking_habit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

{{--                                <div class="col-md-12">--}}
{{--                                    <label for="about_me" class="form-label">About Me</label>--}}
{{--                                    <textarea id="about_me" class="form-control rounded-pill shadow-none" name="about_me">{{ $userAndUserDetails->about_me }}</textarea>--}}
{{--                                </div>--}}

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn button2 px-5 py-2">Update</button>
                                </div>
                            </div>
                        </form>


                        <hr>
                        {{--Education & Career--}}
                        <h5 class="fw-semibold mb-4">Professional Details</h5>
                        <form method="POST" action="{{ route('my-profile.update', $userAndUserDetails->user_id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="educations" class="form-label">Education <span class="text-danger"> *</span></label>
                                    @php
                                        $selectedEducations = !empty($userAndUserDetails->education)
                                            ? array_map('trim', explode(',', $userAndUserDetails->education))
                                            : [];
                                    @endphp
                                    <select name="education[]" id="educations" class="form-select rounded-pill select2 shadow-none @error('education') is-invalid @enderror" multiple="multiple" data-placeholder="Select Education">
                                        @foreach($db['combinedEducations'] as $level)
                                            <optgroup label="{{ $level->level_name }}">
                                                @foreach($level->educations as $education)
                                                    <option value="{{ $education->name }}" {{ in_array($education->name, $selectedEducations) || $userAndUserDetails->education == $education->name ? 'selected' : '' }}>{{ $education->name }}</option>
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
                                    <label for="occupations" class="form-label">Occupation <span class="text-danger"> *</span></label>
                                    <select name="occupation" id="occupations" class="form-select rounded-pill shadow-none @error('occupation') is-invalid @enderror">
                                        <option value="">Select Occupation</option>
                                        @foreach($db['combinedOccupations'] as $combinedOccupation)
                                            <optgroup label="{{ $combinedOccupation->type_name }}">
                                                @foreach($combinedOccupation->occupations as $occupation)
                                                    <option value="{{ $occupation->name }}" {{ $userAndUserDetails->occupation == $occupation->name ? 'selected' : '' }}>{{ $occupation->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    @error('occupation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="employed_in" class="form-label">Employed In <span class="text-danger"> *</span></label>
                                    <select name="employed_in" id="employed_in" class="form-select rounded-pill shadow-none @error('employed_in') is-invalid @enderror" required>
                                        <option value="">Select Employed In</option>
                                        @foreach($db['employedIns'] as $employedIn)
                                            <option value="{{ $employedIn->name }}" {{ $userAndUserDetails->employed_in  == $employedIn->name ? 'selected' : '' }}>{{ $employedIn->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('employed_in')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="monthly_income" class="form-label">Monthly Income <span class="text-danger"> *</span></label>
                                    <select name="monthly_income" id="monthly_income" class="form-select rounded-pill shadow-none @error('monthly_income') is-invalid @enderror" required>
                                        <option value="">Select Monthly Income</option>
                                        @foreach($db['salaries'] as $salary)
                                            <option value="{{ $salary->name }}" {{ $userAndUserDetails->monthly_income == $salary->name ? 'selected' : '' }}>{{ $salary->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('monthly_income')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="workCountry" class="form-label">Work Country</label>
                                    <select name="work_country" id="workCountry" class="form-select rounded-pill shadow-none">
                                        <option value="">Select Work Country</option>
                                        @foreach($locationData['countries'] as $country)
                                            <option value="{{ $country->name }}" {{ $userAndUserDetails->work_country == $country->name ? 'selected' : '' }}>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="visa_status" class="form-label">Visa Status</label>
                                    <select name="visa_status" id="visa_status" class="form-select rounded-pill shadow-none">
                                        <option value="">Select Visa Status</option>
                                        @foreach($db['visaStatus'] as $visaStatus)
                                            <option value="{{ $visaStatus->name }}" {{ $userAndUserDetails->visa_status == $visaStatus->name ? 'selected' : '' }}>{{ $visaStatus->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn button2 px-5 py-2">Update</button>
                                </div>
                            </div>
                        </form>

                        <hr>
                        {{--Family Info--}}
                        <h5 class="fw-semibold mb-4">Family Details</h5>
                        <form method="POST" action="{{ route('my-profile.update', $userAndUserDetails->user_id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="father_name" class="form-label">Father Name <span class="text-danger"> *</span></label>
                                    <input type="text" name="father_name" id="father_name" class="form-control rounded-pill shadow-none @error('father_name') is-invalid @enderror" placeholder="Enter Father Name" value="{{ old('father_name', $userAndUserDetails->father_name ?? '') }}" required>
                                    @error('father_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="father_profession" class="form-label">Father Status</label>
                                    <select name="father_profession" id="father_profession" class="form-select rounded-pill shadow-none">
                                        <option value="">Select Father Status</option>
                                        <option value="Employed" {{ strcasecmp($userAndUserDetails->father_profession ?? '', 'Employed') === 0 ? 'selected' : '' }}>Employed</option>
                                        <option value="Not Working" {{ strcasecmp($userAndUserDetails->father_profession ?? '', 'Not Working') === 0 ? 'selected' : '' }}>Not Working</option>
                                        <option value="Passed Away" {{ in_array(strtolower(trim($userAndUserDetails->father_profession ?? '')), ['passed away', 'passedaway']) ? 'selected' : '' }}>Passed Away</option>
                                        @if(!empty($userAndUserDetails->father_profession) && !in_array(strtolower(trim($userAndUserDetails->father_profession)), ['employed', 'not working', 'passed away', 'passedaway']))
                                            <option value="{{ $userAndUserDetails->father_profession }}" selected>{{ $userAndUserDetails->father_profession }}</option>
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="mother_name" class="form-label">Mother Name <span class="text-danger"> *</span></label>
                                    <input type="text" name="mother_name" id="mother_name" class="form-control rounded-pill shadow-none @error('mother_name') is-invalid @enderror" placeholder="Enter Mother Name" value="{{ old('mother_name', $userAndUserDetails->mother_name ?? '') }}" required>
                                    @error('mother_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="mother_profession" class="form-label">Mother Status</label>
                                    <select name="mother_profession" id="mother_profession" class="form-select rounded-pill shadow-none">
                                        <option value="">Select Mother Status</option>
                                        <option value="Employed" {{ strcasecmp($userAndUserDetails->mother_profession ?? '', 'Employed') === 0 ? 'selected' : '' }}>Employed</option>
                                        <option value="Not Working" {{ strcasecmp($userAndUserDetails->mother_profession ?? '', 'Not Working') === 0 ? 'selected' : '' }}>Not Working</option>
                                        <option value="Passed Away" {{ in_array(strtolower(trim($userAndUserDetails->mother_profession ?? '')), ['passed away', 'passedaway']) ? 'selected' : '' }}>Passed Away</option>
                                        @if(!empty($userAndUserDetails->mother_profession) && !in_array(strtolower(trim($userAndUserDetails->mother_profession)), ['employed', 'not working', 'passed away', 'passedaway']))
                                            <option value="{{ $userAndUserDetails->mother_profession }}" selected>{{ $userAndUserDetails->mother_profession }}</option>
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="family_type" class="form-label">Family Type</label>
                                    <select name="family_type" id="family_type" class="form-select rounded-pill shadow-none">
                                        <option value="">Select Family Type</option>
                                        <option value="Joint" {{ strcasecmp($userAndUserDetails->family_type ?? '', 'Joint') === 0 ? 'selected' : '' }}>Joint</option>
                                        <option value="Nuclear" {{ strcasecmp($userAndUserDetails->family_type ?? '', 'Nuclear') === 0 ? 'selected' : '' }}>Nuclear</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="family_status" class="form-label">Family Status</label>
                                    <select name="family_status" id="family_status" class="form-select rounded-pill shadow-none">
                                        <option value="">Select Family Status</option>
                                        <option value="Middle Class" {{ strcasecmp($userAndUserDetails->family_status ?? '', 'Middle Class') === 0 ? 'selected' : '' }}>Middle Class</option>
                                        <option value="Upper Middle Class" {{ strcasecmp($userAndUserDetails->family_status ?? '', 'Upper Middle Class') === 0 ? 'selected' : '' }}>Upper Middle Class</option>
                                        <option value="Rich" {{ strcasecmp($userAndUserDetails->family_status ?? '', 'Rich') === 0 ? 'selected' : '' }}>Rich</option>
                                        <option value="Affluent" {{ strcasecmp($userAndUserDetails->family_status ?? '', 'Affluent') === 0 ? 'selected' : '' }}>Affluent</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="family_values" class="form-label">Family Values</label>
                                    <select name="family_values" id="family_values" class="form-select rounded-pill shadow-none">
                                        <option value="">Select Family Values</option>
                                        <option value="Orthodox" {{ strcasecmp($userAndUserDetails->family_values ?? '', 'Orthodox') === 0 ? 'selected' : '' }}>Orthodox</option>
                                        <option value="Traditional" {{ strcasecmp($userAndUserDetails->family_values ?? '', 'Traditional') === 0 ? 'selected' : '' }}>Traditional</option>
                                        <option value="Moderate" {{ strcasecmp($userAndUserDetails->family_values ?? '', 'Moderate') === 0 ? 'selected' : '' }}>Moderate</option>
                                        <option value="Liberal" {{ strcasecmp($userAndUserDetails->family_values ?? '', 'Liberal') === 0 ? 'selected' : '' }}>Liberal</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="elder_brother" class="form-label">Elder Brothers</label>
                                    <select name="elder_brother" id="elder_brother" class="form-select rounded-pill shadow-none">
                                        <option value="">Select Elder Brothers</option>
                                        <option value="None" {{ ($userAndUserDetails->elder_brother ?? '') == 'None' ? 'selected' : '' }}>None</option>
                                        <option value="1" {{ ($userAndUserDetails->elder_brother ?? '') == '1' ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ ($userAndUserDetails->elder_brother ?? '') == '2' ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ ($userAndUserDetails->elder_brother ?? '') == '3' ? 'selected' : '' }}>3</option>
                                        <option value="4" {{ ($userAndUserDetails->elder_brother ?? '') == '4' ? 'selected' : '' }}>4</option>
                                        <option value="5" {{ ($userAndUserDetails->elder_brother ?? '') == '5' ? 'selected' : '' }}>5</option>
                                        <option value="6" {{ ($userAndUserDetails->elder_brother ?? '') == '6' ? 'selected' : '' }}>6</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="younger_brother" class="form-label">Younger Brothers</label>
                                    <select name="younger_brother" id="younger_brother" class="form-select rounded-pill shadow-none">
                                        <option value="">Select Younger Brothers</option>
                                        <option value="None" {{ ($userAndUserDetails->younger_brother ?? '') == 'None' ? 'selected' : '' }}>None</option>
                                        <option value="1" {{ ($userAndUserDetails->younger_brother ?? '') == '1' ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ ($userAndUserDetails->younger_brother ?? '') == '2' ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ ($userAndUserDetails->younger_brother ?? '') == '3' ? 'selected' : '' }}>3</option>
                                        <option value="4" {{ ($userAndUserDetails->younger_brother ?? '') == '4' ? 'selected' : '' }}>4</option>
                                        <option value="5" {{ ($userAndUserDetails->younger_brother ?? '') == '5' ? 'selected' : '' }}>5</option>
                                        <option value="6" {{ ($userAndUserDetails->younger_brother ?? '') == '6' ? 'selected' : '' }}>6</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="elder_married_brother" class="form-label">Elder Married Brothers</label>
                                    <select name="elder_married_brother" id="elder_married_brother" class="form-select rounded-pill shadow-none">
                                        <option value="">Select Elder Married Brothers</option>
                                        <option value="None" {{ ($userAndUserDetails->elder_married_brother ?? '') == 'None' ? 'selected' : '' }}>None</option>
                                        <option value="1" {{ ($userAndUserDetails->elder_married_brother ?? '') == '1' ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ ($userAndUserDetails->elder_married_brother ?? '') == '2' ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ ($userAndUserDetails->elder_married_brother ?? '') == '3' ? 'selected' : '' }}>3</option>
                                        <option value="4" {{ ($userAndUserDetails->elder_married_brother ?? '') == '4' ? 'selected' : '' }}>4</option>
                                        <option value="5" {{ ($userAndUserDetails->elder_married_brother ?? '') == '5' ? 'selected' : '' }}>5</option>
                                        <option value="6" {{ ($userAndUserDetails->elder_married_brother ?? '') == '6' ? 'selected' : '' }}>6</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="younger_married_brother" class="form-label">Younger Married Brothers</label>
                                    <select name="younger_married_brother" id="younger_married_brother" class="form-select rounded-pill shadow-none">
                                        <option value="">Select Younger Married Brothers</option>
                                        <option value="None" {{ ($userAndUserDetails->younger_married_brother ?? '') == 'None' ? 'selected' : '' }}>None</option>
                                        <option value="1" {{ ($userAndUserDetails->younger_married_brother ?? '') == '1' ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ ($userAndUserDetails->younger_married_brother ?? '') == '2' ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ ($userAndUserDetails->younger_married_brother ?? '') == '3' ? 'selected' : '' }}>3</option>
                                        <option value="4" {{ ($userAndUserDetails->younger_married_brother ?? '') == '4' ? 'selected' : '' }}>4</option>
                                        <option value="5" {{ ($userAndUserDetails->younger_married_brother ?? '') == '5' ? 'selected' : '' }}>5</option>
                                        <option value="6" {{ ($userAndUserDetails->younger_married_brother ?? '') == '6' ? 'selected' : '' }}>6</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="elder_sister" class="form-label">Elder Sisters</label>
                                    <select name="elder_sister" id="elder_sister" class="form-select rounded-pill shadow-none">
                                        <option value="">Select Elder Sisters</option>
                                        <option value="None" {{ ($userAndUserDetails->elder_sister ?? '') == 'None' ? 'selected' : '' }}>None</option>
                                        <option value="1" {{ ($userAndUserDetails->elder_sister ?? '') == '1' ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ ($userAndUserDetails->elder_sister ?? '') == '2' ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ ($userAndUserDetails->elder_sister ?? '') == '3' ? 'selected' : '' }}>3</option>
                                        <option value="4" {{ ($userAndUserDetails->elder_sister ?? '') == '4' ? 'selected' : '' }}>4</option>
                                        <option value="5" {{ ($userAndUserDetails->elder_sister ?? '') == '5' ? 'selected' : '' }}>5</option>
                                        <option value="6" {{ ($userAndUserDetails->elder_sister ?? '') == '6' ? 'selected' : '' }}>6</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="younger_sister" class="form-label">Younger Sisters</label>
                                    <select name="younger_sister" id="younger_sister" class="form-select rounded-pill shadow-none">
                                        <option value="">Select Younger Sisters</option>
                                        <option value="None" {{ ($userAndUserDetails->younger_sister ?? '') == 'None' ? 'selected' : '' }}>None</option>
                                        <option value="1" {{ ($userAndUserDetails->younger_sister ?? '') == '1' ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ ($userAndUserDetails->younger_sister ?? '') == '2' ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ ($userAndUserDetails->younger_sister ?? '') == '3' ? 'selected' : '' }}>3</option>
                                        <option value="4" {{ ($userAndUserDetails->younger_sister ?? '') == '4' ? 'selected' : '' }}>4</option>
                                        <option value="5" {{ ($userAndUserDetails->younger_sister ?? '') == '5' ? 'selected' : '' }}>5</option>
                                        <option value="6" {{ ($userAndUserDetails->younger_sister ?? '') == '6' ? 'selected' : '' }}>6</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="elder_married_sister" class="form-label">Elder Married Sisters</label>
                                    <select name="elder_married_sister" id="elder_married_sister" class="form-select rounded-pill shadow-none">
                                        <option value="">Select Elder Married Sisters</option>
                                        <option value="None" {{ ($userAndUserDetails->elder_married_sister ?? '') == 'None' ? 'selected' : '' }}>None</option>
                                        <option value="1" {{ ($userAndUserDetails->elder_married_sister ?? '') == '1' ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ ($userAndUserDetails->elder_married_sister ?? '') == '2' ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ ($userAndUserDetails->elder_married_sister ?? '') == '3' ? 'selected' : '' }}>3</option>
                                        <option value="4" {{ ($userAndUserDetails->elder_married_sister ?? '') == '4' ? 'selected' : '' }}>4</option>
                                        <option value="5" {{ ($userAndUserDetails->elder_married_sister ?? '') == '5' ? 'selected' : '' }}>5</option>
                                        <option value="6" {{ ($userAndUserDetails->elder_married_sister ?? '') == '6' ? 'selected' : '' }}>6</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="younger_married_sister" class="form-label">Younger Married Sisters</label>
                                    <select name="younger_married_sister" id="younger_married_sister" class="form-select rounded-pill shadow-none">
                                        <option value="">Select Younger Married Sisters</option>
                                        <option value="None" {{ ($userAndUserDetails->younger_married_sister ?? '') == 'None' ? 'selected' : '' }}>None</option>
                                        <option value="1" {{ ($userAndUserDetails->younger_married_sister ?? '') == '1' ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ ($userAndUserDetails->younger_married_sister ?? '') == '2' ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ ($userAndUserDetails->younger_married_sister ?? '') == '3' ? 'selected' : '' }}>3</option>
                                        <option value="4" {{ ($userAndUserDetails->younger_married_sister ?? '') == '4' ? 'selected' : '' }}>4</option>
                                        <option value="5" {{ ($userAndUserDetails->younger_married_sister ?? '') == '5' ? 'selected' : '' }}>5</option>
                                        <option value="6" {{ ($userAndUserDetails->younger_married_sister ?? '') == '6' ? 'selected' : '' }}>6</option>
                                    </select>
                                </div>

                                @php
                                    $selectedProperties = \App\Http\Controllers\Helpers\DataController::parsePropertyDetails($userAndUserDetails->property_details ?? '');
                                @endphp
                                <div class="col-md-12">
                                    <label class="form-label fw-medium">Property Details <span class="small text-muted fw-normal">(Enter number of properties e.g. 1, 2, 3)</span></label>
                                    <div class="row g-2">
                                        @foreach($db['propertyDetails'] as $propertyDetail)
                                            <div class="col-lg-3 col-md-4 col-sm-6">
                                                <div class="border rounded-3 p-2 bg-light bg-opacity-50 h-100">
                                                    <label for="profile_prop_{{ $loop->index }}" class="form-label small fw-semibold text-truncate d-block mb-1" title="{{ $propertyDetail->name }}">{{ $propertyDetail->name }}</label>
                                                    <input type="number" min="0" step="1" name="property_details[{{ $propertyDetail->name }}]" id="profile_prop_{{ $loop->index }}"
                                                           value="{{ old('property_details.' . $propertyDetail->name, $selectedProperties[$propertyDetail->name] ?? '') }}"
                                                           class="form-control form-control-sm rounded-pill shadow-none"
                                                           placeholder="e.g. 1, 2">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="property_info" class="form-label">Additional Property Details</label>
                                    <textarea name="property_info" id="property_info" rows="3" class="form-control rounded-4 shadow-none" placeholder="Enter additional property details...">{{ old('property_info', $userAndUserDetails->property_info ?? '') }}</textarea>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn button2 px-5 py-2">Update</button>
                                </div>
                            </div>
                        </form>

                        <hr>
                        {{--Horoscope Info--}}
                        <h5 class="fw-semibold mb-4">Horoscope Details</h5>
                        <form method="POST" action="{{ route('my-profile.update', $userAndUserDetails->user_id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="rashi" class="form-label">Rashi <span class="text-danger"> *</span></label>
                                    <select name="rashi" id="rashi" class="form-select rounded-pill shadow-none @error('rashi') is-invalid @enderror">
                                        <option value="">Select Rashi</option>
                                        @foreach($db['rashies'] as $rashi)
                                            <option value="{{ $rashi->name }}" {{ $userAndUserDetails->rashi == $rashi->name ? 'selected' : '' }}>{{ $rashi->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('rashi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>


                                <div class="col-md-6">
                                    <label for="nakshatra" class="form-label">Nakshatra <span class="text-danger">*</span></label>
                                    <select name="nakshatra" id="nakshatra" class="form-select rounded-pill shadow-none @error('nakshatra') is-invalid @enderror">
                                        <option value="">Select Nakshatra</option>
                                        @foreach($db['nakshatras'] as $star)
                                            <option value="{{ $star->name }}" {{ ($userAndUserDetails->nakshatra ?? '') == $star->name ? 'selected' : '' }}>{{ $star->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('nakshatra')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="gothram" class="form-label">Gothram</label>
                                    <select name="gothram" id="gothram" class="form-select rounded-pill shadow-none">
                                        <option value="">Select Gothram</option>
                                        @foreach($db['gothrams'] as $gothram)
                                            <option value="{{ $gothram->name }}" {{ $userAndUserDetails->gothram == $gothram->name ? 'selected' : '' }}>{{ $gothram->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="dosham" class="form-label">Dosha <span class="text-danger"> *</span></label>
                                    <select name="dosham" id="dosham" class="form-select rounded-pill shadow-none @error('dosham') is-invalid @enderror">
                                        <option value="">Select Dosha</option>
                                        @foreach($db['dosham'] as $dosham)
                                            <option value="{{ $dosham->name }}" {{ $userAndUserDetails->dosham == $dosham->name ? 'selected' : '' }}>{{ $dosham->name }}</option>
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

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn button2 px-5 py-2">Update</button>
                                </div>
                            </div>
                        </form>


                        <hr>
                        {{--Address Info--}}
                        <h5 class="fw-semibold mb-4">Address Details</h5>
                        <form method="POST" action="{{ route('my-profile.update', $userAndUserDetails->user_id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="country" class="form-label">Country <span class="text-danger"> *</span></label>
                                    <select name="country" id="country" class="form-select rounded-pill shadow-none @error('country') is-invalid @enderror">
                                        <option value="">Select Country</option>
                                        @foreach($locationData['countries'] as $country)
                                            <option value="{{ $country->name }}" {{ $userAndUserDetails->country == $country->name ? 'selected' : '' }}>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="state" class="form-label">State <span class="text-danger"> *</span></label>
                                    <select name="state" id="state" class="form-select rounded-pill shadow-none @error('state') is-invalid @enderror">
                                        <option value="">Select State</option>
                                        @foreach($locationData['states'] as $state)
                                            <option value="{{ $state->name }}" {{ $userAndUserDetails->state == $state->name ? 'selected' : '' }}>{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="city" class="form-label">City <span class="text-danger"> *</span></label>
                                    <select name="city" id="city" class="form-select rounded-pill shadow-none @error('city') is-invalid @enderror">
                                        <option value="">Select City</option>
                                        @foreach($locationData['cities'] as $city)
                                            <option value="{{ $city->name }}" {{ $userAndUserDetails->city == $city->name ? 'selected' : '' }}>{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="pin_code" class="form-label">Pincode <span class="text-danger"> *</span></label>
                                    <input type="text" name="pin_code" id="pin_code" class="form-control rounded-pill shadow-none @error('pin_code') is-invalid @enderror" placeholder="Enter Pincode" value="{{ $userAndUserDetails->pin_code ?? '' }}" required>
                                    @error('pin_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-12">
                                    <label for="address" class="form-label">Address <span class="text-danger"> *</span></label>
                                    <textarea name="address" id="address" rows="3" class="form-control rounded-4 shadow-none @error('address') is-invalid @enderror" placeholder="Enter Address" required>{{ $userAndUserDetails->address ?? '' }}</textarea>
                                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn button2 px-5 py-2">Update</button>
                                </div>
                            </div>
                        </form>

                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Profile Completion -->
                <div class="card p-4 mb-4 shadow-sm rounded-4">
                    <h6 class="fw-semibold">About My Details</h6>
                    <p class="small text-muted mb-3">
                        Do you want to download your biodata? Download the Bala Matrimony Bureau app from the Google Play Store and click on Profile Download.
                    </p>

                    @php
                        $progressClass = 'bg-danger';
                        if ($profileCompletion >= 70) { $progressClass = 'bg-success'; }
                        elseif ($profileCompletion >= 50) { $progressClass = 'bg-warning'; }
                    @endphp
                    <div class="bg-light-primary p-3 rounded-3 mb-3">
                        <span class="fw-semibold">Profile Completion</span>
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">Complete your profile for the full experience.</span>
                            <span class="text-success">{{ $profileCompletion }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar {{ $progressClass }}" style="width: {{ $profileCompletion }}%;"></div>
                        </div>
                    </div>
                </div>

                {{-- Personal Info --}}
                <div class="card p-4 shadow-sm rounded-4 mb-4">
                    <h6 class="fw-semibold mb-3">Profile Details</h6>
                    <form method="POST" action="{{ route('my-profile.update', $userAndUserDetails->user_id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" id="name" name="name" class="form-control rounded-pill shadow-none" value="{{ $userAndUserDetails->name }}">
                        </div>
                        <div class="mb-3">
                            <label for="profile_for" class="form-label">Profile for</label>
                            <input type="text" id="profile_for" class="form-control rounded-pill shadow-none" value="{{ $userAndUserDetails->profile_for }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label small mb-1">gender</label>
                            <input id="gender" type="text" class="form-control rounded-pill shadow-none" value="{{ $userAndUserDetails->gender }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label small mb-1">Phone Number</label>
                            <input id="phone" type="tel" class="form-control rounded-pill shadow-none" value="{{ $userAndUserDetails->mobile }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label small mb-1">Email</label>
                            <input id="email" type="email" class="form-control rounded-pill shadow-none" value="{{ $userAndUserDetails->email }}" disabled>
                        </div>

                        <button type="submit" class="btn button2 w-100">Update</button>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="card p-4 shadow-sm rounded-4 mb-4">
                    <h6 class="fw-semibold mb-3">Change Password</h6>
                    <form method="POST" action="{{ route('my-profile.update', $userAndUserDetails->user_id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <input id="password" type="password" name="password" class="form-control rounded-pill shadow-none @error('password') is-invalid @enderror" placeholder="Change Password">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn button2 w-100">Change Password</button>
                    </form>
                </div>

                <div class="card p-4 shadow-sm rounded-4 mb-4">
                    <h6 class="fw-semibold mb-3">Horoscope Image</h6>
                    <img class="img-thumbnail"
                         src="{{ asset('Horoscope Image/' .$userAndUserDetails->horoscope_image) }}" alt=""
                    >
                </div>

                <!-- Deactivation -->
                <div class="card p-4 shadow-sm rounded-4 mb-4">
                    <h6 class="fw-semibold mb-3">Account Deactivation</h6>
                    <form method="POST" action="{{ route('my-profile.update', $userAndUserDetails->user_id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="deactivated">
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason</label>
                            <select name="reason" id="reason" class="form-select rounded-pill shadow-none @error('reason') is-invalid @enderror">
                                <option value="">Choose Reason</option>
                                <option value="marriage_fixed">Marriage fixed</option>
                                <option value="engaged">Engaged</option>
                                <option value="temporarily_deactivate">Temporarily deactivate</option>
                                <option value="others">Others</option>
                            </select>
                            @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3" id="otherReasonGroup" style="display: none">
                            <label for="other_reason" class="form-label">
                                Describe the reason <small class="text-muted">(optional)</small>
                            </label>
                            <textarea class="form-control shadow-none" name="other_reason" id="other_reason" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn button2 w-100">Deactivate</button>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <link href="{{ asset('asset/cropper/cropper.min.css') }}" rel="stylesheet">
    <script src="{{ asset('asset/cropper/cropper.js') }}"></script>
    <script src="{{ asset('asset/cropper/image-cropper.js') }}"></script>
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

        $('#reason').on('change', function () {
            if ($(this).val() === 'others') {
                $('#otherReasonGroup').show();
            } else {
                $('#otherReasonGroup').hide();
            }
        });

        window.addEventListener('DOMContentLoaded', function () {
            new ImageCropper({
                inputId: 'profileImageInput',
                previewId: 'previewImage',
                croppedInputId: 'croppedImageInput',
                cropButtonId: 'cropButton',
                width: 300,
                height: 300,
                aspectRatio: 1
            });

            new ImageCropper({
                inputId: 'horoscopeImageInput',
                previewId: 'horoscopePreviewImage',
                croppedInputId: 'horoscopeCroppedImageInput',
                cropButtonId: 'horoscopeCropButton',
                aspectRatio: 16/9
            });
        });
    </script>
    @include('web.includes.footer')
@endsection
