@extends('admin.layouts.layout')
@section('title',  'Admin - Members List')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')

        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body border-bottom">
                                    <div class="d-flex align-items-center">
                                        <h5 class="mb-0 card-title flex-grow-1">All Members</h5>
                                    </div>
                                </div>

                                {{-- Search & Filter --}}
                                <div class="card-body border-bottom">
                                    <form action="{{ route('user-list.index') }}" method="GET">
                                        <div class="row">
                                            <div class="col-md-3 mb-2">
                                                <label for="search">Search</label>
                                                <input type="search" name="search" id="search" value="{{ request('search') }}" class="form-control" placeholder="Search id, name, email, mobile">
                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <label for="from_date">From Date</label>
                                                <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control" id="from_date">
                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <label for="to_date">To Date</label>
                                                <input type="date" name="to_date"  value="{{ request('to_date') }}" class="form-control" id="to_date">
                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <label for="status-app">Status</label>
                                                <select name="status" id="status-app" class="form-select">
                                                    <option value="">Select Status</option>
                                                    <option value="active">Approved</option>
                                                    <option value="deactivated">Rejected</option>
                                                    <option value="pending">Pending</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-end">
                                            <div class="col-md-2 ">
                                                <input type="submit" value="Search" class="form-control btn btn-primary">
                                            </div>
                                        </div>
                                    </form>

                                    @if (session('error'))
                                        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                                    @endif
                                </div>

                                {{--End: Search & Filter --}}

                                <div class="card-body">
                                    <div class="row">
                                        @foreach($details['user-list'] as $profile)
                                            <div class="col-sm-6 col-lg-3">
                                                <div class="card text-center shadow-lg">
                                                    <div class="card-body">
                                                        @php
                                                            $defaultImage = asset('asset/img/default/default.png');
                                                            if (isset($profile->gender)) {
                                                                $defaultImage = $profile->gender === 'Male'
                                                                    ? asset('asset/img/default/male.webp')
                                                                    : ($profile->gender === 'Female'
                                                                        ? asset('asset/img/default/female.webp')
                                                                        : $defaultImage);
                                                            }

                                                            $imageSrc = $defaultImage;
                                                            if (!empty($profile->profile_image) && file_exists(public_path('Profile Image/' . $profile->profile_image))) {
                                                                $imageSrc = asset('Profile Image/' . $profile->profile_image);
                                                            }
                                                        @endphp

                                                        <img class="img-thumbnail"
                                                             src="{{ $imageSrc }}"
                                                             alt="{{ $profile->name ?? '' }}"
                                                             style="max-width: 100%; height: 140px;"
                                                        >
                                                        <h5 class="font-size-15 mb-1"><a href="javascript: void(0);" class="text-dark">{{ $profile->name }}</a></h5>
                                                        <p class="text-muted">{{ $profile->mobile }}</p>

                                                        <div>
                                                            <a>
                                                                <span class="badge badge-soft-info p-2">ID : {{$profile->user_id}}</span>
                                                            </a>
                                                            <a>
                                                                @if($profile->status === 'active')
                                                                    <span class="badge badge-soft-success p-2">Approved</span>
                                                                @elseif($profile->status === 'deactivated')
                                                                    <span class="badge badge-soft-danger p-2">Rejected</span>
                                                                @else
                                                                    <span class="badge badge-soft-pink p-2">Pending</span>
                                                                @endif
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer bg-transparent border-top">
                                                        <div class="contact-links d-flex font-size-20">
                                                            <div class="flex-fill">
                                                                <a href="{{ url('admin/user-details/'. $profile->user_id) }}">
                                                                    <i class="mdi mdi-account-circle btn btn-soft-primary">
                                                                        <span class="" style="font-style: normal">
                                                                            view
                                                                        </span>
                                                                    </i>
                                                                </a>
                                                            </div>

                                                            @if(\Illuminate\Support\Facades\Auth::user()->role === 'admin')
                                                            <div class="flex-fill">
                                                                <a href="#" class="" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $profile->user_id }}">
                                                                    <i class="mdi mdi-delete-outline btn btn-soft-danger">
                                                                        <span class="" style="font-style: normal">
                                                                             Delete
                                                                         </span>
                                                                    </i>
                                                                </a>
                                                            </div>
                                                            @endif

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


                    @foreach($details['user-list'] as $profile)
                    <!-- Modal Structure for Deletion Confirmation (Unique per profile) -->
                    <div class="modal fade" id="deleteModal{{ $profile->user_id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $profile->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel{{ $profile->user_id }}">Delete Confirmation</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <!-- Modal Body -->
                                <div class="modal-body">
                                    <p class="text-muted font-size-16 mb-4">Are you sure you want to permanently delete {{ $profile->name }}?</p>
                                </div>

                                <!-- Modal Footer -->
                                <div class="modal-footer">
                                    <!-- Delete Form -->
                                    <form method="POST" action="{{ route('user-list.destroy', $profile->user_id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach


                {{--Pagination start--}}
                <div class="d-flex justify-content-end mt-2">
                    <div class="d-flex justify-content-end mt-2">
                        {{ $details['user-list']->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
                {{--End: Pagination--}}
                </div>
            </div>

        </div>
    </div>

    @include('admin.includes.footer')
@endsection
