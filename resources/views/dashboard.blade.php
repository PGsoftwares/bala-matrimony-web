@extends('web.layouts.layout')
@section('title', $metaTags->title ?? '')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <div class="bg-light-primary">
        <section class="container-fluid">
            <div class="row">
                <div class="col-md-8 mb-5">

                    {{--New Profiles--}}
                    <div class="mt-4 mb-4 p-2 shadow-sm rounded-2 bg-white">
                        <div class="d-flex justify-content-between mt-2 mb-4">
                            <h4 class="fw-medium">New Profiles</h4>
                            <h4 class="fw-medium primary_color">{{ $recentUserDetailsCount }}</h4>
                        </div>

                        <div class="swiper carousal2">
                            <div class="swiper-wrapper">

                                @foreach($recentUserDetails as $userDetail)
                                    <div class="swiper-slide">
                                        <div class="card border-0 shadow-sm rounded-4">
                                            <div class="position-relative">
                                                <a href="{{ route('all-profiles.show', $userDetail->user_id) }}"  onclick="event.preventDefault(); document.getElementById('viewed-profile-form-{{ $userDetail->user_id }}').submit();">
                                                    <img src="{{ $userDetail->profile_image }}" alt="" class="card-img-top rounded-top" style="width: 100%; height: 250px; object-fit: cover">
                                                </a>
                                                @if(!empty($userDetail->email_verified_at))
                                                <div class="position-absolute bottom-0 start-0 bg-white px-2 py-1 m-2 rounded-pill shadow-sm small">
                                                    <i class="fa-regular fa-circle-check text-success me-1"></i> ID Verified
                                                </div>
                                                @endif
                                            </div>

                                            <div class="card-body">
                                                <h6 class="mb-0 fw-bold">{{ $userDetail->name ?? '---' }}</h6>
                                                <p class="small text-muted mb-1">{{ $userDetail->age ?? '---' }} &nbsp; | &nbsp; {{ $userDetail->city ?? '---' }}</p>
                                            </div>
                                        </div>

                                        <form id="viewed-profile-form-{{ $userDetail->user_id }}" method="POST" action="{{ route('all-profiles.viewedProfiles') }}">
                                            @csrf
                                            <input type="hidden" name="viewer_id" value="{{ $userDetail->user_id }}">
                                            <input type="hidden" name="viewed_id" value="{{ Auth::id() }}">
                                        </form>
                                    </div>
                                @endforeach

                            </div>

                            <div class="swiper-navigation d-flex justify-content-between align-items-center gap-3 mt-3">
                                <button class="btn gradient-bg-2 rounded-circle shadow-sm carousal2-prev" style="width: 48px; height: 48px;">
                                    <i class="bi bi-arrow-left"></i>
                                </button>
                                <div class="swiper-pagination flex-grow-1 text-center"></div>
                                <button class="btn gradient-bg-2 rounded-circle shadow-sm carousal2-next" style="width: 48px; height: 48px;">
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    {{--End of New Profiles--}}

                    {{-- Profession --}}
                    <div class="mt-4 mb-4 p-3 shadow-sm rounded-3 bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="fw-semibold mb-0">Select by Profession</h4>
                            <h4 class="fw-semibold text-danger mb-0"></h4>
                        </div>

                        <div class="swiper carousal2">
                            <div class="swiper-wrapper">

                                @php
                                    $professionMap = [
                                        'Healthcare' => ['Doctor', 'Medical & Healthcare others'],
                                        'Engineering' => ['Engineering'],
                                        'IAS/IPS' => ['Civil Services'],
                                        'Business' => ['Corporate Professionals', 'Senior Management', 'Banking & Finance', 'Administration', 'Revenue'],
                                        'Defence' => ['Defence', 'Merchant Navy'],
                                        'Education' => ['Education & Training'],
                                        'Industrialist' => ['Industrial & Manufacturing', 'Agriculture'],
                                        'Fashion' => ['Beauty & Fashion', 'Architecture & Design'],
                                        'Law Enforcement' => ['Police / Law Enforcement'],
                                        'Legal' => ['Legal'],
                                        'IT' => ['IT & Software', 'BPO & Customer Service'],
                                        'Media' => ['Media & Entertainment', 'Airline'],
                                    ];

                                    $iconMap = [
                                        'Healthcare' => 'bi-heart-fill',
                                        'Engineering' => 'bi-code-square',
                                        'IAS/IPS' => 'bi-bank',
                                        'Business' => 'bi-building',
                                        'Defence' => 'bi-shield-shaded',
                                        'Education' => 'bi-book',
                                        'Industrialist' => 'bi-gear',
                                        'Fashion' => 'bi-star',
                                        'Law Enforcement'   => 'bi-person-badge',
                                        'Legal'             => 'bi-clipboard-check-fill',
                                        'IT'                => 'bi-cpu',
                                        'Media'             => 'bi-camera-video',
                                    ];
                                @endphp

                                @foreach($professionMap as $label => $occupations)
                                    @php
                                        $iconClass = $iconMap[$label] ?? 'bi-briefcase';
                                        $queryString = http_build_query(['occupation_type' => $occupations]);
                                    @endphp
                                    <a href="{{ url('all-profiles?' . $queryString) }}" class="swiper-slide text-decoration-none text-dark">
                                        <div class="text-center">
                                            <div class="rounded-circle bg-light-primary px-4 py-3 d-inline-block">
                                                <i class="bi {{ $iconClass }} fs-2 gradient-text"></i>
                                            </div>
                                            <p class="mt-2 small">{{ $label }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>

                            <!-- Navigation -->
                            <div class="swiper-navigation d-flex justify-content-between align-items-center gap-3 mt-3">
                                <button class="btn gradient-bg-2 rounded-circle shadow-sm carousal2-prev" style="width: 48px; height: 48px;">
                                    <i class="bi bi-arrow-left"></i>
                                </button>
                                <div class="swiper-pagination flex-grow-1 text-center"></div>
                                <button class="btn gradient-bg-2 rounded-circle shadow-sm carousal2-next" style="width: 48px; height: 48px;">
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    {{--End of Profession--}}

                    {{--Viewed my profiles--}}
                    <div class="mt-4 mb-4 p-3 shadow-sm border-0 bg-white rounded-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="fw-semibold mb-0">Viewed My Profiles</h4>
                            <h4 class="fw-semibold text-danger mb-0">{{ $viewedMyProfileCount }}</h4>
                        </div>

                        <div class="swiper carousal3">
                            <div class="swiper-wrapper">
                                @foreach($viewedMyProfile as $userDetail)
                                    <div class="swiper-slide">
                                        <div class="card border-0 rounded-4 shadow-sm px-2 py-3" style="background-color: #fdecee;">
                                            <div class="row g-3 align-items-center">

                                                <div class="col-md-4 text-center">
                                                    <a href="{{ route('all-profiles.show', $userDetail->user_id) }}"  onclick="event.preventDefault(); document.getElementById('viewed-profile-form-{{ $userDetail->user_id }}').submit();">
                                                        <img src="{{ $userDetail->profile_image }}" class="img-fluid rounded-3" alt="Profile Image" style="height: 200px; width: 100%; object-fit: cover;">
                                                    </a>
                                                </div>

                                                <div class="col-md-8">
                                                    <div class="d-flex flex-wrap align-items-center gap-2 small text-dark mb-2">
                                                        @if(!empty($userDetail->email_verified_at))
                                                        <span><i class="bi bi-patch-check-fill gradient-text me-1"></i> ID Verified</span>
                                                        @endif
{{--                                                        <span><i class="bi bi-star-fill gradient-text me-1"></i> Premium Member</span>--}}
                                                    </div>
                                                    <h6 class="fw-bold mb-1">{{ $userDetail->name }}</h6>
                                                    <p class="small text-muted mb-1">
                                                        {{ $userDetail->age }} years old • {{ $userDetail->height }} • {{ $userDetail->caste }}
                                                    </p>
                                                    <p class="small text-muted mb-1">
                                                        {{ $userDetail->education }} • {{ $userDetail->occupation }}
                                                    </p>
                                                    <p class="small text-muted">{{ $userDetail->city }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <form id="viewed-profile-form-{{ $userDetail->user_id }}" method="POST" action="{{ route('all-profiles.viewedProfiles') }}">
                                            @csrf
                                            <input type="hidden" name="viewer_id" value="{{ $userDetail->user_id }}">
                                            <input type="hidden" name="viewed_id" value="{{ Auth::id() }}">
                                        </form>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Navigation -->
                            <div class="swiper-navigation d-flex justify-content-between align-items-center gap-3 mt-3">
                                <button class="btn gradient-bg-2 rounded-circle shadow-sm carousal3-prev" style="width: 48px; height: 48px;">
                                    <i class="bi bi-arrow-left"></i>
                                </button>
                                <div class="swiper-pagination flex-grow-1 text-center"></div>
                                <button class="btn gradient-bg-2 rounded-circle shadow-sm carousal3-next" style="width: 48px; height: 48px;">
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    {{--End of viewed my profiles--}}

                    {{--Viewed By Me Profiles--}}
                    <div class="mt-4 mb-4 p-2 shadow-sm bg-white rounded-3 ">
                        <div class="d-flex justify-content-between mt-2 mb-4">
                            <h4 class="fw-medium">Viewed By Me</h4>
                            <h4 class="fw-medium primary_color">{{ $profileViewedByMeCount }}</h4>
                        </div>

                        <div class="swiper carousal2">
                            <div class="swiper-wrapper">
                                @foreach($profileViewedByMe as $userDetail)
                                    <div class="swiper-slide">
                                        <div class="card shadow-sm border-0 rounded-3">
                                            <div class="position-relative">
                                                <a href="{{ route('all-profiles.show', $userDetail->user_id) }}"  onclick="event.preventDefault(); document.getElementById('viewed-profile-form-{{ $userDetail->user_id }}').submit();">
                                                    <img src="{{ $userDetail->profile_image }}" alt="" class="card-img-top rounded-top" style="width: 100%; height: 250px; object-fit: cover">
                                                </a>
                                                @if(!empty($userDetail->email_verified_at))
                                                <div class="position-absolute bottom-0 start-0 bg-white px-2 py-1 m-2 rounded-pill shadow-sm small">
                                                    <i class="fa-regular fa-circle-check text-success me-1"></i> ID Verified
                                                </div>
                                                @endif
                                            </div>

                                            <div class="card-body">
                                                <h6 class="mb-0 fw-bold">{{ $userDetail->name ?? '---' }}</h6>
                                                <p class="small text-muted mb-1">{{ $userDetail->age ?? '---' }} &nbsp; | &nbsp; {{ $userDetail->city ?? '---' }}</p>
                                            </div>
                                        </div>

                                        <form id="viewed-profile-form-{{ $userDetail->user_id }}" method="POST" action="{{ route('all-profiles.viewedProfiles') }}">
                                            @csrf
                                            <input type="hidden" name="viewer_id" value="{{ $userDetail->user_id }}">
                                            <input type="hidden" name="viewed_id" value="{{ Auth::id() }}">
                                        </form>
                                    </div>
                                @endforeach
                            </div>

                            <div class="swiper-navigation d-flex justify-content-between align-items-center gap-3 mt-3">
                                <button class="btn gradient-bg-2 rounded-circle shadow-sm carousal2-prev" style="width: 48px; height: 48px;">
                                    <i class="bi bi-arrow-left"></i>
                                </button>
                                <div class="swiper-pagination flex-grow-1 text-center"></div>
                                <button class="btn gradient-bg-2 rounded-circle shadow-sm carousal2-next" style="width: 48px; height: 48px;">
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    {{--End of Viewed By Me--}}

                    {{--Matched profiles--}}
                    <div class="mt-4 mb-4 p-3 shadow-sm rounded-3 bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="fw-semibold mb-0">Matched Profiles</h4>
                            <h4 class="fw-semibold text-danger mb-0">{{ $relatedProfilesCount }}</h4>
                        </div>

                        <div class="swiper carousal3">
                            <div class="swiper-wrapper">
                                @forelse($relatedProfiles as $userDetail)
                                    <div class="swiper-slide">
                                        <div class="card h-100 border-0 rounded-4 shadow-sm px-2 py-3" style="background-color: #fdecee;">
                                            <div class="row g-3 align-items-center">

                                                <div class="col-md-4 text-center">
                                                    <a href="{{ route('all-profiles.show', $userDetail->user_id) }}"  onclick="event.preventDefault(); document.getElementById('viewed-profile-form-{{ $userDetail->user_id }}').submit();">
                                                        <img src="{{ $userDetail->profile_image }}" class="img-fluid rounded-3" alt="Profile Image" style="height: 200px; width: 100%; object-fit: cover;">
                                                    </a>
                                                </div>

                                                <div class="col-md-8">
                                                    <div class="d-flex flex-wrap align-items-center gap-2 small text-dark mb-2">
                                                        @if(!empty($userDetail->email_verified_at))
                                                        <span><i class="fa-solid fa-shield-halved text-warning me-1"></i> ID Verified</span>
                                                        @endif
{{--                                                        <span><i class="fa-solid fa-crown text-warning me-1"></i> Premium Member</span>--}}
                                                    </div>
                                                    <h6 class="fw-bold mb-1">{{ $userDetail->name }}</h6>
                                                    <p class="small text-muted mb-1">
                                                        {{ $userDetail->age }} years old • {{ $userDetail->height }} • {{ $userDetail->caste }}
                                                    </p>
                                                    <p class="small text-muted mb-1">
                                                        {{ $userDetail->education }} • {{ $userDetail->occupation }}
                                                    </p>
                                                    <p class="small text-muted">{{ $userDetail->city }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <form id="viewed-profile-form-{{ $userDetail->user_id }}" method="POST" action="{{ route('all-profiles.viewedProfiles') }}">
                                            @csrf
                                            <input type="hidden" name="viewer_id" value="{{ $userDetail->user_id }}">
                                            <input type="hidden" name="viewed_id" value="{{ Auth::id() }}">
                                        </form>
                                    </div>
                                @empty
                                    <div class="">
                                        <p class="alert alert-info">No matched profiles found</p>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Navigation -->
                            <div class="swiper-navigation d-flex justify-content-between align-items-center gap-3 mt-3">
                                <button class="btn gradient-bg-2 rounded-circle shadow-sm carousal3-prev" style="width: 48px; height: 48px;">
                                    <i class="bi bi-arrow-left"></i>
                                </button>
                                <div class="swiper-pagination flex-grow-1 text-center"></div>
                                <button class="btn gradient-bg-2 rounded-circle shadow-sm carousal3-next" style="width: 48px; height: 48px;">
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    {{--End of matched profiles--}}

                </div>

                @include('web.includes.right-aside')
            </div>
        </section>
    </div>


    <link rel="stylesheet" href="{{ asset('asset/swiper/swiper-bundle.min.css') }}" />
    <script src="{{ asset('asset/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('asset/swiper/swiper-custom.js') }}"></script>
    @include('web.includes.footer')
@endsection
