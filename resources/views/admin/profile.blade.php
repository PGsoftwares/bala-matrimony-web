@extends('admin.layouts.layout')
@section('title', 'My Profile - Admin')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')

        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <!-- Page Title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18">My Profile</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                        <li class="breadcrumb-item active">My Profile</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alerts -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-check-all me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-block-helper me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Profile Card Overview -->
                        <div class="col-xl-4">
                            <div class="card overflow-hidden">
                                <div class="bg-primary bg-soft p-4" style="background: linear-gradient(135deg, #0d6b38 0%, #c8102e 100%) !important;">
                                    <div class="row">
                                        <div class="col-12 text-white">
                                            <h5 class="text-white mb-1">Welcome Back !</h5>
                                            <p class="mb-0 text-white-50">Administrator Profile</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="avatar-md profile-user-wid mb-4 text-center mx-auto" style="margin-top: -32px;">
                                                @php
                                                    $avatarUrl = !empty($user->photo) && file_exists(public_path('Profile Image/' . $user->photo))
                                                        ? asset('Profile Image/' . $user->photo)
                                                        : asset('asset/img/logo/fav-icon-pg.png');
                                                @endphp
                                                <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="img-thumbnail rounded-circle" style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.15);">
                                            </div>
                                            <div class="text-center">
                                                <h5 class="font-size-16 mb-1 text-truncate">{{ $user->name }}</h5>
                                                <p class="text-muted mb-2">
                                                    <span class="badge bg-success font-size-12">{{ ucfirst($user->role ?? 'Admin') }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive mt-3">
                                        <table class="table table-nowrap mb-0">
                                            <tbody>
                                                <tr>
                                                    <th scope="row"><i class="bx bx-envelope text-primary me-2"></i> Email :</th>
                                                    <td>{{ $user->email }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"><i class="bx bx-phone text-primary me-2"></i> Mobile :</th>
                                                    <td>{{ $user->country_code ? '+' . $user->country_code . ' ' : '' }}{{ $user->mobile }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"><i class="bx bx-shield-quarter text-primary me-2"></i> Role :</th>
                                                    <td>{{ ucfirst(str_replace('_', ' ', $user->role)) }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"><i class="bx bx-calendar text-primary me-2"></i> Joined :</th>
                                                    <td>{{ $user->created_at ? $user->created_at->format('d M, Y') : 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row"><i class="bx bx-time-five text-primary me-2"></i> Last Login :</th>
                                                    <td>{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Recently' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Update Details & Change Password -->
                        <div class="col-xl-8">
                            <!-- Update Profile Details -->
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4"><i class="bx bx-user-pin text-primary me-2"></i>Edit Profile Details</h4>

                                    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="admin-name">Full Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="admin-name" name="name" value="{{ old('name', $user->name) }}" required placeholder="Enter full name">
                                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="admin-email">Email Address <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="admin-email" name="email" value="{{ old('email', $user->email) }}" required placeholder="Enter email address">
                                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="col-md-3 mb-3">
                                                <label class="form-label" for="admin-country-code">Country Code</label>
                                                <input type="text" class="form-control @error('country_code') is-invalid @enderror" id="admin-country-code" name="country_code" value="{{ old('country_code', $user->country_code ?? '91') }}" placeholder="e.g. 91">
                                                @error('country_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="col-md-9 mb-3">
                                                <label class="form-label" for="admin-mobile">Mobile Number <span class="text-danger">*</span></label>
                                                <input type="tel" class="form-control @error('mobile') is-invalid @enderror" id="admin-mobile" name="mobile" value="{{ old('mobile', $user->mobile) }}" required placeholder="Enter mobile number">
                                                @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>

                                            <div class="col-md-12 mb-3">
                                                <label class="form-label" for="admin-photo">Profile Photo / Avatar</label>
                                                <input type="file" class="form-control @error('photo') is-invalid @enderror" id="admin-photo" name="photo" accept="image/*">
                                                <small class="text-muted">Allowed formats: JPG, JPEG, PNG, WEBP (Max 3MB)</small>
                                                @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>

                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary px-4" style="background-color: #0d6b38; border-color: #0d6b38;">
                                                <i class="bx bx-save me-1"></i> Save Changes
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Change Password -->
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4"><i class="bx bx-lock-alt text-danger me-2"></i>Change Password</h4>

                                    <form method="POST" action="{{ route('admin.profile.password') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label" for="current_password">Current Password <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required placeholder="Enter current password">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('current_password', this)">
                                                        <i class="mdi mdi-eye-outline"></i>
                                                    </button>
                                                    @error('current_password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="new_password">New Password <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="new_password" name="password" required placeholder="Minimum 8 characters">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('new_password', this)">
                                                        <i class="mdi mdi-eye-outline"></i>
                                                    </button>
                                                    @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="password_confirmation">Confirm New Password <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Re-enter new password">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('password_confirmation', this)">
                                                        <i class="mdi mdi-eye-outline"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-end">
                                            <button type="submit" class="btn btn-danger px-4" style="background-color: #c8102e; border-color: #c8102e;">
                                                <i class="bx bx-key me-1"></i> Update Password
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            @include('admin.includes.footer')
        </div>
    </div>
@endsection
