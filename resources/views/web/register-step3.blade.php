@extends('web.layouts.layout')
@section('title', $metaTags->title ?? 'Habitual Details')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <section class="container-fluid">

        <div class="card border-0 p-4 mt-3 mb-5 shadow rounded-4 gradient-bg-primary">
            <div class="row">

                <div class="col-md-4 d-flex flex-column justify-content-between text-white" style="height: 500px">
                    <div>
                        <h5 class="fw-bold">Habitual Details</h5>
                        <p>Please fill with your habitual details</p>
                    </div>
                    <div class="d-flex justify-content-center text-center">
                        <img src="{{ asset('asset/img/registration.png') }}" alt="registration" class="img-fluid" style="max-height: 400px;">
                    </div>
                </div>

                <div class="col-md-8 d-flex flex-column" >
                    <div class="card shadow border-0 p-0 d-flex flex-column" >

                        <div class="p-3">
                            <h5 class="fw-semibold mb-0">Habitual Details</h5>
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                        </div>

                        <div class="form-body px-3 overflow-auto" style="flex: 1 1 auto;">
                            <form method="POST" action="{{ route('storeRegisterStep3') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user_id }}">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label for="eating_habit" class="form-label">Eating Habit <span class="text-danger">*</span></label>
                                        <select id="eating_habit" class="form-select rounded-pill shadow-none @error('eating_habit') is-invalid @enderror" name="eating_habit" required>
                                            <option value="">Select Eating Habit</option>
                                            @foreach($db['eatingHabits'] as $eatingHabit)
                                                <option value="{{ $eatingHabit->name }}" {{ old('eating_habit') == $eatingHabit->name ? 'selected' : '' }}>{{ $eatingHabit->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('eating_habit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="drinking_habit" class="form-label">Drinking Habit <span class="text-danger">*</span></label>
                                        <select id="drinking_habit" class="form-select rounded-pill shadow-none @error('drinking_habit') is-invalid @enderror" name="drinking_habit" required>
                                            <option value="">Select Drinking Habit</option>
                                            @foreach($db['drinkingHabits'] as $drinkingHabit)
                                                <option value="{{ $drinkingHabit->name }}" {{ old('drinking_habit') == $drinkingHabit->name ? 'selected' : '' }}>{{ $drinkingHabit->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('drinking_habit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="smoking_habit" class="form-label">Smoking Habit <span class="text-danger">*</span></label>
                                        <select id="smoking_habit" class="form-select rounded-pill shadow-none @error('smoking_habit') is-invalid @enderror" name="smoking_habit" required>
                                            <option value="">Select Smoking Habit</option>
                                            @foreach($db['smokingHabits'] as $smokingHabit)
                                                <option value="{{ $smokingHabit->name }}" {{ old('smoking_habit') == $smokingHabit->name ? 'selected' : '' }}>{{ $smokingHabit->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('smoking_habit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                </div>

                                <div class="bg-white border-0 p-3 d-flex justify-content-between align-items-center shadow-top sticky-bottom">
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn button2 rounded-pill">Logout</button>
                                    </form>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn button2 rounded-pill">Save & Next</button>
                                    </div>
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
