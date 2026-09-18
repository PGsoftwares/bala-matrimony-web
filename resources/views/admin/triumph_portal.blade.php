@extends('admin.layouts.layout')
@section('title', 'User Logs')

@section('content')
    @include('admin.includes.header')
    @include('admin.includes.sidebar')

    <div class="main-content background_color">
        <div class="page-content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            {{-- User list --}}
                            <h4 class="mb-3">Logs</h4>
                            <div class="col-md-12">
                                <form method="GET" action="{{ route('triumph.portal') }}" class="row g-3 mb-4">
                                    <div class="col-md-2">
                                        <label for="search" class="form-label">User ID</label>
                                        <input type="text" name="search" class="form-control" id="search" placeholder="Search ID" value="{{ request('search') }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="mobile" class="form-label">Mobile</label>
                                        <input type="text" name="mobile" class="form-control" id="mobile" placeholder="Search Mobile" value="{{ request('mobile') }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                                        <input type="date" name="dob" class="form-control" id="date_of_birth" value="{{ request('dob') }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="from_date" class="form-label">From Date</label>
                                        <input type="date" name="from_date" class="form-control" id="from_date" value="{{ request('from_date') }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="to_date" class="form-label">To Date</label>
                                        <input type="date" name="to_date" class="form-control" id="to_date" value="{{ request('to_date') }}">
                                    </div>

                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">Search</button>
                                    </div>
                                </form>
                            </div>


                            <div class="col-md-3">

                                <div class="list-group overflow-auto" style="max-height: 550px;">
                                    @if($users->isEmpty())
                                        <p>No users found.</p>
                                    @else
                                        @foreach ($users as $user)
                                            <a class="list-group-item list-group-item-action {{ isset($selectedUser) && $selectedUser->user_id === $user->id ? 'active' : '' }}"
                                               href="{{ route('triumph.portal.user', $user->id) }}">
                                                BMB000{{ $user->id }}
                                            </a>
                                        @endforeach
                                    @endif

                                </div>
                            </div>

                            {{-- Selected user details --}}
                            <div class="col-md-9">
                                @if(isset($selectedUser))
                                    @include('admin.triumph_portal_user', [
                                        'user' => $selectedUser,
                                        'dates' => $dates,
                                        'history' => $history
                                    ])
                                @else
                                    <div class="d-flex justify-content-center align-items-center vh-100">
                                        <p class="alert alert-success">Select a user to view details.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.includes.footer')
@endsection
