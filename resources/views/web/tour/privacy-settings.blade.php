@extends('web.layouts.layout')
@section('title', $metaTags->title ?? '')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <section id="page-content py-5">
        <div class="container">
            <div class="row justify-content-center">

                <div class="content col-md-9">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="card">
                        <div class="primary_bg shadow p-4 text-light text-left">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="h4">Edit Privacy Settings</span>
                                    <p class="text-muted m-0">Manage Who Can View Your Personal Details</p>
                                </div>
                                <div>
                                    <a href="{{ route('tour3') }}" class="btn secondary_button">SKIP</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('settings.update', $setting->user_id ) }}">
                                @csrf
                                @method('PUT')
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-control-label">Name</label>
                                            <select id="name" name="name_visibility">
                                                <option value="3" {{  $setting->name_visibility === 3 ? 'selected' : '' }}>Show to all users</option>
                                                <option value="2" {{  $setting->name_visibility === 2 ? 'selected' : '' }}>Show to premium users</option>
                                                <option value="1" {{  $setting->name_visibility === 1 ? 'selected' : '' }}>Show to interest accepted users</option>
                                                <option value="0" {{  $setting->name_visibility === 0 ? 'selected' : '' }}>Do not show all users</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="dob" class="form-control-label">Date of Birth</label>
                                            <select id="dob" name="date_of_birth_visibility">
                                                <option value="3" {{  $setting->date_of_birth_visibility === 3 ? 'selected' : '' }}>Show to all users</option>
                                                <option value="2" {{  $setting->date_of_birth_visibility === 2 ? 'selected' : '' }}>Show to premium users</option>
                                                <option value="1" {{  $setting->date_of_birth_visibility === 1 ? 'selected' : '' }}>Show to interest accepted users</option>
                                                <option value="0" {{  $setting->date_of_birth_visibility === 0 ? 'selected' : '' }}>Do not show all users</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ppv" class="form-control-label">Profile Picture</label>
                                            <select id="ppv" name="profile_picture_visibility">
                                                <option value="3" {{  $setting->profile_picture_visibility === 3 ? 'selected' : '' }}>Show to all users</option>
                                                <option value="2" {{  $setting->profile_picture_visibility === 2 ? 'selected' : '' }}>Show to premium users</option>
                                                <option value="1" {{  $setting->profile_picture_visibility === 1 ? 'selected' : '' }}>Show to interest accepted users</option>
                                                <option value="0" {{  $setting->profile_picture_visibility === 0 ? 'selected' : '' }}>Do not show all users</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="hpv" class="form-control-label">Horoscope Picture</label>
                                            <select id="hpv" name="horoscope_picture_visibility">
                                                <option value="3" {{  $setting->horoscope_picture_visibility === 3 ? 'selected' : '' }}>Show to all users</option>
                                                <option value="2" {{  $setting->horoscope_picture_visibility === 2 ? 'selected' : '' }}>Show to premium users</option>
                                                <option value="1" {{  $setting->horoscope_picture_visibility === 1 ? 'selected' : '' }}>Show to interest accepted users</option>
                                                <option value="0" {{  $setting->horoscope_picture_visibility === 0 ? 'selected' : '' }}>Do not show all users</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mnv" class="form-control-label">Mobile Number</label>
                                            <select id="mnv" name="mobile_number_visibility">
                                                <option value="3" {{  $setting->mobile_number_visibility === 3 ? 'selected' : '' }}>Show to all users</option>
                                                <option value="2" {{  $setting->mobile_number_visibility === 2 ? 'selected' : '' }}>Show to premium users</option>
                                                <option value="1" {{  $setting->mobile_number_visibility === 1 ? 'selected' : '' }}>Show to interest accepted users</option>
                                                <option value="0" {{  $setting->mobile_number_visibility === 0 ? 'selected' : '' }}>Do not show all users</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ev" class="form-control-label">Email</label>
                                            <select id="ev" name="email_visibility">
                                                <option value="3" {{  $setting->email_visibility === 3 ? 'selected' : '' }}>Show to all users</option>
                                                <option value="2" {{  $setting->email_visibility === 2 ? 'selected' : '' }}>Show to premium users</option>
                                                <option value="1" {{  $setting->email_visibility === 1 ? 'selected' : '' }}>Show to interest accepted users</option>
                                                <option value="0" {{  $setting->email_visibility === 0 ? 'selected' : '' }}>Do not show all users</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 d-flex justify-content-end">
                                    <button type="submit" class="btn primary_button">Save</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    @include('web.includes.footer')
@endsection


