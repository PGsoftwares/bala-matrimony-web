@extends('web.layouts.layout')
@section('title', $metaTags->title ?? 'Search Matches')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <section class="container-fluid bg-light-primary">
        <div class="row">

            @include('web.includes.profile-search')

            <div class="col-md-8 mt-md-4 mb-5 shadow-sm bg-white rounded-4">
                <div class="d-flex justify-content-between  py-3 px-2 align-items-center ">
                    <h5 class="fw-medium">Members</h5>
                    <span class="fw-medium text-danger">{{ $profilesCount }}</span>
                </div>

                <div class="row">
                    @if(!empty($appliedFilters))
                        <div class="mb-3">
                            <div class="bg-light border-start border-4 p-3 rounded-3">
                                <h6 class="fw-semibold mb-2 text-dark">
                                    Search Results for <span class="text-danger">({{ $profilesCount }})</span>:
                                </h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($appliedFilters as $filterKey => $filterValue)
                                        <span class="badge button1 px-3 py-2">
                                            {{ ucwords(str_replace('_', ' ', $filterKey)) }}: @if(is_array($filterValue))
                                                {{ implode(', ', $filterValue) }}
                                            @else
                                                {{ $filterValue }}
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                @foreach($profiles as $profile)
                    <div class="card mb-3 shadow-sm rounded-3 p-3">
                        <div class="row align-items-start">

                            <div class="col-md-4 text-center">
                                <img src="{{ $profile->profile_image }}"
                                     class="img-fluid rounded" alt="Profile Image" style="width: 100%; height: 250px; object-fit: cover">
                            </div>

                            <div class="col-md-8">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $profile->name ?? '' }}</h6>
                                        <small class="text-muted d-block">ID: BMB{{$profile->user_id}}</small>
                                        <div class="d-flex gap-2 flex-wrap">
                                            @if(!empty($profile->email_verified_at))
                                                <i class="bi bi-patch-check-fill gradient-text"></i>
                                                <span class="small">ID Verified</span>
                                            @endif
                                            @if(!empty($profile->package))
                                                <i class="bi bi-star-fill gradient-text"></i>
                                                <span class="small">Premium Member</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <form action="{{ route('addWishlist') }}" method="POST" class="mr-2 mb-2">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                            <input type="hidden" name="profile_id" value="{{ $profile->user_id }}">
                                            <button class="btn btn-light btn-sm rounded-circle shadow-sm " type="submit">
                                                <i class="bi bi-heart fw-bold text-danger"></i>
                                            </button>
                                        </form>
                                        <form id="viewed-profile-form-{{ $profile->user_id }}" method="post" action="{{ route('all-profiles.viewedProfiles') }}">
                                            @csrf
                                            <input type="hidden" name="viewer_id" value="{{ $profile->user_id }}">
                                            <input type="hidden" name="viewed_id" value="{{ Auth::id() }}">
                                            <a class="btn button1 rounded-pill btn-sm mb-1 py-2 px-2"
                                               href="{{ route('all-profiles.show', $profile->user_id) }}"
                                               onclick="event.preventDefault(); document.getElementById('viewed-profile-form-{{ $profile->user_id }}').submit();">
                                                View Profile
                                            </a>
                                        </form>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <p class="mb-1 small">Age: {{ $profile->age ?? '' }} | Height: {{ $profile->height ?? '' }}</p>
                                    <p class="mb-1 small">Religion: {{ $profile->religion ?? '' }} | Caste: {{ $profile->caste ?? '' }}</p>
                                    <p class="mb-1 small">{{ $profile->occupation ?? '' }}</p>
                                    <p class="mb-0 small">City: {{ $profile->city ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{--Pagination--}}
                <div class="d-flex justify-content-center mt-4">
                    {!! $profiles->links('pagination::bootstrap-5') !!}
                </div>
            </div>

        </div>

    </section>


    <script>
        const authUserGender = "{{ strtolower($userAndUserDetails->gender) }}";
        const savedMinAge = null;
        const savedMaxAge = null;
    </script>
    @include('web.includes.footer')
@endsection
