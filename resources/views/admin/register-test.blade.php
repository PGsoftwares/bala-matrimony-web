@extends('admin.layouts.layout')

@section('content')
    @include('admin.includes.header')
    <div id="layout-wrapper">
        <div class="main-content">
            <div class="page-content">
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
                                            <a class="nav-link active" data-bs-toggle="tab" href="#personal_details" role="tab">
                                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                                <span class="d-none d-sm-block">Profile Details</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#education_job" role="tab">
                                                <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                                <span class="d-none d-sm-block">Education & Job</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#family_details" role="tab">
                                                <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                                <span class="d-none d-sm-block">Family Details</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#horoscope_details" role="tab">
                                                <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                                <span class="d-none d-sm-block">Horoscope Details</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#address_details" role="tab">
                                                <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                                <span class="d-none d-sm-block">Address Details</span>
                                            </a>
                                        </li>
                                    </ul>

                                    {{-- Tab Pane start --}}
                                    <div class="tab-content p-3 text-muted">
                                        <form method="POST" action="{{ route('register-details.store') }}"  enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="user_id" value="16">

                                        {{-- Personal Details  start --}}
                                        <div class="tab-pane active" id="personal_details" role="tabpanel">
                                            <div class="row">
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Profile for</label>
                                                    <select class="form-select" name="profile_for">
                                                        <option>Select Profile for</option>
                                                        <option value="Myself">Myself</option>
                                                        <option value="Son">Son</option>
                                                        <option value="Daughter">Daughter</option>
                                                        <option value="Brother">Brother</option>
                                                        <option value="Sister">Sister</option>
                                                        <option value="Guardian">Guardian</option>
                                                        <option value="Friend">Friend</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Gender</label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="gender" value="Male">
                                                        <label class="form-check-label">Male</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="gender" value="Female">
                                                        <label class="form-check-label">Female</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Date of Birth</label>
                                                    <input type="date" class="form-control" name="dob">
                                                </div>
                                                @if($isLanguageTableEnabled)
                                                    <div class="col-lg-4 mb-3">
                                                        <label class="form-label">Mother Tongue</label>
                                                        <select class="form-select" name="mother_tongue">
                                                            <option>Select Mother Tongue</option>
                                                            @foreach($languages as $language)
                                                                <option value="{{ $language->language }}">{{ $language->language }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                                @if($isMaritalStatusTableEnabled)
                                                    <div class="col-lg-4 mb-3">
                                                        <label class="form-label">Marital Status</label>
                                                        <select class="form-select" name="marital_status">
                                                            <option>Select Marital Status</option>
                                                            @foreach($maritalStatuses as $maritalStatus)
                                                                <option value="{{ $maritalStatus->name }}">{{ $maritalStatus->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                                @if($isSkinToneTableEnabled)
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Skin Tone</label>
                                                    @foreach($skinTones as $skinTone)
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="skin_tone" value="{{ $skinTone->name }}" id="{{ $skinTone->name }}">
                                                            <label class="form-check-label" for="{{ $skinTone->name }}">{{ $skinTone->name }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @endif
                                                @if($isHeightTableEnabled)
                                                    <div class="col-lg-4 mb-3">
                                                        <label class="form-label">Height</label>
                                                        <select class="form-select" name="height">
                                                            <option>Select Height</option>
                                                            @foreach($heights as $height)
                                                                <option value="{{ $height->name }}">{{ $height->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                                @if($isBodyTypeTableEnabled)
                                                    <div class="col-lg-4 mb-3">
                                                        <label class="form-label">Body Type</label>
                                                        @foreach($bodyTypes as $bodyType)
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="body_type" value="{{ $bodyType->name }}" id="{{ $bodyType->name }}">
                                                                <label class="form-check-label" for="{{ $bodyType->name }}">{{ $bodyType->name }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Physical Status</label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="physical_status" value="Normal">
                                                        <label class="form-check-label">Normal</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="physical_status" value="Physically Challenged">
                                                        <label class="form-check-label">Physically Challenged</label>
                                                    </div>
                                                </div>
                                                @if($isEatingHabitTableEnabled)
                                                    <div class="col-lg-4 mb-3">
                                                        <label class="form-label">Eating Habit</label>
                                                        @foreach($eatingHabits as $eatingHabit)
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="eating_habit" value="{{ $eatingHabit->name }}" id="{{ $eatingHabit->name }}">
                                                                <label class="form-check-label" for="{{ $eatingHabit->name }}">{{ $eatingHabit->name }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                @if($isDrinkingHabitTableEnabled)
                                                    <div class="col-lg-4 mb-3">
                                                        <label class="form-label">Drinking Habit</label>
                                                        @foreach($drinkingHabits as $drinkingHabit)
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="drinking_habit" value="{{ $drinkingHabit->name }}" id="{{ $drinkingHabit->name }}">
                                                                <label class="form-check-label" for="{{ $drinkingHabit->name }}">{{ $drinkingHabit->name }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                @if($isSmokingHabitTableEnabled)
                                                    <div class="col-lg-4 mb-3">
                                                        <label class="form-label">Smoking Habit</label>
                                                        @foreach($smokingHabits as $smokingHabit)
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="smoking_habit" value="{{ $smokingHabit->name }}" id="{{ $smokingHabit->name }}">
                                                                <label class="form-check-label" for="{{ $smokingHabit->name }}">{{ $smokingHabit->name }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                @if($isReligionTableEnabled)
                                                    <div class="col-lg-4 mb-3">
                                                        <label class="form-label">Religion</label>
                                                        <select class="form-select" name="religion">
                                                            <option>Select Religion</option>
                                                            @foreach($religions as $religion)
                                                                <option value="{{ $religion->name }}">{{ $religion->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                                @if($isCasteTableEnabled)
                                                    <div class="col-lg-4 mb-3">
                                                        <label class="form-label">Caste</label>
                                                        <select class="form-select" id="caste-admin" name="caste">
                                                            <option>Select Caste</option>
                                                            @foreach($castes as $caste)
                                                                <option value="{{ $caste->name }}">{{ $caste->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                                @if($isSubCasteTableEnabled)
                                                    <div class="col-lg-4 mb-3">
                                                        <label class="form-label">Sub caste</label>
                                                        <select class="form-select" id="sub-caste-admin" name="sub_caste">
                                                            <option>Select Sub caste</option>
                                                            <option value=""></option>
                                                        </select>
                                                    </div>
                                                @endif

                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">About Me</label>
                                                    <textarea class="form-control" name="about_me"></textarea>
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Profile Image</label>
                                                    <input type="file" class="form-control" name="profile_image">
                                                </div>

                                            </div>
                                            <div class="text-end">
                                                <button type="button" class="btn btn-primary" >Submit</button>
                                            </div>
                                        </div>

                                        {{-- Education & Job Details  start --}}
                                        <div class="tab-pane" id="education_job" role="tabpanel">
                                            <div class="row">

                                                @if($isEducationLevelTableEnabled)
                                                    <div class="col-lg-4 mb-3">
                                                        <label class="form-label">Qualification</label>
                                                        <select class="form-select" id="education-level-admin" name="qualification">
                                                            <option>Select Qualification</option>
                                                            @foreach($educationLevels as $educationLevel)
                                                                <option value="{{ $educationLevel->name }}">{{ $educationLevel->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                                @if($isEducationTableEnabled)
                                                    <div class="col-lg-4 mb-3">
                                                        <label class="form-label">Education</label>
                                                        <select class="form-select" id="study-admin" name="education">
                                                            <option>Select Education</option>
                                                            <option value=""></option>
                                                        </select>
                                                    </div>
                                                @endif
                                                @if($isEmployedInTableEnabled)
                                                    <div class="col-lg-4 mb-3">
                                                        <label class="form-label">Employed In</label>
                                                        <select class="form-select" name="employed_in">
                                                            <option>Select Employed In</option>
                                                            @foreach($employedIns as $employedIn)
                                                                <option value="{{ $employedIn->name }}">{{ $employedIn->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                                @if($isOccupationTypeTableEnabled)
                                                    <div class="col-lg-4 mb-3">
                                                        <label class="form-label">Occupation Type</label>
                                                        <select class="form-select" id="occupation-type-admin" name="occupation_type">
                                                            <option>Select Occupation Type</option>
                                                            @foreach($occupationTypes as $occupationType)
                                                                <option value="{{ $occupationType->name }}">{{ $occupationType->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif
                                                @if($isOccupationTableEnabled)
                                                    <div class="col-lg-4 mb-3">
                                                        <label class="form-label">Occupation</label>
                                                        <select class="form-select" id="occupation-admin" name="occupation">
                                                            <option>Select Occupation</option>
                                                            <option value=""></option>
                                                        </select>
                                                    </div>
                                                @endif
                                                @if($isSalaryTableEnabled)
                                                    <div class="col-lg-4 mb-3">
                                                        <label class="form-label">Monthly Income</label>
                                                        <select class="form-select" name="monthly_income">
                                                            <option>Select Monthly Income</option>
                                                            @foreach($salaries as $salary)
                                                                <option value="{{ $salary->name }}">{{ $salary->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif





                                            </div>
                                            <div class="text-end">
                                                <button type="button" class="btn btn-primary" >Submit</button>
                                            </div>
                                        </div>



                                        {{-- Family Details  start --}}
                                        <div class="tab-pane" id="family_details" role="tabpanel">
                                            <div class="row">

                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Father Name</label>
                                                    <input type="text" class="form-control" name="father_name">
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Father Profession</label>
                                                    <input type="text" class="form-control" name="father_profession">
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Mother Name</label>
                                                    <input type="text" class="form-control" name="mother_name">
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Mother Profession</label>
                                                    <input type="text" class="form-control" name="mother_profession">
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Family Type</label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="family_type" value="Joint">
                                                        <label class="form-check-label">Joint</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="family_type" value="Nuclear">
                                                        <label class="form-check-label">Nuclear</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Family Status</label>
                                                    <select class="form-select" name="family_status">
                                                        <option>Select Family Status</option>
                                                        <option value="Middle Class">Middle Class</option>
                                                        <option value="Upper Middle Class">Upper Middle Class</option>
                                                        <option value="Rich">Rich</option>
                                                        <option value="Affluent">Affluent</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Family Values</label>
                                                    <select class="form-select" name="family_values">
                                                        <option>Select Family Values</option>
                                                        <option value="Orthodox">Orthodox</option>
                                                        <option value="Traditional">Traditional</option>
                                                        <option value="Moderate">Moderate</option>
                                                        <option value="Liberal">Liberal</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Family God</label>
                                                    <input type="text" class="form-control" name="family_god">
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Elder Married Brothers</label>
                                                    <input type="text" class="form-control" name="elder_married_brother">
                                                </div>
                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Younger Married Brothers</label>
                                                    <input type="text" class="form-control" name="younger_married_brother">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Elder Married Sisters</label>
                                                    <input type="text" class="form-control" name="elder_married_sister">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Younger Married Sisters</label>
                                                    <input type="text" class="form-control" name="younger_married_sister">
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">Property Details</label>
                                                    <textarea class="form-control" name="property_details"></textarea>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">About Family</label>
                                                    <textarea class="form-control" name="about_family"></textarea>
                                                </div>


                                            </div>
                                            <div class="text-end">
                                                <button type="button" class="btn btn-primary" id="nextToHoroscope">Next</button>
                                            </div>
                                        </div>


                                        {{-- Horoscope Details  start --}}
                                        <div class="tab-pane" id="horoscope_details" role="tabpanel">
                                            <div class="row">


                                                @if($isRashiTableEnabled)
                                                    <div class="col-lg-4 mb-3">
                                                        <label class="form-label">Rashi</label>
                                                        <select class="form-select" name="rashi">
                                                            <option>Select Rashi</option>
                                                            @foreach($rashies as $rashi)
                                                                <option value="{{ $rashi->name }}">{{ $rashi->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif

                                                    @if($isStarTableEnabled)
                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Nakshatra <span class="text-danger">*</span></label>
                                                            <select class="form-select" name="nakshatra" required>
                                                                <option value="">Select Nakshatra</option>
                                                                @foreach($stars as $star)
                                                                    <option value="{{ $star->name }}">{{ $star->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif

                                                    @if($isLagnamTableEnabled)
                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Lagnam</label>
                                                            <select class="form-select" name="lagnam">
                                                                <option>Select Lagnam</option>
                                                                @foreach($lagnams as $lagnam)
                                                                    <option value="{{ $lagnam->name }}">{{ $lagnam->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif

                                                    @if($isPadamTableEnabled)
                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Padam</label>
                                                            <select class="form-select" name="padam">
                                                                <option>Select Padam</option>
                                                                @foreach($padams as $padam)
                                                                    <option value="{{ $padam->name }}">{{ $padam->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif

                                                    @if($isKulamTableEnabled)
                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Kulam</label>
                                                            <select class="form-select" name="kulam">
                                                                <option>Select Kulam</option>
                                                                @foreach($kulams as $kulam)
                                                                    <option value="{{ $kulam->name }}">{{ $kulam->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif

                                                    @if($isGothramTableEnabled)
                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Gothram</label>
                                                            <select class="form-select" name="gothram">
                                                                <option>Select Gothram</option>
                                                                @foreach($gothrams as $gothram)
                                                                    <option value="{{ $gothram->name }}">{{ $gothram->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif

                                                    @if($isDoshamTableEnabled)
                                                        <div class="col-lg-4 mb-3">
                                                            <label class="form-label">Dosham</label>
                                                            <select class="form-select" name="dosham">
                                                                <option>Select Dosham</option>
                                                                @foreach($doshams as $dosham)
                                                                    <option value="{{ $dosham->name }}">{{ $dosham->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif

                                                    <div class="col-lg-4 mb-3">
                                                        <label class="form-label">Horoscope Image</label>
                                                        <input type="file" class="form-control" name="horoscope_image">
                                                    </div>

                                            </div>
                                            <div class="text-end">
                                                <button type="button" class="btn btn-primary" id="nextToAddress">Next</button>
                                            </div>
                                        </div>


                                        {{-- Address Details  start --}}
                                        <div class="tab-pane" id="address_details" role="tabpanel">
                                            <div class="row">

                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Address</label>
                                                    <textarea class="form-control" name="address"></textarea>
                                                </div>

                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Country</label>
                                                    <select class="form-select" id="country-admin" name="country">
                                                        <option>Select Country</option>
                                                        @foreach($countries as $country)
                                                            <option value="{{ $country->name }}">{{ $country->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">State</label>
                                                    <select class="form-select" id="state-admin" name="state">
                                                        <option>Select State</option>
                                                        @foreach($states as $state)
                                                            <option value="{{ $state->name }}">{{ $state->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">City</label>
                                                    <select class="form-select" id="city-admin"  name="city">
                                                        <option>Select City</option>
                                                        @foreach($cities as $city)
                                                            <option value="{{ $city->name }}">{{ $city->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-lg-4 mb-3">
                                                    <label class="form-label">Pin code</label>
                                                    <input type="text" class="form-control" name="pin_code">
                                                </div>
                                            </div>

                                        </div>
                                        {{-- End of Address Details  start --}}


                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>

                                        </form>
                                    </div>
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
    @include('admin.includes.footer')
@endsection

