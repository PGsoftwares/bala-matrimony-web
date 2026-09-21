@extends('web.layouts.layout')
@section('title', $metaTags->title ?? '')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <div class="container-fluid py-4">
        <div class="row g-4">

            <div class="col-md-4">

                <div class="card shadow-sm border-0 rounded-4">
                    <div class="position-relative">
                        <img src="{{ $profile->profile_image }}" class="card-img-top " alt="Profile">

                        {{--Add Wishlist--}}
                        <form action="{{ route('addWishlist') }}" method="POST" class="position-absolute top-0 end-0 m-2">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                            <input type="hidden" name="profile_id" value="{{ $profile->user_id }}">
                            <button type="submit" class="btn btn-light rounded-circle shadow-sm">
                                <i class="bi bi-heart text-danger fw-bold"></i>
                            </button>
                        </form>

                        <div class="position-absolute bottom-0 start-0 text-white w-100 p-3"
                             style="background: linear-gradient(to top, rgba(0,0,0,0.6), rgba(0,0,0,0));">
                            <h5 class="mb-2">{{ $profile->name ?? '' }}</h5>
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 small">
                                <span><i class="bi bi-patch-check-fill gradient-text"></i> ID Verified</span>
                                <span><i class="bi bi-star-fill gradient-text"></i> Premium Member</span>
                                <span class="badge bg-light text-dark rounded-pill p-2">ID : BMB{{ $profile->user_id }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body text-center bg-light-primary rounded-bottom-2 mb-2">
                        <div class="d-flex justify-content-around text-center my-3">
                            <div><small>Age</small><br><strong class="primary_color">{{ $profile->age ?? '' }}</strong></div>
                            <div><small>Employed In</small><br><strong class="primary_color">{{ $profile->employed_in ?? '' }}</strong></div>
                            <div><small>City</small><br><strong class="primary_color">{{ $profile->city ?? '' }}</strong></div>
                        </div>
                    </div>

                    {{--Chat & View Contact--}}
                    <div class="d-flex gap-2 mb-2">
                        <a href="{{ url('chats?chat_user_id=' . $profile->user_id) }}"
                           class="btn button2 w-100 py-3">
                            <i class="bi bi-chat-square-text me-2"></i> Send Message
                        </a>
                        <form id="contact-form-{{ $profile->user_id }}" method="POST" action="{{ route('all-profiles.contact') }}" class="w-100">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                            <input type="hidden" name="profile_id" value="{{ $profile->user_id }}">

                            <button type="submit" class="btn button2 w-100 py-3 d-flex align-items-center justify-content-center">
                                <i class="bi bi-person-lines-fill me-2"></i> View Contact
                            </button>
                        </form>
                    </div>

                    {{--Interest--}}
                    <form action="{{ route('web.sendInterest') }}" method="POST">
                        @csrf
                        <input type="hidden" name="profile_id" value="{{ $profile->user_id }}">
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                        <button type="submit" class="btn button1 w-100 py-3 mb-2 d-flex align-items-center justify-content-center">
                            <i class="bi bi-hearts me-2"></i> Express Interest
                        </button>
                    </form>

                    {{--If only made contact--}}
                    @if($contactRequested)
                    <div class="card-body bg-light-primary rounded-2">
                        <h5>Contact Info</h5>
                        <span class="text-muted"><i class="bi bi-telephone-fill gradient-text me-2"></i> {{ $profile->mobile ?? '' }}</span><br>
                        <span class="text-muted"><i class="bi bi-envelope-fill gradient-text me-2"></i> {{ $profile->email ?? '' }}</span>
                    </div>
                    @endif
                </div>

            </div>


            <div class="col-md-8">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info">{{ session('info') }}</div>
                @endif
                <div class="overflow-auto mb-3">
                    <!-- Tabs -->
                    <ul class="nav nav-pills flex-nowrap text-nowrap gap-2 bg-light-primary p-2 rounded shadow-sm" id="modernTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link tab-btn-style active" id="basic-tab" data-bs-toggle="pill" data-bs-target="#basic" type="button" role="tab"><i class="bi bi-person-fill me-2"></i>Basic Details</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link tab-btn-style" id="family-tab" data-bs-toggle="pill" data-bs-target="#family" type="button" role="tab"><i class="bi bi-people-fill me-2"></i>Family Details</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link tab-btn-style" id="education-tab" data-bs-toggle="pill" data-bs-target="#education" type="button" role="tab"><i class="bi bi-mortarboard me-2"></i>Education Details</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link tab-btn-style" id="horoscope-tab" data-bs-toggle="pill" data-bs-target="#horoscope" type="button" role="tab"><i class="bi bi-stars me-2"></i>Horoscope Details</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link tab-btn-style" id="address-tab" data-bs-toggle="pill" data-bs-target="#address" type="button" role="tab"><i class="bi bi-house me-2"></i>Address Details</button>
                        </li>
                    </ul>
                </div>

                <!-- Tab Content -->
                <div class="tab-content p-3 bg-white border rounded shadow-sm" id="modernTabContent">

                    <div class="tab-pane fade show active" id="basic" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 border-end">
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Profile For</div>
                                    <div class="col-6">{{ $profile->profile_for ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Name</div>
                                    <div class="col-6">{{ $profile->name ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Gender</div>
                                    <div class="col-6">{{ $profile->gender ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Date of Birth</div>
                                    <div class="col-6">{{ $profile->date_of_birth ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Birth Time</div>
                                    <div class="col-6">{{ \Carbon\Carbon::parse($profile->birth_time)->format('h:i A') ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Birth Country</div>
                                    <div class="col-6">{{ $profile->birth_country ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Birth State</div>
                                    <div class="col-6">{{ $profile->birth_state ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Birth City</div>
                                    <div class="col-6">{{ $profile->birth_city ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Marital Status</div>
                                    <div class="col-6">{{ $profile->marital_status ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Religion</div>
                                    <div class="col-6">{{ $profile->religion ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Caste</div>
                                    <div class="col-6">{{ $profile->caste ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Sub Caste</div>
                                    <div class="col-6">{{ $profile->sub_caste ?? '' }}</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Mother Tongue</div>
                                    <div class="col-6">{{ $profile->mother_tongue ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Height</div>
                                    <div class="col-6">{{ $profile->height ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Skin Tone</div>
                                    <div class="col-6">{{ $profile->skin_tone ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Body Type</div>
                                    <div class="col-6">{{ $profile->body_type ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Physical Status</div>
                                    <div class="col-6">{{ $profile->physical_status ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Eating Habit</div>
                                    <div class="col-6">{{ $profile->eating_habit ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Drinking Habit</div>
                                    <div class="col-6">{{ $profile->drinking_habit ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Smoking Habit</div>
                                    <div class="col-6">{{ $profile->smoking_habit ?? '' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="family" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 border-end">
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Father Name</div>
                                    <div class="col-6">{{ $profile->father_name ?? '-' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Father Status</div>
                                    <div class="col-6">{{ $profile->father_profession ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Mother Name</div>
                                    <div class="col-6">{{ $profile->mother_name ?? '-' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Mother Status</div>
                                    <div class="col-6">{{ $profile->mother_profession ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Family Type</div>
                                    <div class="col-6">{{ $profile->family_type ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Family Status</div>
                                    <div class="col-6">{{ $profile->family_status ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Family Values</div>
                                    <div class="col-6">{{ $profile->family_values ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Elder Brothers</div>
                                    <div class="col-6">{{ $profile->elder_brother ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Younger Brothers</div>
                                    <div class="col-6">{{ $profile->younger_brother ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Elder Married Brothers</div>
                                    <div class="col-6">{{ $profile->elder_married_brother ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Younger Married Brothers</div>
                                    <div class="col-6">{{ $profile->younger_married_brother ?? '' }}</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Elder Sisters</div>
                                    <div class="col-6">{{ $profile->elder_sister ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Younger Sisters</div>
                                    <div class="col-6">{{ $profile->younger_sister ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Elder Married Sisters</div>
                                    <div class="col-6">{{ $profile->elder_married_sister ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Younger Married Sisters</div>
                                    <div class="col-6">{{ $profile->younger_married_sister ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Property Details</div>
                                    <div class="col-6">{{ $profile->property_details ?? '' }}</div>
                                </div>
                                @if(!empty($profile->property_info))
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Property Info</div>
                                    <div class="col-6">{{ $profile->property_info }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="education" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 border-end">
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Education</div>
                                    <div class="col-6">{{ $profile->education ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Occupation</div>
                                    <div class="col-6">{{ $profile->occupation ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Employed in</div>
                                    <div class="col-6">{{ $profile->employed_in ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Monthly Income</div>
                                    <div class="col-6">{{ $profile->monthly_income ?? '' }}</div>
                                </div>

                            </div>

                            <div class="col-md-6">
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Work Country</div>
                                    <div class="col-6">{{ $profile->work_country ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Visa Status</div>
                                    <div class="col-6">{{ $profile->visa_status ?? '' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="horoscope" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 border-end">
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Rashi</div>
                                    <div class="col-6">{{ $profile->rashi ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Nakshatra</div>
                                    <div class="col-6">{{ $profile->nakshatra ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Gothram</div>
                                    <div class="col-6">{{ $profile->gothram ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Dosha</div>
                                    <div class="col-6">{{ $profile->dosham ?? '' }}</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row mb-2">
                                    <div class="col-12 fw-medium mb-2">Horoscope Image</div>
                                    <div class="col-12">
                                        <img class="img-thumbnail" src="{{ $profile->horoscope_image }}" alt="" style="max-width: 100%;height: 200px">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="address" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 border-end">
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">City</div>
                                    <div class="col-6">{{ $profile->city ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">State</div>
                                    <div class="col-6">{{ $profile->state ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Country</div>
                                    <div class="col-6">{{ $profile->country ?? '' }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6 fw-medium">Pincode</div>
                                    <div class="col-6">{{ $profile->pin_code ?? '' }}</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row mb-2">
                                    <div class="col-4 fw-medium">Address</div>
                                    <div class="col-8">{{ $profile->address ?? '' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--Preference Matches--}}
                @php
                    $defaultImage = 'public/web/assets/default/default.png';
                    $userImage = !empty($userAndUserDetails->profile_image)
                        ? asset('Profile Image/' . $userAndUserDetails->profile_image)
                        : asset($defaultImage);
                    $matchedUserImage = !empty($profile->profile_image)
                        ? $profile->profile_image
                        : asset($defaultImage);
                @endphp

                <div class="mt-4 p-3 bg-white border rounded shadow-sm">
                    <h4>Partner Preferences</h4>
                    <div class="d-flex bg-light-primary justify-content-center align-items-center mb-3 p-2 rounded-4">
                        <img src="{{ $matchedUserImage }}" width="100" class="rounded-circle" alt="">
                        <span class="ms-3 me-3 fw-medium">You match {{ $matchedCount }}/{{ $totalPreferences }} of preferences</span>
                        <img src="{{ $userImage }}" width="100" class="rounded-circle" alt="">
                    </div>
                    <ul class="list-group rounded">
                        @forelse($matchedPreferences as $match)
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-medium text-capitalize">{{ str_replace('_', ' ', $match['preference_name']) }}</div>
                                <small class="text-muted ">{{ $match['value'] }}</small>
                            </div>

                            @if($match['match'])
                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            @else
                                <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                            @endif
                        </li>
                        @empty
                            <p class="text-muted text-center fst-italic">This user has not set any preferences.</p>
                        @endforelse

                    </ul>
                </div>

            </div>
        </div>
    </div>

    @include('web.includes.footer')
@endsection
