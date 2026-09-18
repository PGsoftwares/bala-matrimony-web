@extends('web.layouts.layout')

@section('title', $metaTags->title ?? '')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <section id="page-content" class="sidebar-both" style="padding: 30px 0 0 0">
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                @include('web.includes.search_ai')

                <!-- Content -->
                <div class="content col-sm-8 col-lg-9">
                    <h4 class="mb-4 theme-bg bg-white shadow p-10 text-light">
                        AI Match Profiles ({{ $profilesCount }})
                    </h4>

                    @if($profiles->isEmpty())
                        <div class="d-flex justify-content-center">
                            <p class="alert alert-info">Search to view your profile match percentage and discover how compatible you are with others!</p>
                        </div>

                    @else
                        <!-- Tabs for Matching Percentages -->
                        <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
                            @foreach(['100' => '100% Match', '75' => '75% Match', '50' => '50% Match', '25' => '25% Match'] as $percent => $label)
                                <li class="nav-item">
                                    <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $percent }}-tab" data-toggle="tab" href="#tab-{{ $percent }}" role="tab" aria-controls="tab-{{ $percent }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                        {{ $label }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="profileTabsContent">
                            @php
                                $matches = [
                                    '100' => $profiles->where('match_percentage', 100),
                                    '75' => $profiles->whereBetween('match_percentage', [75, 99]),
                                    '50' => $profiles->whereBetween('match_percentage', [50, 74]),
                                    '25' => $profiles->whereBetween('match_percentage', [25, 49]),
                                ];
                            @endphp

                            @foreach($matches as $percent => $group)
                                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $percent }}" role="tabpanel" aria-labelledby="{{ $percent }}-tab">
                                    @if($group->isEmpty())
                                        <div class="d-flex justify-content-center">
                                            <p class="alert alert-info">AI Matching Profiles Not Found for {{ $percent }}% Match</p>
                                        </div>
                                    @else
                                        <div class="row team-members team-members-left team-members-shadow m-b-40">
                                            @foreach($group as $profile)
                                                <div class="col-lg-6">
                                                    <div class="team-member">
                                                        <div class="team-image">
                                                            <a href="{{ $isPackageValid ? route('all-profiles.show', $profile->user_id) : url('package-404') }}">
                                                                @if(empty($profile->profile_picture_visibility) || $profile->profile_picture_visibility == 3)
                                                                    <img src="{{ asset('Profile Image/' . $profile->profile_image) }}" alt="" class="profile img-fluid" style="height: 215px;">
                                                                @else
                                                                    <img src="{{ asset('web/assets/default/default-locked.png') }}" alt="" class="profile img-fluid" style="height: 215px;">
                                                                @endif
                                                            </a>
                                                        </div>
                                                        <div class="team-desc p-3">
                                                            <h3>{{ $profile->name ?? '----' }}</h3>
                                                            <p>Age: {{ $profile->age ?? '----' }}</p>
                                                            <p>Occupation: {{ $profile->occupation_type ?? '----' }}</p>
                                                            <p>City: {{ $profile->city ?? '----' }}</p>
                                                            <div class="d-flex justify-content-start align-items-end">
                                                                <form action="{{ route('addWishlist') }}" method="POST" class="mr-2">
                                                                    @csrf
                                                                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                                                    <input type="hidden" name="profile_id" value="{{ $profile->user_id }}">
                                                                    <button class="btn btn-success" type="submit">
                                                                        <i class="fa fa-heart"></i> Add to Wishlist
                                                                    </button>
                                                                </form>
                                                                <a class="btn theme-btn text-light" href="{{ $isPackageValid ? route('all-profiles.show', $profile->user_id) : route('package-404') }}">
                                                                    <i class="icon-file-text mr-2"></i> View Profile
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @include('web.includes.footer')
@endsection
