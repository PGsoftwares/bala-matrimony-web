@extends('web.layouts.layout')
@section('title', $metaTags->title ?? 'Wishlists')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <div class="bg-light-primary min-vh-100 py-5">
        <section class="container">
            <div class="p-3 shadow-sm bg-white rounded-2">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-medium">Wishlist</h5>
                    <span class="fw-medium text-danger"></span>
                </div>

                <div class="row g-4">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success')  }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error')  }}</div>
                    @endif

                    @forelse($wishlistProfiles as $wishlistProfile)
                        <div class="col-md-6">
                            <div class="card shadow-sm rounded-3 p-3">
                                <div class="row g-3 align-items-start">

                                    <div class="col-md-4 text-center">
                                        <img src="{{ $wishlistProfile->profile_image }}"
                                             class="img-fluid rounded" alt="Profile Image" style="width: 100%; height: 200px; object-fit: cover">
                                    </div>

                                    <div class="col-md-8">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="fw-bold mb-1">{{ $wishlistProfile->name ?? '' }}</h6>
                                                {{--                                        <small class="text-muted d-block">User ID: HN12345</small>--}}
                                                <div class="d-flex gap-2 flex-wrap">
                                                    <i class="bi bi-patch-check-fill gradient-text"></i> <span class="small">ID Verified</span>
                                                    <i class="bi bi-star-fill gradient-text"></i> <span class="small">Premium Member</span>
                                                </div>
                                            </div>

                                            <div class="text-end">

                                                <form method="post" action="{{ route('wishlist.remove') }}" id="remove-wishlist-{{ $wishlistProfile->profile_id }}">
                                                    @csrf
                                                    <input type="hidden" name="profile_id" value="{{ $wishlistProfile->profile_id }}">
                                                    <button type="submit" class="btn button2 btn-sm rounded-pill mb-2">Remove</button>
                                                </form>

                                                <form id="viewed-profile-form-{{ $wishlistProfile->profile_id }}" method="post" action="{{ route('all-profiles.viewedProfiles') }}">
                                                    @csrf
                                                    <input type="hidden" name="viewer_id" value="{{ $wishlistProfile->profile_id }}">
                                                    <input type="hidden" name="viewed_id" value="{{ Auth::id() }}">

                                                    <a class="btn btn-sm button1 rounded-pill"
                                                       href="{{ route('all-profiles.show', ['all_profile' => $wishlistProfile->profile_id]) }}"
                                                       onclick="event.preventDefault(); document.getElementById('viewed-profile-form-{{ $wishlistProfile->profile_id }}').submit();">
                                                        <i class="icon-file-text mr-2"> </i>View Profile
                                                    </a>
                                                </form>

                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <p class="mb-1 small">Age: {{ $wishlistProfile->age ?? '' }} | Height: {{ $wishlistProfile->height ?? '' }}</p>
                                            <p class="mb-1 small">Religion: {{ $wishlistProfile->religion ?? '' }} | Caste: {{ $wishlistProfile->caste ?? '' }}</p>
                                            <p class="mb-1 small">{{ $wishlistProfile->occupation ?? '' }}</p>
                                            <p class="mb-0 small">City: {{ $wishlistProfile->city ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                            <div class="d-flex justify-content-center align-items-center vh-100 w-100">
                                <div class="alert alert-info text-center">
                                    No profiles found in your wishlist.
                                </div>
                            </div>
                        @endforelse
                </div>

            </div>
        </section>
    </div>


    @include('web.includes.footer')
@endsection
