@php use Illuminate\Support\Facades\Auth; @endphp
@extends('web.layouts.layout')
@section('title', $metaTags->title ?? '')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <section class="container-fluid my-4">
        <div class="row g-3">
            <!-- Sidebar -->
            <div class="col-md-4">
                <div class="card shadow rounded-3 border-0 p-3 d-flex flex-column justify-content-between">
                    <div class="accordion" id="interestAccordion">
                        <!-- Received Section -->
                        <div class="accordion-item border-0 mb-2">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-semibold bg-light-primary border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#receivedCollapse">
                                    Received
                                </button>
                            </h2>
                            <div id="receivedCollapse" class="accordion-collapse collapse show" data-bs-parent="#interestAccordion">
                                <div class="accordion-body p-2 bg-light-primary">
                                    <button class="btn w-100 mb-2 sidebar-button active" data-target="receivedByMe">Received Interest</button>
                                    <button class="btn w-100 mb-2 sidebar-button" data-target="acceptedByMe">Accepted By Me</button>
                                    <button class="btn w-100 mb-2 sidebar-button" data-target="deniedByMe">Denied By Me</button>
                                </div>
                            </div>
                        </div>

                        <!-- Sent Section -->
                        <div class="accordion-item border-0" >
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-semibold bg-light-primary border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#sentCollapse" aria-expanded="true">
                                    Sent
                                </button>
                            </h2>
                            <div id="sentCollapse" class="accordion-collapse collapse " data-bs-parent="#interestAccordion">
                                <div class="accordion-body p-2 bg-light-primary" >
                                    <button class="btn w-100 mb-2 sidebar-button " data-target="sentByMe">Sent Interest</button>
                                    <button class="btn w-100 mb-2 sidebar-button" data-target="acceptedByOthers">Accepted My Interest</button>
                                    <button class="btn w-100 mb-2 sidebar-button" data-target="deniedByOthers">Denied My Interest</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="col-md-8">

                <div class="card shadow border-0 rounded-3 p-3">
                    {{--Received Tab--}}
                    {{--Received New Interest--}}

                    <div id="receivedByMe" class="profile-section active">

                        @if($newInterest->isEmpty())
                            <div class="alert alert-info text-center">
                                No Details Found.
                            </div>
                        @else
                            @foreach($newInterest as $user)
                            <div class="card mb-3 shadow-sm rounded-3 p-3">
                                <div class="row align-items-start">

                                    <div class="col-md-4 text-center">
                                        <img src="{{ $user['profile_image'] }}"
                                             class="img-fluid rounded " alt="Profile Image">
                                    </div>

                                    <div class="col-md-8">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="fw-bold mb-1">{{ $user['name'] ?? '' }}</h6>
                                                <div class="d-flex gap-2 flex-wrap">
                                                    <i class="bi bi-patch-check-fill gradient-text"></i> <span class="small">ID Verified</span>
                                                    <i class="bi bi-star-fill gradient-text"></i> <span class="small">Premium Member</span>
                                                </div>
                                            </div>

                                            <div class="text-end">
                                                <form action="{{ route('addWishlist') }}" method="POST" class="mr-2 mb-2">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                                    <input type="hidden" name="profile_id" value="{{ $user['id'] }}">
                                                    <button class="btn btn-light btn-sm rounded-circle shadow-sm " type="submit">
                                                        <i class="bi bi-heart fw-bold text-danger"></i>
                                                    </button>
                                                </form>
                                                <form id="viewed-profile-form-{{ $user['id'] }}" method="post" action="{{ route('all-profiles.viewedProfiles') }}">
                                                    @csrf
                                                    <input type="hidden" name="viewer_id" value="{{ $user['id'] }}">
                                                    <input type="hidden" name="viewed_id" value="{{ Auth::id() }}">
                                                    <a class="btn button1 rounded-pill btn-sm mb-1 py-2 px-2"
                                                       href="{{ route('all-profiles.show', $user['id']) }}"
                                                       onclick="event.preventDefault(); document.getElementById('viewed-profile-form-{{ $user['id'] }}').submit();">
                                                        View Profile
                                                    </a>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="row g-3 align-items-center mt-3">
                                            <div class="col-md-8">
                                                <div class="text-start small text-secondary">
                                                    <p class="mb-1">
                                                        <strong>Age:</strong> {{ $user['age'] ?? '' }} |
                                                        <strong>Height:</strong> {{ $user['height'] ?? '' }}
                                                    </p>
                                                    <p class="mb-1">
                                                        <strong>Religion:</strong> {{ $user['religion'] ?? '' }} &nbsp; | &nbsp;
                                                        <strong>Caste:</strong> {{ $user['caste'] ?? '' }}
                                                    </p>
                                                    <p class="mb-1">
                                                        <strong>Occupation:</strong> {{ $user['occupation'] ?? '' }}
                                                    </p>
                                                    <p class="mb-0">
                                                        <strong>City:</strong> {{ $user['city'] ?? '' }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-4 text-md-end text-start">
                                                <form method="POST" action="{{ route('interests.accept', $user['interest_id']) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn button2 mb-2 w-100 w-md-auto">Accept</button>
                                                </form>

                                                <form method="POST" action="{{ route('interests.deny', $user['interest_id']) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn button1 w-100 w-md-auto">Deny</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif

                    </div>

                    {{--Interest - Accepted By Me--}}
                    <div id="acceptedByMe" class="profile-section">
                        @if($acceptedByMe->isEmpty())
                            <div class="alert alert-info text-center">
                                No Details Found.
                            </div>
                        @else
                            @foreach($acceptedByMe as $user)
                                <div class="card mb-3 shadow-sm rounded-3 p-3">
                                    <div class="row align-items-start">

                                        <div class="col-md-4 text-center">
                                            <img src="{{ $user['profile_image'] }}"
                                                 class="img-fluid rounded " alt="Profile Image">
                                        </div>

                                        <div class="col-md-8">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="fw-bold mb-1">{{ $user['name'] ?? '' }}</h6>
                                                    <div class="d-flex gap-2 flex-wrap">
                                                        <i class="bi bi-patch-check-fill gradient-text"></i> <span class="small">ID Verified</span>
                                                        <i class="bi bi-star-fill gradient-text"></i> <span class="small">Premium Member</span>
                                                    </div>
                                                </div>

                                                <div class="text-end">
                                                    <form action="{{ route('addWishlist') }}" method="POST" class="mr-2 mb-2">
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                                        <input type="hidden" name="profile_id" value="{{ $user['id'] }}">
                                                        <button class="btn btn-light btn-sm rounded-circle shadow-sm " type="submit">
                                                            <i class="bi bi-heart fw-bold text-danger"></i>
                                                        </button>
                                                    </form>
                                                    <form id="viewed-profile-form-{{ $user['id'] }}" method="post" action="{{ route('all-profiles.viewedProfiles') }}">
                                                        @csrf
                                                        <input type="hidden" name="viewer_id" value="{{ $user['id'] }}">
                                                        <input type="hidden" name="viewed_id" value="{{ Auth::id() }}">
                                                        <a class="btn button1 rounded-pill btn-sm mb-1 py-2 px-2"
                                                           href="{{ route('all-profiles.show', $user['id']) }}"
                                                           onclick="event.preventDefault(); document.getElementById('viewed-profile-form-{{ $user['id'] }}').submit();">
                                                            View Profile
                                                        </a>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="row g-3 align-items-center mt-3">
                                                <div class="col-md-8">
                                                    <div class="text-start small text-secondary">
                                                        <p class="mb-1">
                                                            <strong>Age:</strong> {{ $user['age'] ?? '' }} |
                                                            <strong>Height:</strong> {{ $user['height'] ?? '' }}
                                                        </p>
                                                        <p class="mb-1">
                                                            <strong>Religion:</strong> {{ $user['religion'] ?? '' }} &nbsp; | &nbsp;
                                                            <strong>Caste:</strong> {{ $user['caste'] ?? '' }}
                                                        </p>
                                                        <p class="mb-1">
                                                            <strong>Occupation:</strong> {{ $user['occupation'] ?? '' }}
                                                        </p>
                                                        <p class="mb-0">
                                                            <strong>City:</strong> {{ $user['city'] ?? '' }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 text-md-end text-start">
                                                    <form method="POST" action="{{ route('interests.deny', $user['interest_id']) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn button1 w-100 w-md-auto">Deny</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                    </div>

                    {{--Interest - Denied By Me--}}
                    <div id="deniedByMe" class="profile-section">

                        @if($deniedByMe->isEmpty())
                            <div class="alert alert-info text-center">
                                No Details Found.
                            </div>
                        @else
                            @foreach($deniedByMe as $user)
                                <div class="card mb-3 shadow-sm rounded-3 p-3">
                                    <div class="row align-items-start">

                                        <div class="col-md-4 text-center">
                                            <img src="{{ $user['profile_image'] }}"
                                                 class="img-fluid rounded " alt="Profile Image">
                                        </div>

                                        <div class="col-md-8">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="fw-bold mb-1">{{ $user['name'] ?? '' }}</h6>
                                                    <div class="d-flex gap-2 flex-wrap">
                                                        <i class="bi bi-patch-check-fill gradient-text"></i> <span class="small">ID Verified</span>
                                                        <i class="bi bi-star-fill gradient-text"></i> <span class="small">Premium Member</span>
                                                    </div>
                                                </div>

                                                <div class="text-end">
                                                    <form action="{{ route('addWishlist') }}" method="POST" class="mr-2 mb-2">
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                                        <input type="hidden" name="profile_id" value="{{ $user['id'] }}">
                                                        <button class="btn btn-light btn-sm rounded-circle shadow-sm " type="submit">
                                                            <i class="bi bi-heart fw-bold text-danger"></i>
                                                        </button>
                                                    </form>
                                                    <form id="viewed-profile-form-{{ $user['id'] }}" method="post" action="{{ route('all-profiles.viewedProfiles') }}">
                                                        @csrf
                                                        <input type="hidden" name="viewer_id" value="{{ $user['id'] }}">
                                                        <input type="hidden" name="viewed_id" value="{{ Auth::id() }}">
                                                        <a class="btn button1 rounded-pill btn-sm mb-1 py-2 px-2"
                                                           href="{{ route('all-profiles.show', $user['id']) }}"
                                                           onclick="event.preventDefault(); document.getElementById('viewed-profile-form-{{ $user['id'] }}').submit();">
                                                            View Profile
                                                        </a>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="row g-3 align-items-center mt-3">
                                                <div class="col-md-8">
                                                    <div class="text-start small text-secondary">
                                                        <p class="mb-1">
                                                            <strong>Age:</strong> {{ $user['age'] ?? '' }} |
                                                            <strong>Height:</strong> {{ $user['height'] ?? '' }}
                                                        </p>
                                                        <p class="mb-1">
                                                            <strong>Religion:</strong> {{ $user['religion'] ?? '' }} &nbsp; | &nbsp;
                                                            <strong>Caste:</strong> {{ $user['caste'] ?? '' }}
                                                        </p>
                                                        <p class="mb-1">
                                                            <strong>Occupation:</strong> {{ $user['occupation'] ?? '' }}
                                                        </p>
                                                        <p class="mb-0">
                                                            <strong>City:</strong> {{ $user['city'] ?? '' }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 text-md-end text-start">
                                                    <form method="POST" action="{{ route('interests.accept', $user['interest_id']) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn button2 mb-2 w-100 w-md-auto">Accept</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                    </div>

                    {{--Send Tab--}}
                    {{--Interest - Sent By Me--}}
                    <div id="sentByMe" class="profile-section">
                        @if($sentByMe->isEmpty())
                            <div class="alert alert-info text-center">
                                No Details Found.
                            </div>
                        @else
                            @foreach($sentByMe as $user)
                                <div class="card mb-3 shadow-sm rounded-3 p-3">
                                    <div class="row align-items-start">

                                        <div class="col-md-4 text-center">
                                            <img src="{{ $user['profile_image'] }}"
                                                 class="img-fluid rounded " alt="Profile Image">
                                        </div>

                                        <div class="col-md-8">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="fw-bold mb-1">{{ $user['name'] ?? '' }}</h6>
                                                    <div class="d-flex gap-2 flex-wrap">
                                                        <i class="bi bi-patch-check-fill gradient-text"></i> <span class="small">ID Verified</span>
                                                        <i class="bi bi-star-fill gradient-text"></i> <span class="small">Premium Member</span>
                                                    </div>
                                                </div>

                                                <div class="text-end">
                                                    <form action="{{ route('addWishlist') }}" method="POST" class="mr-2 mb-2">
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                                        <input type="hidden" name="profile_id" value="{{ $user['id'] }}">
                                                        <button class="btn btn-light btn-sm rounded-circle shadow-sm " type="submit">
                                                            <i class="bi bi-heart fw-bold text-danger"></i>
                                                        </button>
                                                    </form>
                                                    <form id="viewed-profile-form-{{ $user['id'] }}" method="post" action="{{ route('all-profiles.viewedProfiles') }}">
                                                        @csrf
                                                        <input type="hidden" name="viewer_id" value="{{ $user['id'] }}">
                                                        <input type="hidden" name="viewed_id" value="{{ Auth::id() }}">
                                                        <a class="btn button1 rounded-pill btn-sm mb-1 py-2 px-2"
                                                           href="{{ route('all-profiles.show', $user['id']) }}"
                                                           onclick="event.preventDefault(); document.getElementById('viewed-profile-form-{{ $user['id'] }}').submit();">
                                                            View Profile
                                                        </a>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="row g-3 align-items-center mt-3">
                                                <div class="col-md-8">
                                                    <div class="text-start small text-secondary">
                                                        <p class="mb-1">
                                                            <strong>Age:</strong> {{ $user['age'] ?? '' }} |
                                                            <strong>Height:</strong> {{ $user['height'] ?? '' }}
                                                        </p>
                                                        <p class="mb-1">
                                                            <strong>Religion:</strong> {{ $user['religion'] ?? '' }} &nbsp; | &nbsp;
                                                            <strong>Caste:</strong> {{ $user['caste'] ?? '' }}
                                                        </p>
                                                        <p class="mb-1">
                                                            <strong>Occupation:</strong> {{ $user['occupation'] ?? '' }}
                                                        </p>
                                                        <p class="mb-0">
                                                            <strong>City:</strong> {{ $user['city'] ?? '' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                   {{--Interest - Accepted My Interest--}}
                    <div id="acceptedByOthers" class="profile-section">
                        @if($acceptedMyInterest->isEmpty())
                            <div class="alert alert-info text-center">
                                No Details Found.
                            </div>
                        @else
                            @foreach($acceptedMyInterest as $user)
                                <div class="card mb-3 shadow-sm rounded-3 p-3">
                                    <div class="row align-items-start">

                                        <div class="col-md-4 text-center">
                                            <img src="{{ $user['profile_image'] }}"
                                                 class="img-fluid rounded " alt="Profile Image">
                                        </div>

                                        <div class="col-md-8">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="fw-bold mb-1">{{ $user['name'] ?? '' }}</h6>
                                                    <div class="d-flex gap-2 flex-wrap">
                                                        <i class="bi bi-patch-check-fill gradient-text"></i> <span class="small">ID Verified</span>
                                                        <i class="bi bi-star-fill gradient-text"></i> <span class="small">Premium Member</span>
                                                    </div>
                                                </div>

                                                <div class="text-end">
                                                    <form action="{{ route('addWishlist') }}" method="POST" class="mr-2 mb-2">
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                                        <input type="hidden" name="profile_id" value="{{ $user['id'] }}">
                                                        <button class="btn btn-light btn-sm rounded-circle shadow-sm " type="submit">
                                                            <i class="bi bi-heart fw-bold text-danger"></i>
                                                        </button>
                                                    </form>
                                                    <form id="viewed-profile-form-{{ $user['id'] }}" method="post" action="{{ route('all-profiles.viewedProfiles') }}">
                                                        @csrf
                                                        <input type="hidden" name="viewer_id" value="{{ $user['id'] }}">
                                                        <input type="hidden" name="viewed_id" value="{{ Auth::id() }}">
                                                        <a class="btn button1 rounded-pill btn-sm mb-1 py-2 px-2"
                                                           href="{{ route('all-profiles.show', $user['id']) }}"
                                                           onclick="event.preventDefault(); document.getElementById('viewed-profile-form-{{ $user['id'] }}').submit();">
                                                            View Profile
                                                        </a>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="row g-3 align-items-center mt-3">
                                                <div class="col-md-8">
                                                    <div class="text-start small text-secondary">
                                                        <p class="mb-1">
                                                            <strong>Age:</strong> {{ $user['age'] ?? '' }} |
                                                            <strong>Height:</strong> {{ $user['height'] ?? '' }}
                                                        </p>
                                                        <p class="mb-1">
                                                            <strong>Religion:</strong> {{ $user['religion'] ?? '' }} &nbsp; | &nbsp;
                                                            <strong>Caste:</strong> {{ $user['caste'] ?? '' }}
                                                        </p>
                                                        <p class="mb-1">
                                                            <strong>Occupation:</strong> {{ $user['occupation'] ?? '' }}
                                                        </p>
                                                        <p class="mb-0">
                                                            <strong>City:</strong> {{ $user['city'] ?? '' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    {{--Interest - Denied My Interest--}}
                    <div id="deniedByOthers" class="profile-section">
                        @if($deniedMyInterest->isEmpty())
                            <div class="alert alert-info text-center">
                                No Details Found.
                            </div>
                        @else
                            @foreach($deniedMyInterest as $user)
                                <div class="card mb-3 shadow-sm rounded-3 p-3">
                                    <div class="row align-items-start">

                                        <div class="col-md-4 text-center">
                                            <img src="{{ $user['profile_image'] }}"
                                                 class="img-fluid rounded " alt="Profile Image">
                                        </div>

                                        <div class="col-md-8">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="fw-bold mb-1">{{ $user['name'] ?? '' }}</h6>
                                                    <div class="d-flex gap-2 flex-wrap">
                                                        <i class="bi bi-patch-check-fill gradient-text"></i> <span class="small">ID Verified</span>
                                                        <i class="bi bi-star-fill gradient-text"></i> <span class="small">Premium Member</span>
                                                    </div>
                                                </div>

                                                <div class="text-end">
                                                    <form action="{{ route('addWishlist') }}" method="POST" class="mr-2 mb-2">
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                                        <input type="hidden" name="profile_id" value="{{ $user['id'] }}">
                                                        <button class="btn btn-light btn-sm rounded-circle shadow-sm " type="submit">
                                                            <i class="bi bi-heart fw-bold text-danger"></i>
                                                        </button>
                                                    </form>
                                                    <form id="viewed-profile-form-{{ $user['id'] }}" method="post" action="{{ route('all-profiles.viewedProfiles') }}">
                                                        @csrf
                                                        <input type="hidden" name="viewer_id" value="{{ $user['id'] }}">
                                                        <input type="hidden" name="viewed_id" value="{{ Auth::id() }}">
                                                        <a class="btn button1 rounded-pill btn-sm mb-1 py-2 px-2"
                                                           href="{{ route('all-profiles.show', $user['id']) }}"
                                                           onclick="event.preventDefault(); document.getElementById('viewed-profile-form-{{ $user['id'] }}').submit();">
                                                            View Profile
                                                        </a>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="row g-3 align-items-center mt-3">
                                                <div class="col-md-8">
                                                    <div class="text-start small text-secondary">
                                                        <p class="mb-1">
                                                            <strong>Age:</strong> {{ $user['age'] ?? '' }} |
                                                            <strong>Height:</strong> {{ $user['height'] ?? '' }}
                                                        </p>
                                                        <p class="mb-1">
                                                            <strong>Religion:</strong> {{ $user['religion'] ?? '' }} &nbsp; | &nbsp;
                                                            <strong>Caste:</strong> {{ $user['caste'] ?? '' }}
                                                        </p>
                                                        <p class="mb-1">
                                                            <strong>Occupation:</strong> {{ $user['occupation'] ?? '' }}
                                                        </p>
                                                        <p class="mb-0">
                                                            <strong>City:</strong> {{ $user['city'] ?? '' }}
                                                        </p>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                </div>

            </div>
        </div>
    </section>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.sidebar-button');
            const sections = document.querySelectorAll('.profile-section');

            buttons.forEach(btn => {
                btn.addEventListener('click', function () {
                    buttons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    sections.forEach(sec => sec.classList.remove('active'));
                    const targetId = this.getAttribute('data-target');
                    const targetSection = document.getElementById(targetId);
                    if (targetSection) targetSection.classList.add('active');
                });
            });
        });
    </script>
    @include('web.includes.footer')
@endsection
