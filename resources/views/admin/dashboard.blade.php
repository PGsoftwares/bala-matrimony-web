@extends('admin.layouts.layout')
@section('title', 'Admin Dashboard')

@section('content')
    <div id="layout-wrapper">
        <!-- Loader -->
        <div id="preloader">
            <div id="status">
                <div class="spinner-chase">
                    <div class="chase-dot"></div>
                    <div class="chase-dot"></div>
                    <div class="chase-dot"></div>
                    <div class="chase-dot"></div>
                    <div class="chase-dot"></div>
                    <div class="chase-dot"></div>
                </div>
            </div>
        </div>

        @include('admin.includes.header')
        @include('admin.includes.sidebar')

        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <h5 class="card-title mb-4">Members</h5>
                                <div class="col-md-4">
                                    <div class="card custom-card main-card-item primary">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-2 flex-wrap">
                                                <div> <span class="d-block mb-2 fw-medium">Total users</span>
                                                    <h3 class="fw-semibold lh-1 mb-0">{{ $totalStandard }}</h3>
                                                </div>
                                                <div class="text-end">
                                                    <div class="mb-2">
                                                        <div class="mini-stat-icon avatar-sm rounded-circle">
                                                            <span class="avatar-title rounded-circle bg-light text-primary">
                                                                <i class='bx bxs-user-rectangle font-size-24'></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3 font-size-12">
                                                <a href="{{ url('admin/users?status=active') }}" class="text-success fw-semibold">Active: {{ $totalActive }}</a> | 
                                                <a href="{{ url('admin/users?status=pending') }}" class="text-warning fw-semibold">Pending: {{ $totalPending }}</a> | 
                                                <a href="{{ url('admin/users?status=deactivated') }}" class="text-danger fw-semibold">Inactive: {{ $totalInactive }}</a>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <a href="{{ url('admin/users') }}" class="text-muted text-decoration-underline fw-medium fs-13">View all</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card custom-card main-card-item primary">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-2 flex-wrap">
                                                <div> <span class="d-block mb-2 fw-medium">Male users</span>
                                                    <h3 class="fw-semibold lh-1 mb-0">{{ $totalStandardMale }}</h3>
                                                </div>
                                                <div class="text-end">
                                                    <div class="mb-2">
                                                        <div class="mini-stat-icon avatar-sm rounded-circle">
                                                            <span class="avatar-title rounded-circle bg-light text-primary">
                                                                <i class='bx bx-male font-size-24'></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3 font-size-12">
                                                <a href="{{ url('admin/users?gender=male&status=active') }}" class="text-success fw-semibold">Active: {{ $totalMaleActive }}</a> | 
                                                <a href="{{ url('admin/users?gender=male&status=pending') }}" class="text-warning fw-semibold">Pending: {{ $totalMalePending }}</a> | 
                                                <a href="{{ url('admin/users?gender=male&status=deactivated') }}" class="text-danger fw-semibold">Inactive: {{ $totalMaleInactive }}</a>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <a href="{{ url('admin/users?gender=male') }}" class="text-muted text-decoration-underline fw-medium fs-13">View all</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card custom-card main-card-item primary">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-2 flex-wrap">
                                                <div> <span class="d-block mb-2 fw-medium">Female users</span>
                                                    <h3 class="fw-semibold lh-1 mb-0">{{ $totalStandardFemale }}</h3>
                                                </div>
                                                <div class="text-end">
                                                    <div class="mb-2">
                                                        <div class="mini-stat-icon avatar-sm rounded-circle">
                                                            <span class="avatar-title rounded-circle bg-light text-primary">
                                                                <i class='bx bx-female font-size-24'></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3 font-size-12">
                                                <a href="{{ url('admin/users?gender=female&status=active') }}" class="text-success fw-semibold">Active: {{ $totalFemaleActive }}</a> | 
                                                <a href="{{ url('admin/users?gender=female&status=pending') }}" class="text-warning fw-semibold">Pending: {{ $totalFemalePending }}</a> | 
                                                <a href="{{ url('admin/users?gender=female&status=deactivated') }}" class="text-danger fw-semibold">Inactive: {{ $totalFemaleInactive }}</a>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <a href="{{ url('admin/users?gender=female') }}" class="text-muted text-decoration-underline fw-medium fs-13">View all</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="card custom-card main-card-item primary">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3 flex-wrap">
                                                <div> <span class="d-block mb-3 fw-medium">Today users</span>
                                                    <h3 class="fw-semibold lh-1 mb-0">{{ $todayUser }}</h3>
                                                </div>
                                                <div class="text-end">
                                                    <div class="mb-4">
                                                        <div class="mini-stat-icon avatar-sm rounded-circle">
                                                            <span class="avatar-title rounded-circle bg-light text-primary">
                                                                <i class='bx bx-user-plus font-size-24'></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <a href="{{ url('admin/users?today=' . now()->toDateString()) }}" class="text-muted text-decoration-underline fw-medium fs-13">View all</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card custom-card main-card-item primary">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3 flex-wrap">
                                                <div> <span class="d-block mb-3 fw-medium">This month users</span>
                                                    <h3 class="fw-semibold lh-1 mb-0">{{ $thisMonth }}</h3>
                                                </div>
                                                <div class="text-end">
                                                    <div class="mb-4">
                                                        <div class="mini-stat-icon avatar-sm rounded-circle">
                                                            <span class="avatar-title rounded-circle bg-light text-primary">
                                                                <i class='bx bx-group font-size-24'></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <a href="{{ url('admin/users?from_date=' . now()->startOfMonth()->toDateString() . '&to_date=' . now()->endOfMonth()->toDateString()) }}" class="text-muted text-decoration-underline fw-medium fs-13">View all</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card custom-card main-card-item primary">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start justify-content-between mb-3 flex-wrap">
                                                <div> <span class="d-block mb-3 fw-medium">This year users</span>
                                                    <h3 class="fw-semibold lh-1 mb-0">{{ $thisYear }}</h3>
                                                </div>
                                                <div class="text-end">
                                                    <div class="mb-4">
                                                        <div class="mini-stat-icon avatar-sm rounded-circle">
                                                            <span class="avatar-title rounded-circle bg-light text-primary">
                                                                <i class='bx bx-group font-size-24'></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <a href="{{ url('admin/users?from_date=' . now()->startOfYear()->toDateString() . '&to_date=' . now()->endOfYear()->toDateString()) }}" class="text-muted text-decoration-underline fw-medium fs-13">View all</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h5 class="card-title mb-4">Membership</h5>

                                @foreach ($packages as $package)
                                    <div class="col-md-4">
                                        <div class="card custom-card main-card-item primary">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start justify-content-between mb-3 flex-wrap">
                                                    <div>
                                                        <span class="d-block mb-3 fw-medium">{{ $package->name }} users</span>
                                                        <h3 class="fw-semibold lh-1 mb-0">{{ $membershipCounts[$package->name] }}</h3>
                                                    </div>
                                                    <div class="text-end">
                                                        <div class="mb-4">
                                                            <div class="mini-stat-icon avatar-sm rounded-circle">
                                                                <span class="avatar-title rounded-circle bg-light text-primary">
                                                                    <i class='bx bx-group font-size-24'></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <a href="{{ url('admin/users?package=' . $package->name) }}" class="text-muted text-decoration-underline fw-medium fs-13">View all</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('admin.includes.footer')
@endsection
