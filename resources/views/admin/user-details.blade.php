@extends('admin.layouts.layout')
@section('title', 'User Details')
@section('description', '')
@section('keywords', '')

@section('content')
    @include('admin.includes.header')
    @include('admin.includes.sidebar')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">{{ $user->name }} - ({{  $user->id  }})</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active">User Details</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-md-4">
                        <div class="card overflow-hidden">
                            <div class="bg-primary-subtle">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="text-primary p-3">
                                            <h5 class="text-primary">Welcome Back !</h5>
                                            <p>About our Details</p>
                                        </div>
                                    </div>
                                    <div class="col-5 align-self-end">
                                        <img src="{{asset('dashboard/admin/images/profile-img.png')}}" alt="" class="img-fluid">
                                    </div>
                                </div>
                            </div>

                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-sm-4">
                                        @php
                                            $defaultImage = asset('asset/img/default/default.png');
                                            if (isset($userAndUserDetails->gender)) {
                                                $defaultImage = $userAndUserDetails->gender === 'Male'
                                                    ? asset('asset/img/default/male.webp')
                                                    : ($userAndUserDetails->gender === 'Female'
                                                        ? asset('asset/img/default/female.webp')
                                                        : $defaultImage);
                                            }

                                            $imageSrc = $defaultImage;
                                            if (!empty($userAndUserDetails->profile_image) && file_exists(public_path('Profile Image/' . $userAndUserDetails->profile_image))) {
                                                $imageSrc = asset('Profile Image/' . $userAndUserDetails->profile_image);
                                            }
                                        @endphp

                                        <div class="avatar-lg profile-user-wid imageViewer" id="image-viewer">
                                            <img class="img-thumbnail"
                                                 src="{{ $imageSrc }}"
                                                 alt="{{ $profile->name ?? '' }}"
                                                 style="max-width: 100%;height: 110px; cursor: zoom-in"
                                            >
                                        </div>
                                    </div>

                                    
                                        <div class="pt-3">
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <p class="text-muted mb-0 font-size-12">Membership</p>
                                                    <h5 class="font-size-13 text-truncate mb-2">{{ !empty($userAndUserDetails->package) ? $userAndUserDetails->package : (!empty($receipt->package) ? $receipt->package : '-') }}</h5>
                                                </div>
                                                <div class="col-6">
                                                    <p class="text-muted mb-0 font-size-12">Status</p>
                                                    <h6 class="font-size-13 mb-2">
                                                        <span class="badge {{ ($userAndUserDetails->status ?? '') === 'active' ? 'bg-success' : (($userAndUserDetails->status ?? '') === 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                                            {{ ucfirst($userAndUserDetails->status ?? 'pending') }}
                                                        </span>
                                                    </h6>
                                                </div>
                                                <div class="col-6">
                                                    <p class="text-muted mb-0 font-size-12">Registration Date</p>
                                                    <h5 class="font-size-13 mb-0">{{ !empty($user->created_at) ? \Carbon\Carbon::parse($user->created_at)->format('d-m-Y') : (!empty($userAndUserDetails->created_at) ? \Carbon\Carbon::parse($userAndUserDetails->created_at)->format('d-m-Y') : '-') }}</h5>
                                                </div>
                                                <div class="col-6">
                                                    <p class="text-muted mb-0 font-size-12">Package Assigned Date</p>
                                                    <h5 class="font-size-13 mb-0">{{ !empty($receipt->recharge_date) ? \Carbon\Carbon::parse($receipt->recharge_date)->format('d-m-Y') : (!empty($receipt->created_at) ? \Carbon\Carbon::parse($receipt->created_at)->format('d-m-Y') : '-') }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    
                                </div>
                            </div>
                        </div>

                        {{--Account Information--}}
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Deactivate Account</h4>
                                <form action="{{ route('user-details.update', $userAndUserDetails->user_id) }}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="update_section" value="deactivate">
                                    <div class="">
                                        <button type="submit" name="deactivated" value="deactivated" class="btn btn-danger">Deactivate</button>
                                    </div>
                                </form>
                            </div>

                            <div class="card-body">
                                <h4 class="card-title mb-4">Activate Account</h4>
                                <form action="{{ route('user-details.update', $userAndUserDetails->user_id) }}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="update_section" value="activate">
                                    <div class="">
                                        <button type="submit" name="active" value="active" class="btn btn-primary">Activate</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{--Account Information--}}
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Account Information</h4>
                                <div class="row">
                                    <form action="{{ route('user-details.update', $userAndUserDetails->user_id) }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="update_section" value="account_info">
                                        <div class="mb-2">
                                            <label for="name" class="form-label p-0">Name</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ $userAndUserDetails->name }}">
                                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="mb-2">
                                            <label for="email" class="form-label p-0">Email</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ $userAndUserDetails->email }}">
                                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="mb-2">
                                            <label for="phone" class="form-label p-0">Mobile</label>
                                            <input type="tel" class="form-control @error('mobile') is-invalid @enderror" name="mobile" id="phone" value="{{ $userAndUserDetails->mobile }}">
                                            @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="change-password" class="form-label p-0">Change Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="change-password" placeholder="Enter new password">
                                                <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('change-password', this)">
                                                    <i class="mdi mdi-eye-outline"></i>
                                                </button>
                                                @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{--Package--}}
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Package Status</h4>
                                <div class="row p-0">
                                    <div class="col-md-6 col-5">
                                        <p><strong>Package</strong></p>
                                        <p><strong>Amount</strong></p>
                                        <p><strong>Month</strong></p>
                                        <p><strong>Total Contact</strong></p>
                                        <p><strong>Viewed</strong></p>
                                        <p><strong>Available</strong></p>
                                        <p><strong>Total Chats</strong></p>
                                        <p><strong>Chatting</strong></p>
                                        <p><strong>Available Chats</strong></p>
                                        <p><strong>Total Interests</strong></p>
                                        <p><strong>Send Interests</strong></p>
                                        <p><strong>Available Interests</strong></p>
                                        <p><strong>Upgraded Date</strong></p>
                                        <p><strong>Expiry Date</strong></p>
                                        <p><strong>Status</strong></p>
                                    </div>
                                    <div class="col-md-6 col-7">
                                        @if(isset($receipt) && $receipt)
                                            <p>: {{ $receipt->package ?? 'N/A' }}</p>
                                            <p>: ₹{{ $receipt->amount ?? '0' }}</p>
                                            <p>: {{ $receipt->month ?? '0' }} Months</p>
                                            <p>: {{ $receipt->no_of_contact ?? '0' }}</p>
                                            <p>: {{ $receipt->no_of_viewed ?? '0' }}</p>
                                            <p>: {{ $receipt->balance ?? '0' }}</p>
                                            <p>: {{ $receipt->no_of_chats ?? '0' }}</p>
                                            <p>: {{ $receipt->viewed_chats ?? '0' }}</p>
                                            <p>: {{ $receipt->balance_chats ?? '0' }}</p>
                                            <p>: {{ $receipt->no_of_interests ?? '0' }}</p>
                                            <p>: {{ $receipt->viewed_interests ?? '0' }}</p>
                                            <p>: {{ $receipt->balance_interests ?? '0' }}</p>
                                            <p>: {{ $receipt->recharge_date ? \Carbon\Carbon::parse($receipt->recharge_date)->format('d-m-Y h:i A') : 'N/A' }}</p>
                                            <p>: {{ !empty($receipt->expiry_date) ? \Carbon\Carbon::parse($receipt->expiry_date)->format('d-m-Y h:i A') : ((empty($receipt->month) || $receipt->month == 0) ? 'Without Expiry Date' : 'N/A') }}</p>
                                            <p>
                                                : <span class="badge {{ $receipt->status === 'paid' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ strtoupper($receipt->status ?? 'INACTIVE') }}
                                                </span>
                                            </p>
                                        @else
                                            <p>: No Active Package</p>
                                            <p>: -</p>
                                            <p>: -</p>
                                            <p>: -</p>
                                            <p>: -</p>
                                            <p>: -</p>
                                            <p>: -</p>
                                            <p>: -</p>
                                            <p>: -</p>
                                            <p>: -</p>
                                            <p>: -</p>
                                            <p>: -</p>
                                            <p>: -</p>
                                            <p>: -</p>
                                            <p>: <span class="badge bg-secondary">INACTIVE</span></p>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <form method="POST" action="{{ route('user-details.update', $userAndUserDetails->user_id) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="update_section" value="package">
                                        <input type="hidden" name="user_id" value="{{ $userAndUserDetails->user_id }}">
                                        <label for="package" class="form-label">Package</label>
                                        <select class="form-select" id="package" name="package">
                                            <option value="">Select Package</option>
                                            @foreach($db['packages'] as $package)
                                                <option value="{{ $package->name }}" {{ $userAndUserDetails->package == $package->name ? 'selected' : '' }}>{{ $package->name }}</option>
                                            @endforeach
                                        </select>

                                        <div class="text-end mt-4">
                                            <label for="update-package">
                                                <input id="update-package" type="submit" value="Update Package" class="btn btn-primary waves-effect waves-light">
                                            </label>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title">Our Info </h4>
                                        <p class="card-title-desc">About Our Details</p>
                                    </div>
                                    <div class="d-flex gap-2" style="height: fit-content;">
                                        <a class="btn btn-info text-white" target="_blank" href="{{ url('admin/print-user/' . $user->id) }}">
                                            <i class="bx bx-printer font-size-15 align-middle me-1"></i> Print / Download Profile
                                        </a>
                                        <a class="btn btn-primary" href="{{ url('admin/add-preference/' . $user->id) }}">
                                            <i class="bx bx-slider-alt font-size-15 align-middle me-1"></i> Add Preference
                                        </a>
                                    </div>
                                </div>

                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif

                                @if (session('image'))
                                    <div class="alert alert-success">{{ session('image') }}</div>
                                @endif

                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab" aria-selected="false" tabindex="-1">
                                            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                            <span class="d-none d-sm-block">Personal</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#educationDetails" role="tab" aria-selected="false" tabindex="-1">
                                            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                            <span class="d-none d-sm-block">Education & Job</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#familyDetails" role="tab" aria-selected="true">
                                            <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                            <span class="d-none d-sm-block">Family</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#horoscopeDetails" role="tab" aria-selected="false" tabindex="-1">
                                            <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                            <span class="d-none d-sm-block">Horoscope</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#addressDetails" role="tab" aria-selected="false" tabindex="-1">
                                            <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                            <span class="d-none d-sm-block">Address</span>
                                        </a>
                                    </li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content p-3 text-muted">

                                    <div class="tab-pane active" id="personalDetails" role="tabpanel">
                                        <form method="POST" action="{{ route('user-details.update', $userAndUserDetails->user_id) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="update_section" value="personal">
                                            <div class="row">
                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Profile for <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('profile_for') is-invalid @enderror" name="profile_for">
                                                        <option value="">Select Profile for</option>
                                                        <option value="Self" {{ $userAndUserDetails->profile_for == 'Self' ? 'selected' : '' }}>Self</option>
                                                        <option value="Son" {{ $userAndUserDetails->profile_for == 'Son' ? 'selected' : '' }}>Son</option>
                                                        <option value="Daughter" {{ $userAndUserDetails->profile_for == 'Daughter' ? 'selected' : '' }}>Daughter</option>
                                                        <option value="Brother" {{ $userAndUserDetails->profile_for == 'Brother' ? 'selected' : '' }}>Brother</option>
                                                        <option value="Sister" {{ $userAndUserDetails->profile_for == 'Sister' ? 'selected' : '' }}>Sister</option>
                                                        <option value="Friend" {{ $userAndUserDetails->profile_for == 'Friend' ? 'selected' : '' }}>Friend</option>
                                                    </select>
                                                    @error('profile_for')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('gender') is-invalid @enderror" name="gender">
                                                        <option value="">Select Gender</option>
                                                        <option value="Male" {{ $userAndUserDetails->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                                        <option value="Female" {{ $userAndUserDetails->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                                    </select>
                                                    @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control @error('dob') is-invalid @enderror" name="dob" value="{{ $userAndUserDetails->dob }}" required>
                                                    @error('dob')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Birth Time <span class="text-danger">*</span></label>
                                                    <input type="time" class="form-control @error('birth_time') is-invalid @enderror" name="birth_time" value="{{ !empty($userAndUserDetails->birth_time) ? date('H:i', strtotime($userAndUserDetails->birth_time)) : '' }}" required>
                                                    @error('birth_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label for="birth_country" class="form-label">Birth Country <span class="text-danger">*</span></label>
                                                    <select class="form-control @error('birth_country') is-invalid @enderror" id="birth_country" name="birth_country" required>
                                                        <option value="">Select Country</option>
                                                        @foreach($locationData['birth_countries'] as $country)
                                                            <option value="{{ $country->name }}" {{ $userAndUserDetails->birth_country == $country->name ? 'selected' : '' }}>{{ $country->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('birth_country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label for="birth_state" class="form-label">Birth State <span class="text-danger">*</span></label>
                                                    <select class="form-control @error('birth_state') is-invalid @enderror" id="birth_state" name="birth_state" required>
                                                        <option value="">Select State</option>
                                                        @foreach($locationData['birth_states'] as $state)
                                                            <option value="{{ $state->name }}" {{ $userAndUserDetails->birth_state == $state->name ? 'selected' : '' }}>{{ $state->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('birth_state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label for="birth_city" class="form-label">Birth City <span class="text-danger">*</span></label>
                                                    <select class="form-control @error('birth_city') is-invalid @enderror" id="birth_city" name="birth_city" required>
                                                        <option value="">Select City</option>
                                                        @foreach($locationData['birth_cities'] as $city)
                                                            <option value="{{ $city->name }}" {{ $userAndUserDetails->birth_city == $city->name ? 'selected' : '' }}>{{ $city->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('birth_city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Ethnicity</label>
                                                    <select class="form-select" name="ethnicity">
                                                        <option value="">Select Ethnicity</option>
                                                        @foreach($db['ethnicity'] as $ethnicity)
                                                            <option value="{{ $ethnicity->name }}" {{ $userAndUserDetails->ethnicity == $ethnicity->name ? 'selected' : '' }}>{{ $ethnicity->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Nationality</label>
                                                    <select class="form-select" name="nationality">
                                                        <option value="">Select Nationality</option>
                                                        @foreach($db['nationality'] as $nationality)
                                                            <option value="{{ $nationality->name }}" {{ $userAndUserDetails->nationality == $nationality->name ? 'selected' : '' }}>{{ $nationality->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Mother Tongue <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('mother_tongue') is-invalid @enderror" name="mother_tongue" required>
                                                        <option value="">Select Mother Tongue</option>
                                                        @foreach($db['languages'] as $language)
                                                            <option value="{{ $language->language }}" {{ $userAndUserDetails->mother_tongue == $language->language ? 'selected' : '' }}>{{ $language->language }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('mother_tongue')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Marital Status <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('marital_status') is-invalid @enderror" name="marital_status">
                                                        <option value="">Select Marital Status</option>
                                                        @foreach($db['maritalStatuses'] as $maritalStatus)
                                                            <option value="{{ $maritalStatus->name }}" {{ $userAndUserDetails->marital_status == $maritalStatus->name ? 'selected' : '' }}>{{ $maritalStatus->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('marital_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Religion <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('religion') is-invalid @enderror" name="religion">
                                                        <option value="">Select Religion</option>
                                                        @foreach($db['religions'] as $religion)
                                                            <option value="{{ $religion->name }}" {{ $userAndUserDetails->religion == $religion->name ? 'selected' : '' }}>{{ $religion->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('religion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label for="caste" class="form-label">Caste <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('caste') is-invalid @enderror" id="caste" name="caste">
                                                        <option value="">Select Caste</option>
                                                        @foreach($dropdownData['castes'] as $caste)
                                                            <option value="{{ $caste->name }}" {{ $userAndUserDetails->caste == $caste->name ? 'selected' : '' }}>
                                                                {{ $caste->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('caste')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label for="sub_caste" class="form-label">Sub Caste <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('sub_caste') is-invalid @enderror" id="sub_caste" name="sub_caste" required>
                                                        <option value="">Select Sub Caste</option>
                                                        @foreach($dropdownData['subCastes'] as $subCaste)
                                                            <option value="{{ $subCaste->name }}" {{ $userAndUserDetails->sub_caste == $subCaste->name ? 'selected' : '' }}>
                                                                {{ $subCaste->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('sub_caste')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Physical Status <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('physical_status') is-invalid @enderror" name="physical_status">
                                                        <option value="">Select Physical Status</option>
                                                        <option value="Normal" {{ $userAndUserDetails->physical_status == 'Normal' ? 'selected' : '' }}>Normal</option>
                                                        <option value="Physically Challenged" {{ $userAndUserDetails->physical_status == 'Physically Challenged' ? 'selected' : '' }}>Physically Challenged</option>
                                                    </select>
                                                    @error('physical_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Skin Tone <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('skin_tone') is-invalid @enderror" name="skin_tone" required>
                                                        <option value="">Select Skin Tone</option>
                                                        @foreach($db['skinTones'] as $skinTone)
                                                            <option value="{{ $skinTone->name }}" {{ $userAndUserDetails->skin_tone == $skinTone->name ? 'selected' : '' }}>
                                                                {{ $skinTone->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('skin_tone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Height <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('height') is-invalid @enderror" name="height" required>
                                                        <option value="">Select Height</option>
                                                        @foreach($db['heights'] as $height)
                                                            <option value="{{ $height->name }}" {{ $userAndUserDetails->height == $height->name ? 'selected' : '' }}>{{ $height->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('height')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Weight (in Kg) <span class="text-danger">*</span></label>
                                                    <input type="number" name="weight" id="weight" class="form-control @error('weight') is-invalid @enderror" placeholder="Enter weight in Kg" value="{{ $userAndUserDetails->weight }}" required>
                                                    @error('weight')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Body Type <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('body_type') is-invalid @enderror" name="body_type" required>
                                                        <option value="">Select Body Type</option>
                                                        @foreach($db['bodyTypes'] as $bodyType)
                                                            <option value="{{ $bodyType->name }}" {{ $userAndUserDetails->body_type == $bodyType->name ? 'selected' : '' }}>
                                                                {{ $bodyType->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('body_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Eating Habit <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('eating_habit') is-invalid @enderror" name="eating_habit" required>
                                                        <option value="">Select Eating Habit</option>
                                                        @foreach($db['eatingHabits'] as $eatingHabit)
                                                            <option value="{{ $eatingHabit->name }}" {{ $userAndUserDetails->eating_habit == $eatingHabit->name ? 'selected' : '' }}>{{ $eatingHabit->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('eating_habit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Drinking Habit <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('drinking_habit') is-invalid @enderror" name="drinking_habit" required>
                                                        <option value="">Select Drinking Habit</option>
                                                        @foreach($db['drinkingHabits'] as $drinkingHabit)
                                                            <option value="{{ $drinkingHabit->name }}" {{ $userAndUserDetails->drinking_habit == $drinkingHabit->name ? 'selected' : '' }}>{{ $drinkingHabit->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('drinking_habit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Smoking Habit <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('smoking_habit') is-invalid @enderror" name="smoking_habit" required>
                                                        <option value="">Select Smoking Habit</option>
                                                        @foreach($db['smokingHabits'] as $smokingHabit)
                                                            <option value="{{ $smokingHabit->name }}" {{ $userAndUserDetails->smoking_habit == $smokingHabit->name ? 'selected' : '' }}>{{ $smokingHabit->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('smoking_habit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Profile Image</label>
                                                    <input type="file" class="form-control" id="profileImageInput">
                                                    <input type="hidden" name="profile_image" id="croppedImageInput">
                                                </div>

                                                <div class="form-group col-lg-8">
                                                    <div class="image-container" style="max-width: 100%; margin-bottom: 10px;">
                                                        <img id="previewImage" style="max-width: 100%; display:none;" alt="" src="">
                                                    </div>
                                                    <button type="button" id="cropButton" class="btn btn-primary" style="display:none;">Crop Image</button>
                                                </div>

                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </div>
                                        </form>

                                        @if (!empty($userAndUserDetails->profile_image))
                                            <div class="mt-3 position-relative imageViewer" id="profileImageViewer">
                                                <img class="img-thumbnail" src="{{ asset('Profile Image/' . $userAndUserDetails->profile_image) }}" alt="" style="max-width: 100%; height: 200px; cursor: zoom-in">
                                                <form method="POST" action="{{ route('deleteImage') }}">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $userAndUserDetails->user_id }}">
                                                    <input type="hidden" name="type" value="profile">
                                                    <button type="submit" class="btn btn-danger btn-sm mt-2">Delete Image</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="tab-pane" id="educationDetails" role="tabpanel">
                                        <form method="POST" action="{{ route('user-details.update', $userAndUserDetails->user_id) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="update_section" value="education">
                                            <div class="row">

                                                {{--Education--}}
                                                <div class="col-lg-6 mb-3">
                                                    <label for="educations" class="form-label">Education <span class="text-danger">*</span></label>
                                                    @php
                                                        $selectedEducations = !empty($userAndUserDetails->education)
                                                            ? array_map('trim', explode(',', $userAndUserDetails->education))
                                                            : [];
                                                    @endphp
                                                    <select id="educations" name="education[]" class="form-select select2 @error('education') is-invalid @enderror" multiple>
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
                                                    @error('education')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Employed In <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('employed_in') is-invalid @enderror" name="employed_in" required>
                                                        <option value="">Select Employed In</option>
                                                        @foreach($db['employedIns'] as $employedIn)
                                                            <option value="{{ $employedIn->name }}" {{ $userAndUserDetails->employed_in == $employedIn->name ? 'selected' : '' }}>{{ $employedIn->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('employed_in')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                {{--Occupation--}}
                                                <div class="col-lg-6 mb-3">
                                                    <label for="occupations" class="form-label">Occupation <span class="text-danger">*</span></label>
                                                    <select id="occupations" name="occupation" class="form-select @error('occupation') is-invalid @enderror">
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

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Monthly Income <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('monthly_income') is-invalid @enderror" name="monthly_income" required>
                                                        <option value="">Select Monthly Income</option>
                                                        @foreach($db['salaries'] as $salary)
                                                            <option value="{{ $salary->name }}" {{ $userAndUserDetails->monthly_income == $salary->name ? 'selected' : '' }}>{{ $salary->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('monthly_income')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Work Country</label>
                                                    <select class="form-select" name="work_country">
                                                        <option value="">Select Work Country</option>
                                                        @foreach($locationData['work_countries'] as $country)
                                                            <option value="{{ $country->name }}" {{ $userAndUserDetails->work_country == $country->name ? 'selected' : '' }}>{{ $country->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Visa Status</label>
                                                    <select class="form-select" name="visa_status">
                                                        <option value="">Select Visa Status</option>
                                                        @foreach($db['visaStatus'] as $visaStatus)
                                                            <option value="{{ $visaStatus->name }}" {{ $userAndUserDetails->visa_status == $visaStatus->name ? 'selected' : '' }}>{{ $visaStatus->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane" id="familyDetails" role="tabpanel">
                                        <form method="POST" action="{{ route('user-details.update', $userAndUserDetails->user_id) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="update_section" value="family">
                                            <div class="row">

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Father Status</label>
                                                    <select class="form-select" name="father_profession">
                                                        <option value="">Select Father Status</option>
                                                        <option value="Employed" {{ strcasecmp($userAndUserDetails->father_profession ?? '', 'Employed') === 0 ? 'selected' : '' }}>Employed</option>
                                                        <option value="Not Working" {{ strcasecmp($userAndUserDetails->father_profession ?? '', 'Not Working') === 0 ? 'selected' : '' }}>Not Working</option>
                                                        <option value="Passed Away" {{ in_array(strtolower(trim($userAndUserDetails->father_profession ?? '')), ['passed away', 'passedaway']) ? 'selected' : '' }}>Passed Away</option>
                                                        @if(!empty($userAndUserDetails->father_profession) && !in_array(strtolower(trim($userAndUserDetails->father_profession)), ['employed', 'not working', 'passed away', 'passedaway']))
                                                            <option value="{{ $userAndUserDetails->father_profession }}" selected>{{ $userAndUserDetails->father_profession }}</option>
                                                        @endif
                                                    </select>
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Mother Status</label>
                                                    <select class="form-select" name="mother_profession">
                                                        <option value="">Select Mother Status</option>
                                                        <option value="Employed" {{ strcasecmp($userAndUserDetails->mother_profession ?? '', 'Employed') === 0 ? 'selected' : '' }}>Employed</option>
                                                        <option value="Not Working" {{ strcasecmp($userAndUserDetails->mother_profession ?? '', 'Not Working') === 0 ? 'selected' : '' }}>Not Working</option>
                                                        <option value="Passed Away" {{ in_array(strtolower(trim($userAndUserDetails->mother_profession ?? '')), ['passed away', 'passedaway']) ? 'selected' : '' }}>Passed Away</option>
                                                        @if(!empty($userAndUserDetails->mother_profession) && !in_array(strtolower(trim($userAndUserDetails->mother_profession)), ['employed', 'not working', 'passed away', 'passedaway']))
                                                            <option value="{{ $userAndUserDetails->mother_profession }}" selected>{{ $userAndUserDetails->mother_profession }}</option>
                                                        @endif
                                                    </select>
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Family Type</label>
                                                    <select class="form-select" name="family_type">
                                                        <option value="">Select Family Type</option>
                                                        <option value="Joint" {{ strcasecmp($userAndUserDetails->family_type ?? '', 'Joint') === 0 ? 'selected' : '' }}>Joint</option>
                                                        <option value="Nuclear" {{ strcasecmp($userAndUserDetails->family_type ?? '', 'Nuclear') === 0 ? 'selected' : '' }}>Nuclear</option>
                                                    </select>
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Family Status</label>
                                                    <select class="form-select" name="family_status">
                                                        <option value="">Select Family Status</option>
                                                        <option value="Middle Class" {{ strcasecmp($userAndUserDetails->family_status ?? '', 'Middle Class') === 0 ? 'selected' : '' }}>Middle Class</option>
                                                        <option value="Upper Middle Class" {{ strcasecmp($userAndUserDetails->family_status ?? '', 'Upper Middle Class') === 0 ? 'selected' : '' }}>Upper Middle Class</option>
                                                        <option value="Rich" {{ strcasecmp($userAndUserDetails->family_status ?? '', 'Rich') === 0 ? 'selected' : '' }}>Rich</option>
                                                        <option value="Affluent" {{ strcasecmp($userAndUserDetails->family_status ?? '', 'Affluent') === 0 ? 'selected' : '' }}>Affluent</option>
                                                    </select>
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Family Values</label>
                                                    <select class="form-select" name="family_values">
                                                        <option value="">Select Family Values</option>
                                                        <option value="Orthodox" {{ strcasecmp($userAndUserDetails->family_values ?? '', 'Orthodox') === 0 ? 'selected' : '' }}>Orthodox</option>
                                                        <option value="Traditional" {{ strcasecmp($userAndUserDetails->family_values ?? '', 'Traditional') === 0 ? 'selected' : '' }}>Traditional</option>
                                                        <option value="Moderate" {{ strcasecmp($userAndUserDetails->family_values ?? '', 'Moderate') === 0 ? 'selected' : '' }}>Moderate</option>
                                                        <option value="Liberal" {{ strcasecmp($userAndUserDetails->family_values ?? '', 'Liberal') === 0 ? 'selected' : '' }}>Liberal</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Elder Brothers</label>
                                                    <select class="form-select" name="elder_brother">
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

                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Younger Brothers</label>
                                                    <select class="form-select" name="younger_brother">
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

                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Elder Married Brothers</label>
                                                    <select class="form-select" name="elder_married_brother">
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

                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Younger Married Brothers</label>
                                                    <select class="form-select" name="younger_married_brother">
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

                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Elder Sisters</label>
                                                    <select class="form-select" name="elder_sister">
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

                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Younger Sisters</label>
                                                    <select class="form-select" name="younger_sister">
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

                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Elder Married Sisters</label>
                                                    <select class="form-select" name="elder_married_sister">
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

                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Younger Married Sisters</label>
                                                    <select class="form-select" name="younger_married_sister">
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

                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">Property Details <span class="small text-muted fw-normal">(Enter number of properties e.g. 1, 2, 3)</span></label>
                                                    @php
                                                        $selectedProperties = \App\Http\Controllers\Helpers\DataController::parsePropertyDetails($userAndUserDetails->property_details ?? '');
                                                    @endphp
                                                    <div class="row g-2">
                                                        @foreach($db['propertyDetails'] as $propertyDetail)
                                                            <div class="col-lg-3 col-md-4 col-sm-6">
                                                                <div class="border rounded p-2 bg-light h-100">
                                                                    <label for="admin_prop_{{ $loop->index }}" class="form-label small fw-semibold text-truncate d-block mb-1" title="{{ $propertyDetail->name }}">{{ $propertyDetail->name }}</label>
                                                                    <input type="number" min="0" step="1" name="property_details[{{ $propertyDetail->name }}]" id="admin_prop_{{ $loop->index }}"
                                                                           value="{{ old('property_details.' . $propertyDetail->name, $selectedProperties[$propertyDetail->name] ?? '') }}"
                                                                           class="form-control form-control-sm"
                                                                           placeholder="e.g. 1, 2">
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label">Additional Property Details</label>
                                                    <textarea name="property_info" rows="3" class="form-control" placeholder="Enter additional property details...">{{ old('property_info', $userAndUserDetails->property_info ?? '') }}</textarea>
                                                </div>

                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane" id="horoscopeDetails" role="tabpanel">
                                        <form method="POST" action="{{ route('user-details.update', $userAndUserDetails->user_id) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="update_section" value="horoscope">
                                            <div class="row">

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Rashi <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('rashi') is-invalid @enderror" name="rashi">
                                                        <option value="">Select Rashi</option>
                                                        @foreach($db['rashies'] as $rashi)
                                                            <option value="{{ $rashi->name }}" {{ $userAndUserDetails->rashi == $rashi->name ? 'selected' : '' }}>{{ $rashi->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('rashi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Nakshatra <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('nakshatra') is-invalid @enderror" name="nakshatra" required>
                                                        <option value="">Select Nakshatra</option>
                                                        @foreach($db['nakshatras'] as $star)
                                                            <option value="{{ $star->name }}" {{ ($userAndUserDetails->nakshatra ?? '') == $star->name ? 'selected' : '' }}>{{ $star->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('nakshatra')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Gothram</label>
                                                    <select class="form-select" name="gothram">
                                                        <option value="">Select Gothram</option>
                                                        @foreach($db['gothrams'] as $gothram)
                                                            <option value="{{ $gothram->name }}" {{ $userAndUserDetails->gothram == $gothram->name ? 'selected' : '' }}>{{ $gothram->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Dosha <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('dosham') is-invalid @enderror" name="dosham">
                                                        <option value="">Select Dosha</option>
                                                        @foreach($db['dosham'] as $dosham)
                                                            <option value="{{ $dosham->name }}" {{ $userAndUserDetails->dosham == $dosham->name ? 'selected' : '' }}>{{ $dosham->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('dosham')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Horoscope Image</label>
                                                    <input type="file" class="form-control"  id="horoscopeImageInput">
                                                    <input type="hidden" name="horoscope_image" id="horoscopeCroppedImageInput">
                                                </div>

                                                <div class="form-group">
                                                    <div class="image-container" style="max-width: 100%; margin-bottom: 10px;">
                                                        <img id="horoscopePreviewImage" style="max-width: 100%;  display:none;" alt="" src="">
                                                    </div>
                                                    <button type="button" id="horoscopeCropButton" class="btn btn-primary" style="display:none;">Crop Image</button>
                                                </div>

                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </div>
                                        </form>

                                        @if (!empty($userAndUserDetails->horoscope_image))
                                            <div class="mt-3 position-relative imageViewer" id="horoscopeViewer">
                                                <img class="img-thumbnail" src="{{ asset('Horoscope Image/' . $userAndUserDetails->horoscope_image) }}" alt="" style="max-width: 100%; height: 200px; cursor: zoom-in">
                                                <form method="POST" action="{{ route('deleteImage') }}">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $userAndUserDetails->user_id }}">
                                                    <input type="hidden" name="type" value="horoscope">
                                                    <button type="submit" class="btn btn-danger btn-sm mt-2">Delete Image</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="tab-pane" id="addressDetails" role="tabpanel">
                                        <form method="POST" action="{{ route('user-details.update', $userAndUserDetails->user_id) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="update_section" value="address">
                                            <div class="row">

                                                <div class="col-lg-6 mb-3">
                                                    <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('country') is-invalid @enderror" id="country" name="country" >
                                                        <option value="">Select Country</option>
                                                        @foreach($locationData['countries'] as $country)
                                                            <option value="{{ $country->name }}" {{ $userAndUserDetails->country == $country->name ? 'selected' : '' }}>{{ $country->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('state') is-invalid @enderror" id="state" name="state">
                                                        <option value="">Select State</option>
                                                        @foreach($locationData['states'] as $state)
                                                            <option value="{{ $state->name }}" {{ $userAndUserDetails->state == $state->name ? 'selected' : '' }}>{{ $state->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                                    <select class="form-select @error('city') is-invalid @enderror" id="city" name="city">
                                                        <option value="">Select City</option>
                                                        @foreach($locationData['cities'] as $city)
                                                            <option value="{{ $city->name }}" {{ $userAndUserDetails->city == $city->name ? 'selected' : '' }}>{{ $city->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label for="pin_code" class="form-label">Pincode <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('pin_code') is-invalid @enderror" id="pin_code" name="pin_code" placeholder="Enter Pincode" value="{{ $userAndUserDetails->pin_code ?? '' }}" required>
                                                    @error('pin_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="col-lg-12 mb-3">
                                                    <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="Enter Address" required>{{ $userAndUserDetails->address ?? '' }}</textarea>
                                                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>


                        {{--Notes--}}
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="card-title">Notes</h4>
                                    </div>
                                </div>

                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#notes" role="tab" aria-selected="false" tabindex="-1">
                                            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                            <span class="d-none d-sm-block">Notes</span>
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#addNew" role="tab" aria-selected="false" tabindex="-1">
                                            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                            <span class="d-none d-sm-block">Add New</span>
                                        </a>
                                    </li>

                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content p-3 text-muted">
                                    <div class="tab-pane active" id="notes" role="tabpanel">
                                        @forelse($notes as $note)
                                            <div class="card mb-3 shadow-sm border-success">
                                                <div class="card-body" style="border: 1px solid grey">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <h6 class="mb-0 text-success">
                                                            <i class="bx bx-note"></i> Note
                                                        </h6>
                                                        <small class="text-muted">
                                                            {{ \Carbon\Carbon::parse($note->created_at)->format('d M Y, h:i A') }}
                                                        </small>
                                                    </div>
                                                    <p class="mb-0">{{ $note->note }}</p>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center text-muted py-3">
                                                <i class="bx bx-info-circle"></i> <em>Notes not added</em>
                                            </div>
                                        @endforelse
                                    </div>

                                    <div class="tab-pane" id="addNew" role="tabpanel">
                                        <form method="POST" action="{{ route('user-details.update', $userAndUserDetails->user_id) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="update_section" value="note">
                                            <h5 class="card-title text-primary mb-3">
                                                <i class="bx bx-pencil"></i> Add a New Note
                                            </h5>

                                            <div class="form-group mb-4">
                                                <label for="note" class="form-label">Your Note</label>
                                                <textarea class="form-control" id="note" name="note" rows="5" placeholder="Write note..."></textarea>
                                            </div>

                                            <div class="text-end">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="bx bx-plus-circle"></i> Save Note
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>

    <link href="{{ asset('asset/css/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('asset/js/select2.min.js') }}"></script>
    <script src="{{ asset('asset/viewer/viewer.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/viewer/viewer.css') }}">
    <link href="{{ asset('asset/cropper/cropper.min.css') }}" rel="stylesheet">
    <script src="{{ asset('asset/cropper/cropper.js') }}"></script>
    <script src="{{ asset('asset/cropper/image-cropper.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                width: '100%',
                placeholder: 'Select Education'
            });
        });

        window.addEventListener('DOMContentLoaded', function () {

            document.querySelectorAll('.imageViewer').forEach(function (el) {
                new Viewer(el, {
                    inline: false,
                    navbar: false,
                    toolbar: true,
                    tooltip: true,
                    movable: true,
                    zoomable: true,
                    rotatable: false,
                    scalable: false,
                    transition: true,
                });
            });

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
                width: 300,
                height: 300,
                aspectRatio: 1
            });

        });
    </script>

    @include('admin.includes.footer')
@endsection
