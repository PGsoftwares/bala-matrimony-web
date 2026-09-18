@extends('web.layouts.layout')
@section('title', $metaTags->title)
@section('description', $metaTags->description)
@section('keywords', $metaTags->keywords)

@section('content')
    @include('web.includes.header')

    <section class="container-fluid p-0">
        <div class="row g-0 min-vh-100">

            <!-- Left Full Image Column -->
            <div class="col-md-6 d-md-block d-none">
                <img src="{{ asset('asset/img/IMG01.png') }}" alt="Left Image" class="img-fluid w-100 h-100" style="object-fit: cover;">
            </div>

            <!-- Right Centered Form -->
            <div class="col-md-6 d-flex mb-md-0 mb-5 align-items-center justify-content-center p-4">
                <div class="card shadow rounded-4 w-100" style="max-width: 450px;">
                    <div class="card-body p-4">

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                        <h4 class="fw-bold text-center mb-2">Register</h4>
                        <p class="text-muted text-center mb-4">Get your free account now.</p>

                        <form method="post" action="{{ route('register') }}">
                            @csrf
                            <div class="mb-3">
                                <select class="form-select rounded-pill shadow-none @error('profile_for') is-invalid @enderror" name="profile_for">
                                    <option value="">Select Profile for</option>
                                    <option value="Self" {{ old('profile_for') == 'Self' ? 'selected' : '' }}>Self</option>
                                    <option value="Son" {{ old('profile_for') == 'Son' ? 'selected' : '' }}>Son</option>
                                    <option value="Daughter" {{ old('profile_for') == 'Daughter' ? 'selected' : '' }}>Daughter</option>
                                    <option value="Brother" {{ old('profile_for') == 'Brother' ? 'selected' : '' }}>Brother</option>
                                    <option value="Sister" {{ old('profile_for') == 'Sister' ? 'selected' : '' }}>Sister</option>
                                    <option value="Friend" {{ old('profile_for') == 'Friend' ? 'selected' : '' }}>Friend</option>
                                    <option value="Relative" {{ old('profile_for') == 'Relative' ? 'selected' : '' }}>Relative</option>
                                </select>
                                @error('profile_for')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <input type="text" name="name" class="form-control rounded-pill shadow-none @error('name') is-invalid @enderror" placeholder="Enter name" value="{{ old('name') }}">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <select class="form-select rounded-pill shadow-none @error('gender') is-invalid @enderror" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <input type="email" name="email" class="form-control rounded-pill shadow-none @error('email') is-invalid @enderror" placeholder="Enter email" value="{{ old('email') }}">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <input type="tel" id="phone" name="mobile" class="form-control rounded-pill shadow-none @error('mobile') is-invalid @enderror" placeholder="Enter mobile" value="{{ old('mobile') }}">
                                <input type="hidden" name="country_code" id="phone_country_code">
                                @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control rounded-pill shadow-none @error('password') is-invalid @enderror" placeholder="Password">
                                    @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    <span class="input-group-text rounded-pill shadow-none" onclick="togglePassword('password')" style="cursor: pointer; margin-left: -45px; z-index: 5;">👁️</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control rounded-pill shadow-none @error('password') is-invalid @enderror" placeholder="Confirm Password">
                                    @error('password_confirmation')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    <span class="input-group-text rounded-pill shadow-none" onclick="togglePassword('password_confirmation')" style="cursor: pointer; margin-left: -45px; z-index: 5;">👁️</span>
                                </div>
                            </div>

                            <!-- Terms & Condition -->
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="terms" onchange="toggleRegisterButton()">
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="{{ url('terms-and-condition') }}" target="_blank">Terms & conditions</a>
                                </label>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex gap-2 mb-3">
                                <button type="submit" id="registerBtn" class="btn button2 w-100 rounded-pill" disabled>Register</button>
                            </div>

                            <!--Links -->
                            <div class="d-flex justify-content-center small">
                                <span>Don't have an account? <a href="{{ route('login') }}" class="primary_color">Login</a></span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <script src="{{asset('asset/dialcode/dial-code.js')}}"></script>
    <script src="{{asset('asset/dialcode/intlTelInput.min.js')}}"></script>
    <script src="{{asset('asset/dialcode/utils.js')}}"></script>
    <script>
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            input.type = input.type === "password" ? "text" : "password";
        }

        function toggleRegisterButton() {
            const checkbox = document.getElementById('terms');
            const button = document.getElementById('registerBtn');
            button.disabled = !checkbox.checked;
        }
    </script>
    @include('web.includes.footer')
@endsection
