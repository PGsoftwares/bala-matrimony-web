@extends('web.layouts.layout')
@section('title', $metaTags->title ?? 'Verify OTP')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <style>
        .otp-input {
            width: 70px;
            height: 60px;
            font-size: 24px;
        }
    </style>

    <section class="container-fluid p-0">
        <div class="row g-0 min-vh-100">

            <!-- Centered Form -->
            <div class="col-md-6 d-flex mb-md-0 mb-5 align-items-center justify-content-center p-4">
                <div class="card shadow rounded-4 w-100" style="max-width: 450px;">
                    <div class="card-body p-4">

                        @if ($errors->has('otp'))
                            <div class="alert alert-danger">{{ $errors->first('otp') }}</div>
                        @endif

                        @if ($errors->has('success'))
                            <div class="alert alert-success">{{ $errors->first('success') }}</div>
                        @endif

                        <h4 class="fw-bold text-center mb-2">Verify OTP</h4>
                        <p class="text-muted text-center mb-4">Login your account</p>

                        <form method="post" action="{{ route('verifyEmailOTP') }}" id="otpForm">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">
                            <input type="hidden" name="email_otp" id="finalOtp">

                            <div class="d-flex gap-4 justify-content-center otp-container mb-3">
                                @for ($i = 0; $i < 4; $i++)
                                    <input type="text" class="otp-input form-control text-center shadow-none" maxlength="1" inputmode="numeric" required>
                                @endfor
                            </div>

                            <button type="submit" class="btn button2 w-100 rounded-pill">Submit</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Image Column -->
            <div class="col-md-6 d-md-block d-none">
                <img src="{{ asset('asset/img/IMG01.png') }}" alt="Left Image" class="img-fluid w-100 h-100" style="object-fit: cover;">
            </div>

        </div>
    </section>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const otpInputs = document.querySelectorAll('.otp-input');
            const finalOtpInput = document.getElementById('finalOtp');
            const form = document.getElementById('otpForm');

            otpInputs[0].focus();

            otpInputs.forEach((input, index) => {
                input.addEventListener('input', () => {
                    input.value = input.value.replace(/\D/g, '');
                    if (input.value && otpInputs[index + 1]) {
                        otpInputs[index + 1].focus();
                    }
                    updateOtp();
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && input.value === '' && otpInputs[index - 1]) {
                        otpInputs[index - 1].focus();
                    }
                });

                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 4);
                    paste.split('').forEach((digit, i) => {
                        if (otpInputs[i]) otpInputs[i].value = digit;
                    });
                    updateOtp();
                });

                input.addEventListener('focus', () => {
                    otpInputs.forEach(inp => inp.classList.remove('error'));
                });
            });

            function updateOtp() {
                const otp = Array.from(otpInputs).map(i => i.value).join('');
                finalOtpInput.value = otp;
            }

            form.addEventListener('submit', (e) => {
                updateOtp();
                if (finalOtpInput.value.length !== 4 || !/^\d{4}$/.test(finalOtpInput.value)) {
                    e.preventDefault();
                    otpInputs.forEach(input => input.classList.add('error'));
                }
            });
        });
    </script>

    @include('web.includes.footer')
@endsection
