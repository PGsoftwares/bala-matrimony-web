@extends('web.layouts.layout')
@section('title', $metaTags->title ?? '')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <section class="container">
        <div class="row">

            <div class="col-md-8 mt-4 mb-5">

                <div class="card border-0 shadow-sm p-3 rounded-4">
                    <h5 class="fw-bold">Edit Privacy Settings</h5>
                    <p class="small text-muted mb-3">Manage Who Can View Your Personal Details.</p>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('settings.update', $setting->user_id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium small">Name</label>
                                    <select name="name_visibility" class="form-select rounded-pill shadow-none">
                                        <option value="3" {{  $setting->name_visibility === 3 ? 'selected' : '' }}>Show to all users</option>
                                        <option value="2" {{  $setting->name_visibility === 2 ? 'selected' : '' }}>Show to premium users</option>
                                        <option value="1" {{  $setting->name_visibility === 1 ? 'selected' : '' }}>Show to interest accepted users</option>
                                        <option value="0" {{  $setting->name_visibility === 0 ? 'selected' : '' }}>Do not show all users</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium small">Mobile Number</label>
                                    <select name="mobile_number_visibility" class="form-select rounded-pill shadow-none">
                                        <option value="3" {{  $setting->mobile_number_visibility === 3 ? 'selected' : '' }}>Show to all users</option>
                                        <option value="2" {{  $setting->mobile_number_visibility === 2 ? 'selected' : '' }}>Show to premium users</option>
                                        <option value="1" {{  $setting->mobile_number_visibility === 1 ? 'selected' : '' }}>Show to interest accepted users</option>
                                        <option value="0" {{  $setting->mobile_number_visibility === 0 ? 'selected' : '' }}>Do not show all users</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium small">Email</label>
                                    <select name="email_visibility" class="form-select rounded-pill shadow-none">
                                        <option value="3" {{  $setting->email_visibility === 3 ? 'selected' : '' }}>Show to all users</option>
                                        <option value="2" {{  $setting->email_visibility === 2 ? 'selected' : '' }}>Show to premium users</option>
                                        <option value="1" {{  $setting->email_visibility === 1 ? 'selected' : '' }}>Show to interest accepted users</option>
                                        <option value="0" {{  $setting->email_visibility === 0 ? 'selected' : '' }}>Do not show all users</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium small">Date of Birth</label>
                                    <select name="date_of_birth_visibility" class="form-select rounded-pill shadow-none">
                                        <option value="3" {{  $setting->date_of_birth_visibility === 3 ? 'selected' : '' }}>Show to all users</option>
                                        <option value="2" {{  $setting->date_of_birth_visibility === 2 ? 'selected' : '' }}>Show to premium users</option>
                                        <option value="1" {{  $setting->date_of_birth_visibility === 1 ? 'selected' : '' }}>Show to interest accepted users</option>
                                        <option value="0" {{  $setting->date_of_birth_visibility === 0 ? 'selected' : '' }}>Do not show all users</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium small">Profile Picture</label>
                                    <select name="profile_picture_visibility" class="form-select rounded-pill shadow-none">
                                        <option value="3" {{  $setting->profile_picture_visibility === 3 ? 'selected' : '' }}>Show to all users</option>
                                        <option value="2" {{  $setting->profile_picture_visibility === 2 ? 'selected' : '' }}>Show to premium users</option>
                                        <option value="1" {{  $setting->profile_picture_visibility === 1 ? 'selected' : '' }}>Show to interest accepted users</option>
                                        <option value="0" {{  $setting->profile_picture_visibility === 0 ? 'selected' : '' }}>Do not show all users</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-medium small">Horoscope Picture</label>
                                    <select name="horoscope_picture_visibility" class="form-select rounded-pill shadow-none">
                                        <option value="3" {{  $setting->horoscope_picture_visibility === 3 ? 'selected' : '' }}>Show to all users</option>
                                        <option value="2" {{  $setting->horoscope_picture_visibility === 2 ? 'selected' : '' }}>Show to premium users</option>
                                        <option value="1" {{  $setting->horoscope_picture_visibility === 1 ? 'selected' : '' }}>Show to interest accepted users</option>
                                        <option value="0" {{  $setting->horoscope_picture_visibility === 0 ? 'selected' : '' }}>Do not show all users</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        {{--Actions--}}
                        <div class="row g-2 d-flex justify-content-end">
                            <div class="col-md-4">
                                <button type="submit" class="btn button2 w-100 px-4 py-2">Set Privacy</button>
                            </div>
                        </div>

                    </form>
                </div>

            </div>

            @include('web.includes.right-aside')
        </div>
    </section>

    @include('web.includes.footer')
@endsection
