@extends('web.layouts.layout')
@section('title', $metaTags->title ?? 'Bala Matrimony Bureau - Find Your Perfect Life Partner')
@section('description', $metaTags->description ?? 'Discover meaningful connections rooted in Indian culture and traditions. Where love meets tradition, and families unite in harmony.')
@section('keywords', $metaTags->keywords ?? 'matrimony, marriage, Indian matrimony, life partner, wedding')

@section('content')
    @include('web.includes.header')
    <!-- Hero Section -->
    <section class="hero-section" style="background: url('{{ asset('asset/img/banner/hero_image.webp') }}') center/cover no-repeat; display: flex; align-items: center;">
        <div class="container-fluid" style="max-width: 1300px;">
            <div class="row align-items-center text-white">
                <!-- Left Content -->
                <div class="col-md-5 mb-5 mt-5 mb-md-0 ps-5">
                    <h1 class="display-6 fw-bold">
                        Find your perfect match on your own terms
                    </h1>
                    <p class="lead mt-3">
                        A safe and trusted place to meet like-minded individuals who share your values, dreams, and cultural traditions.
                        Whether you are looking for companionship, commitment, or a partner to grow with — your journey begins here.
                    </p>
                </div>

                <div class="col-md-3"></div>

                <!-- Right Form -->
                <div class="col-md-4 mb-md-0 p-5" style="margin-bottom: 100px;">
                    <div class="p-4 rounded-4 shadow-lg bg-glass">
                        <form method="post" action="{{ route('register') }}">
                            @csrf
                            <!-- Profile For -->
                            <div class="mb-3">
                                <select class="glossy-input form-select shadow-none rounded-pill @error('profile_for') is-invalid @enderror" name="profile_for">
                                    <option value="">Select Profile for</option>
                                    @foreach(['Self','Son','Daughter','Brother','Sister','Friend','Relative'] as $option)
                                        <option value="{{ $option }}" {{ old('profile_for') == $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                </select>
                                @error('profile_for')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Gender -->
                            <div class="mb-3">
                                <select class="glossy-input form-select shadow-none rounded-pill @error('gender') is-invalid @enderror" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Name -->
                            <div class="mb-3">
                                <input type="text" class="glossy-input form-control shadow-none rounded-pill @error('name') is-invalid @enderror" name="name" placeholder="Name" value="{{ old('name') }}">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <input type="email" class="glossy-input form-control shadow-none rounded-pill @error('email') is-invalid @enderror" name="email" placeholder="Email" value="{{ old('email') }}">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Mobile -->
                            <div class="mb-3">
                                <input type="tel" class="glossy-input form-control shadow-none rounded-pill @error('mobile') is-invalid @enderror" name="mobile" placeholder="Mobile number" id="phone" value="{{ old('mobile') }}">
                                <input type="hidden" name="country_code" id="phone_country_code">
                                @error('mobile')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3 position-relative">
                                <input type="password" id="password" class="glossy-input form-control shadow-none rounded-pill pe-5 @error('password') is-invalid @enderror" name="password" placeholder="Password">
                                <i class="bi bi-eye-slash toggle-icon" onclick="togglePassword(this, 'password')"></i>
                                @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3 position-relative">
                                <input type="password" id="password_confirmation" class="glossy-input shadow-none form-control rounded-pill pe-5 @error('password_confirmation') is-invalid @enderror" name="password_confirmation" placeholder="Confirm Password">
                                <i class="bi bi-eye-slash toggle-icon" onclick="togglePassword(this, 'password_confirmation')"></i>
                                @error('password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Terms -->
                            <div class="mb-3 form-check">
                                <input class="form-check-input shadow-none" type="checkbox" id="terms" onchange="toggleRegisterButton()">
                                <label class="form-check-label text-dark" for="terms">
                                    I agree to the <a href="{{ url('terms-and-condition') }}" target="_blank">Terms & conditions</a>
                                </label>
                            </div>

                            <!-- Submit -->
                            <div class="d-grid">
                                <button class="btn button2 rounded-pill py-2" id="registerBtn" type="submit" disabled>Register for Free</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="container" style="margin-top: -45px;">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-4">
                <form class="row g-3 align-items-center justify-content-center" action="{{ route('homeSearch') }}" method="get">

                    <div class="col-md-2 col-6">
                        <select class="form-select rounded-pill shadow-none border-2" name="gender">
                            <option value="">Looking for</option>
                            <option value="Female">Bride</option>
                            <option value="Male">Groom</option>
                        </select>
                    </div>

                    <div class="col-md-2 col-6">
                        <select class="form-select rounded-pill shadow-none border-2" name="min_age" id="min_age">
                            <option value="">Min age</option>
                        </select>
                    </div>

                    <div class="col-md-2 col-6">
                        <select class="form-select rounded-pill shadow-none border-2" name="max_age" id="max_age">
                            <option value="">Max age</option>
                        </select>
                    </div>

                    <div class="col-md-2 col-6">
                        <select class="form-select rounded-pill shadow-none border-2" name="religion">
                            <option value="">Religion</option>
                            @foreach($db['religions'] as $religion)
                                <option value="{{ $religion->name }}">{{ $religion->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 col-6">
                        <select class="form-select rounded-pill shadow-none border-2" name="city">
                            <option value="">City</option>
                            @foreach($customizeDB['cities'] as $city)
                                <option value="{{ $city->name }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 text-center mt-3">
                        <button type="submit" class="btn button1 rounded-pill px-4 py-2 fw-semibold shadow-sm">
                            Find My Perfect Match
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </section>


    <section class="container-fluid my-5">
        <div class="">
            <h4 class="display-5 fw-bold text-center">Our Matrimony Trusted by Millions</h4>
            <p class="text-center fw-normal">India's most successful matrimony platform</p>
        </div>
        <div class="row py-3 px-md-5">
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="card r-clip p-3">
                    <div class="card-body">
                        <h2 class="text-center text-white fw-bold">500+</h2>
                        <p class="text-center text-white">Matches Made</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="card p-3 r-clip">
                    <div class="card-body">
                        <h2 class="text-center text-white fw-bold">800+</h2>
                        <p class="text-center text-white">Active Users</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="card p-3 r-clip">
                    <div class="card-body">
                        <h2 class="text-center text-white fw-bold">10+</h2>
                        <p class="text-center text-white">Cities Covered</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="card p-3 r-clip">
                    <div class="card-body">
                        <h2 class="text-center text-white fw-bold">99%</h2>
                        <p class="text-center text-white">Profile Accuracy</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section id="browseProfilesContent" class="container-fluid my-5" style="background: url('{{ asset('asset/img/banner/banner1.png') }}'); ">
        <div class="p-md-5 p-2">
            <h4 class="display-5 fw-bold text-center">Find Your Match by Profession</h4>
            <p class="text-center fw-normal">Discover compatibility through shared career paths and values.</p>
        </div>

        <div class="px-md-5">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 shadow-lg h-100 position-relative overflow-hidden rounded-4">
                        <img src="{{ asset('asset/img/P01.png') }}"
                             class="card-img h-100 object-fit-cover" alt="Doctor">
                        <div class="card-img-overlay d-flex flex-column justify-content-end p-4" style="background: linear-gradient(135deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.6) 100%);">
                            <h4 class="card-title text-white fw-bold mb-2">Doctors</h4>
                            <p class="card-text text-white-50 mb-0">Caring Hearts, Committed Lives</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 shadow-lg h-100 position-relative overflow-hidden rounded-4">
                        <img src="{{ asset('asset/img/P02.png') }}"
                             class="card-img h-100 object-fit-cover" alt="Engineer">
                        <div class="card-img-overlay d-flex flex-column justify-content-end p-4" style="background: linear-gradient(135deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.6) 100%);">
                            <h4 class="card-title text-white fw-bold mb-2">Engineers</h4>
                            <p class="card-text text-white-50 mb-0">Building Futures Together</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 shadow-lg h-100 position-relative overflow-hidden rounded-4">
                        <img src="{{ asset('asset/img/P03.png') }}"
                             class="card-img h-100 object-fit-cover" alt="Government Employee">
                        <div class="card-img-overlay d-flex flex-column justify-content-end p-4" style="background: linear-gradient(135deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.6) 100%);">
                            <h4 class="card-title text-white fw-bold mb-2">Government</h4>
                            <p class="card-text text-white-50 mb-0">Stable Lives, Trusted Partners</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card border-0 shadow-lg h-100 position-relative overflow-hidden rounded-4">
                        <img src="{{ asset('asset/img/P04.png') }}"
                             class="card-img h-100 object-fit-cover" alt="Entrepreneur">
                        <div class="card-img-overlay d-flex flex-column justify-content-end p-4" style="background: linear-gradient(135deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.6) 100%);">
                            <h4 class="card-title text-white fw-bold mb-2">Entrepreneurs</h4>
                            <p class="card-text text-white-50 mb-0">Visionaries Seeking Companions</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="{{ url('all-profiles') }}" class="btn button1 btn-lg rounded-pill px-4 py-3 fw-semibold">
                    View all
                </a>
            </div>
        </div>
    </section>


    <section class="container-fluid my-5" style="background: url('{{ asset('asset/img/banner/banner2.png') }}') repeat-x;">
        <div class="p-md-5 p-2">
            <h4 class="display-5 fw-bold ">Are You a Perfect Match?</h4>
            <p class="fw-normal">Take our compatibility quiz and discover what makes you and your partner truly compatible.</p>
        </div>

        <div class="px-2 px-md-5 mb-5">
            <div class="row g-4">

                <!-- Card 1 – Personality Match -->
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 rounded-4 shadow-sm primary-border-style">
                        <div class="card-body text-center p-4" id="quizCard">
                            <!-- Intro Section -->
                            <div id="quizIntro">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="badge bg-light fs-1 fw-bold px-3 py-2 rounded-3 grey-text">01</span>
                                    <div class="rounded-circle d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('asset/img/icon/heart_check.png') }}" alt="">
                                    </div>
                                </div>
                                <h4 class="card-title mb-3 gradient-text">Personality Match</h4>
                                <p class="card-text text-muted mb-4">Discover personality compatibility</p>
                                <button id="startQuizBtn" class="btn button2 w-100 rounded-pill py-3 fw-semibold">Start the Quiz</button>
                            </div>
                            <!-- Quiz Container -->
                            <div id="quizContainer" class="text-start mt-3" style="display:none;"></div>
                        </div>
                    </div>
                </div>

                <!-- Card 2 – Values Alignment -->
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 rounded-4 shadow-sm primary-border-style">
                        <div class="card-body text-center p-4" id="valuesQuizCard">
                            <!-- Intro Section -->
                            <div id="valuesIntro">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="badge bg-light fs-1 fw-bold px-3 py-2 rounded-3 grey-text">02</span>
                                    <div class="rounded-circle d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('asset/img/icon/favorite.png') }}" alt="">
                                    </div>
                                </div>
                                <h4 class="card-title mb-3 gradient-text">Values Alignment</h4>
                                <p class="card-text text-muted mb-4">Find shared values and beliefs</p>
                                <button id="startValuesQuizBtn" class="btn w-100 rounded-pill py-3 fw-semibold button-outline">Start the Quiz</button>
                            </div>
                            <!-- Quiz Container -->
                            <div id="valuesQuizContainer" class="text-start mt-3" style="display:none;"></div>
                        </div>
                    </div>
                </div>

                <!-- Card 3 – Lifestyle Match -->
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 rounded-4 shadow-sm primary-border-style">
                        <div class="card-body text-center p-4" id="lifestyleQuizCard">
                            <!-- Intro Section -->
                            <div id="lifestyleIntro">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="badge bg-light fs-1 fw-bold px-3 py-2 rounded-3 grey-text">03</span>
                                    <div class="rounded-circle d-flex align-items-center justify-content-center">
                                        <img src="{{ asset('asset/img/icon/digital_wellbeing.png') }}" alt="">
                                    </div>
                                </div>
                                <h4 class="card-title mb-3 gradient-text">Lifestyle Match</h4>
                                <p class="card-text text-muted mb-4">Explore compatibility through daily habits</p>
                                <button id="startLifestyleQuizBtn" class="btn w-100 rounded-pill py-3 fw-semibold button-outline">Start the Quiz</button>
                            </div>
                            <!-- Quiz Container -->
                            <div id="lifestyleQuizContainer" class="text-start mt-3" style="display:none;"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <section class="container-fluid my-5 text-center">
        <div class="p-2 p-md-5">
            <h4 class="display-5 fw-bold">Celebrate Every Ritual with Love</h4>
            <p class="fw-normal">
                From Haldi to Mehendi to the grand wedding day — witness stories of real couples who found their perfect match on our platform.
            </p>
        </div>

        <div class="position-relative mx-auto shadow-lg rounded-4 overflow-hidden" style="max-width: 900px; height: 500px;">
            <video id="ritualVideo" class="w-100 h-100 object-fit-cover" preload="metadata">
                <source src="{{ asset('asset/img/video/video1.mp4') }}" type="video/mp4">
                Your browser does not support video.
            </video>

            <!-- Play overlay -->
            <div id="playOverlay"
                 class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex justify-content-center align-items-center"
                 role="button">
                <div class="bg-white rounded-circle shadow d-flex justify-content-center align-items-center"
                     style="width: 80px; height: 80px;">
                    <i class="bi bi-play-fill fs-1 text-danger"></i>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const video = document.getElementById("ritualVideo");
            const overlay = document.getElementById("playOverlay");

            // Click overlay → Play
            overlay.addEventListener("click", () => {
                overlay.classList.add("opacity-0");
                setTimeout(() => overlay.classList.add("d-none"), 300);
                video.play();
            });

            // Click video → Pause
            video.addEventListener("click", () => {
                if (!video.paused) {
                    video.pause();
                }
            });

            // When paused → Show overlay
            video.addEventListener("pause", () => {
                if (video.currentTime < video.duration) { // avoid showing at end
                    overlay.classList.remove("d-none");
                    setTimeout(() => overlay.classList.remove("opacity-0"), 10);
                }
            });

            // When ended → Show overlay again
            video.addEventListener("ended", () => {
                overlay.classList.remove("d-none");
                setTimeout(() => overlay.classList.remove("opacity-0"), 10);
            });
        });
    </script>


    <section class="bg-gradient-custom min-vh-100" id="successStoryContent">
        <div class="container py-5">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-light mb-3">Success Stories of Our Matrimony</h1>
                <p class="lead text-light">Real couples, real happiness</p>
            </div>

            <!-- Swiper HTML Structure -->
            <div class="swiper carousal1">
                <div class="swiper-wrapper">
                    <!-- Slide 1 -->
                    <div class="swiper-slide">
                        <img class="img-fluid w-100 rounded shadow" src="{{ asset('asset/img/SS01.png') }}" alt="Aisha & Vikram" style="height: 250px; object-fit: cover">
                        <div class="card text-center shadow mx-auto" style="width: 90%; margin-top: -60px; position: relative; z-index: 10;">
                            <div class="card-body">
                                <blockquote class="blockquote mb-3">
                                    <p class="fst-italic fs-6 text-muted px-2">
                                        "Our love story started here, and it blossomed into a beautiful marriage within 6 months! Forever grateful!"
                                    </p>
                                </blockquote>
                                <h5 class="card-title fw-bold text-dark mb-1">Aisha & Vikram</h5>
                                <p class="card-text text-muted">Mumbai</p>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 2 -->
                    <div class="swiper-slide">
                        <img class="img-fluid w-100 rounded shadow" src="{{ asset('asset/img/SS02.png') }}" alt="Meera & Rohan">
                        <div class="card text-center shadow mx-auto" style="width: 90%; margin-top: -60px; position: relative; z-index: 10;">
                            <div class="card-body">
                                <blockquote class="blockquote mb-3">
                                    <p class="fst-italic fs-6 text-muted px-2">
                                        "We met on this platform and tied the knot in 3 months. Thank you for bringing us together!"
                                    </p>
                                </blockquote>
                                <h5 class="card-title fw-bold text-dark mb-1">Meera & Rohan</h5>
                                <p class="card-text text-muted">Delhi</p>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 3 -->
                    <div class="swiper-slide">
                        <img class="img-fluid w-100 rounded shadow" src="{{ asset('asset/img/SS03.png') }}" alt="Sonia & Aarav">
                        <div class="card text-center shadow mx-auto" style="width: 90%; margin-top: -60px; position: relative; z-index: 10;">
                            <div class="card-body">
                                <blockquote class="blockquote mb-3">
                                    <p class="fst-italic fs-6 text-muted px-2">
                                        "What a journey! We connected on this platform, and in just 2 months, we were walking down the aisle!"
                                    </p>
                                </blockquote>
                                <h5 class="card-title fw-bold text-dark mb-1">Sonia & Aarav</h5>
                                <p class="card-text text-muted">Bangalore</p>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-slide">
                        <img class="img-fluid w-100 rounded shadow" src="{{ asset('asset/img/SS01.png') }}" alt="Sonia & Aarav">
                        <div class="card text-center shadow mx-auto" style="width: 90%; margin-top: -60px; position: relative; z-index: 10;">
                            <div class="card-body">
                                <blockquote class="blockquote mb-3">
                                    <p class="fst-italic fs-6 text-muted px-2">
                                        "What a journey! We connected on this platform, and in just 2 months, we were walking down the aisle!"
                                    </p>
                                </blockquote>
                                <h5 class="card-title fw-bold text-dark mb-1">Aisha & Vikram</h5>
                                <p class="card-text text-muted">Mumbai</p>
                            </div>
                        </div>
                    </div>


                </div>

                <!-- Pagination inside swiper -->
                {{--                <div class="swiper-pagination"></div>--}}
            </div>

            <!-- Navigation Buttons Below Carousel -->
            <div class="swiper-navigation d-flex justify-content-center gap-3 mt-4 mb-4">
                <button class="btn gradient-bg-2 rounded-circle shadow-sm carousal1-prev" style="width: 48px; height: 48px;">
                    <i class="bi bi-arrow-left"></i>
                </button>
                <button class="btn gradient-bg-2 rounded-circle shadow-sm carousal1-next" style="width: 48px; height: 48px;">
                    <i class="bi bi-arrow-right"></i>
                </button>
            </div>

        </div>
    </section>


    <script>
        class Quiz {
            constructor(config) {
                this.questions = config.questions;
                this.results   = config.results;
                this.headTitle   = config.headTitle;
                this.container = document.getElementById(config.containerId);
                this.intro     = document.getElementById(config.introId);
                this.startBtn  = document.getElementById(config.startBtnId);
                this.resultTitle = config.resultTitle || 'Your result:';
                this.currentQuestion = 0;
                this.typeScores = {};

                // initialize scores
                this.questions[0].options.forEach(o => { this.typeScores[o.type] = 0 });

                this.startBtn.addEventListener('click', () => this.start());
            }

            start() {
                this.intro.style.display = 'none';
                this.container.style.display = 'block';
                this.showQuestion();
            }

            showQuestion() {
                const q = this.questions[this.currentQuestion];
                this.container.innerHTML = `
                                <div class="mb-3 fw-bold">${q.q}</div>
                                <form id="quizForm">
                                  <div class="option-group d-flex flex-column gap-2">
                                    ${q.options.map(o => `
                                      <label class="border rounded p-2">
                                        <input type="radio" name="answer" value="${o.type}"> ${o.text}
                                      </label>
                                    `).join('')}
                                  </div>
                                  <button type="submit" class="btn button2 rounded-pill mt-3 w-100" disabled>Next</button>
                                </form>
                              `;

                const form = document.getElementById('quizForm');
                const nextBtn = form.querySelector('button');

                form.addEventListener('change', e => {
                    if (e.target.name === 'answer') nextBtn.disabled = false;
                });

                form.onsubmit = e => {
                    e.preventDefault();
                    const choice = form.answer.value;
                    this.typeScores[choice]++;
                    this.currentQuestion++;
                    if (this.currentQuestion < this.questions.length) {
                        this.showQuestion();
                    } else {
                        this.showResult();
                    }
                };
            }

            showResult() {
                const [ topType ] = Object.entries(this.typeScores)
                    .sort((a,b) => b[1] - a[1])[0];

                this.container.innerHTML = `
                            <div class="result text-center">
                              <h4 class="card-title mb-3 gradient-text">${this.headTitle}</h4>
                              <h4 class="text-success mb-2">${this.resultTitle} <span class="text-danger">${topType}</span></h4>
                              <p class="text-muted">${this.results[topType]}</p>
                              <button class="btn button1 mt-3" onclick="${this.restartName}()">Restart Quiz</button>
                            </div>
                          `;
            }

            restart() {
                this.currentQuestion = 0;
                Object.keys(this.typeScores).forEach(k => this.typeScores[k] = 0);
                this.container.style.display = 'none';
                this.intro.style.display = 'block';
            }

            get restartName() {
                return `restart_${this.container.id}`;
            }
        }

        // make restart functions global
        function createRestartFunction(inst, name) {
            window[`restart_${name}`] = () => inst.restart();
        }

        // instantiate all three quizzes:

        // 1. Personality Match
        const quiz1 = new Quiz({
            questions: [
                { q: "How do you usually spend your weekends?", options: [
                        { text: "Exploring new places", type: "Explorer" },
                        { text: "Reading or watching documentaries", type: "Thinker" },
                        { text: "Spending time with family", type: "Nurturer" }
                    ]},
                { q: "In a disagreement, you usually:", options: [
                        { text: "Say what’s on your mind", type: "Explorer" },
                        { text: "Walk away to cool down", type: "Thinker" },
                        { text: "Talk it out calmly", type: "Nurturer" }
                    ]},
                { q: "What's your ideal vacation?", options: [
                        { text: "Road trip with no plan", type: "Explorer" },
                        { text: "Solo trip for learning", type: "Thinker" },
                        { text: "Cozy family stay", type: "Nurturer" }
                    ]},
                { q: "How do you make decisions?", options: [
                        { text: "Gut feeling", type: "Explorer" },
                        { text: "Logical analysis", type: "Thinker" },
                        { text: "Impact on others", type: "Nurturer" }
                    ]},
                { q: "What do you value most in a partner?", options: [
                        { text: "Energy and fun", type: "Explorer" },
                        { text: "Stability and wisdom", type: "Thinker" },
                        { text: "Compassion and understanding", type: "Nurturer" }
                    ]}
            ],
            results: {
                Explorer: "You're energetic, spontaneous, and love trying new things. In a relationship, you're fun-loving and bring a sense of adventure.",
                Thinker:  "You're introspective, logical, and thoughtful. You prefer deep conversations and a calm, stable relationship.",
                Nurturer: "You're empathetic, caring, and emotionally intelligent. You seek meaningful connections and always look after your loved ones."
            },
            headTitle: "Personality Match",
            containerId:  "quizContainer",
            introId:       "quizIntro",
            startBtnId:    "startQuizBtn",
            resultTitle:   "You are a"
        });
        createRestartFunction(quiz1, "quizContainer");

        // 2. Values Alignment
        const quiz2 = new Quiz({
            questions: [
                { q: "What does marriage mean to you?", options: [
                        { text: "A sacred family union", type: "Traditionalist" },
                        { text: "Love, values, and shared growth", type: "Balanced" },
                        { text: "A partnership based on freedom", type: "Progressive" }
                    ]},
                { q: "How do you view family roles?", options: [
                        { text: "Defined by culture and gender", type: "Traditionalist" },
                        { text: "Flexible, but mutual responsibility", type: "Balanced" },
                        { text: "Equal partnership without roles", type: "Progressive" }
                    ]},
                { q: "What’s your approach to spirituality?", options: [
                        { text: "Strong belief and practice", type: "Traditionalist" },
                        { text: "Respect traditions, stay open", type: "Balanced" },
                        { text: "Spiritual or not religious", type: "Progressive" }
                    ]},
                { q: "How do you see your ideal future?", options: [
                        { text: "Settled with family and tradition", type: "Traditionalist" },
                        { text: "Career + family in balance", type: "Balanced" },
                        { text: "Personal growth and freedom", type: "Progressive" }
                    ]},
                { q: "How important are societal expectations?", options: [
                        { text: "Very important – I follow them", type: "Traditionalist" },
                        { text: "Follow some, question others", type: "Balanced" },
                        { text: "I prefer creating my own path", type: "Progressive" }
                    ]}
            ],
            results: {
                Traditionalist: "You value culture, family roots, and deep traditions. You believe in fulfilling traditional roles and preserving beliefs that give life structure.",
                Balanced:       "You value both modern outlook and traditional roots. You seek a relationship based on mutual respect, responsibility, and shared values.",
                Progressive:    "You value freedom, self-expression, and individual growth. You see relationships as evolving partnerships built on shared respect and personal choices."
            },
            headTitle: "Values Alignment",
            containerId: "valuesQuizContainer",
            introId:      "valuesIntro",
            startBtnId:   "startValuesQuizBtn",
            resultTitle:  "You are"
        });
        createRestartFunction(quiz2, "valuesQuizContainer");

        // 3. Lifestyle Match
        const quiz3 = new Quiz({
            questions: [
                { q: "What’s your daily routine like?", options: [
                        { text: "Structured and disciplined", type: "Planned" },
                        { text: "Balanced with flexibility", type: "Balanced" },
                        { text: "Spontaneous and relaxed", type: "Free-Spirit" }
                    ]},
                { q: "Your idea of a perfect weekend?", options: [
                        { text: "Early mornings, productive tasks", type: "Planned" },
                        { text: "Mix of chores and relaxation", type: "Balanced" },
                        { text: "Sleep in, do whatever feels right", type: "Free-Spirit" }
                    ]},
                { q: "How do you manage fitness?", options: [
                        { text: "Regular gym or workouts", type: "Planned" },
                        { text: "Active when possible", type: "Balanced" },
                        { text: "I don’t stress over it", type: "Free-Spirit" }
                    ]},
                { q: "Eating habits?", options: [
                        { text: "Strict diet & meal planning", type: "Planned" },
                        { text: "Try to eat healthy", type: "Balanced" },
                        { text: "Eat what I feel like", type: "Free-Spirit" }
                    ]},
                { q: "How important is work-life balance?", options: [
                        { text: "Work comes first", type: "Planned" },
                        { text: "Balance is essential", type: "Balanced" },
                        { text: "Life over work always", type: "Free-Spirit" }
                    ]}
            ],
            results: {
                Planned:     "You live a disciplined and focused life. You prioritize routines, structure, and productivity — a partner who values order will align well with you.",
                Balanced:    "You enjoy a healthy mix of discipline and freedom. Flexibility, self-care, and practicality matter most — a great trait for sustainable relationships.",
                "Free-Spirit":"You're all about living in the moment and following your heart. You prefer spontaneity, emotional freedom, and value relaxed energy in a partner."
            },
            headTitle: "Lifestyle Match",
            containerId: "lifestyleQuizContainer",
            introId:      "lifestyleIntro",
            startBtnId:   "startLifestyleQuizBtn",
            resultTitle:  "Your lifestyle match is"
        });
        createRestartFunction(quiz3, "lifestyleQuizContainer");
    </script>
    <script>
        const authUserGender = @json(optional($userAndUserDetails)->gender ? strtolower($userAndUserDetails->gender) : null);
        const savedMinAge = null;
        const savedMaxAge = null;

        function togglePassword(iconElem, inputId) {
            const input = document.getElementById(inputId);
            const showIcon = "bi-eye";
            const hideIcon = "bi-eye-slash";

            if (input.type === "password") {
                input.type = "text";
                iconElem.classList.replace(hideIcon, showIcon);
            } else {
                input.type = "password";
                iconElem.classList.replace(showIcon, hideIcon);
            }
        }

        function toggleRegisterButton() {
            document.getElementById('registerBtn').disabled = !document.getElementById('terms').checked;
        }
    </script>
    <script src="{{ asset('asset/js/script.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/swiper/swiper-bundle.min.css') }}" />
    <script src="{{ asset('asset/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('asset/swiper/swiper-custom.js') }}"></script>
    <script src="{{asset('asset/dialcode/dial-code.js')}}"></script>
    <script src="{{asset('asset/dialcode/intlTelInput.min.js')}}"></script>
    <script src="{{asset('asset/dialcode/utils.js')}}"></script>

    @include('web.includes.footer')
@endsection
