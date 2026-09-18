@extends('admin.layouts.layout')
@section('title',  'All Users')

@section('content')
    <div id="layout-wrapper">
        {{--        Header --}}
        @include('admin.includes.header')
        @include('admin.includes.sidebar')

        {{--        Main content--}}
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body border-bottom">
                                    <div class="d-flex align-items-center">
                                        <h5 class="mb-0 card-title flex-grow-1">
                                            @if(request('gender') == 'male')
                                                Male Users
                                            @elseif(request('gender') == 'female')
                                                Female Users
                                            @elseif(request('today'))
                                                Today's Users
                                            @elseif(request('from_date') == now()->startOfMonth()->toDateString() && request('to_date') == now()->endOfMonth()->toDateString())
                                                This Month's Users
                                            @elseif(request('from_date') == now()->startOfYear()->toDateString() && request('to_date') == now()->endOfYear()->toDateString())
                                                This Year's Users
                                            @elseif(request('package'))
                                                {{ ucfirst(request('package')) }} Users
                                            @else
                                                All Users
                                            @endif
                                        </h5>

                                    </div>
                                </div>

                                {{--End: Search & Filter --}}

                                <div class="card-body">
                                    {{--Search form--}}
                                    <form class="border-bottom mb-2" action="{{ route('allUsers') }}" method="GET">
                                        <div class="row align-items-end">
                                            <!-- Filter Type Dropdown -->
                                            <div class="col-md-4 mb-3">
                                                <label for="filter_type" class="form-label">Filter By</label>
                                                <select id="filter_type" class="form-select" name="filter_type" onchange="toggleSearchField()">
                                                    <option value="">Select Filter</option>
                                                    <option value="id" {{ request('filter_type') == 'id' ? 'selected' : '' }}>ID</option>
                                                    <option value="name" {{ request('filter_type') == 'name' ? 'selected' : '' }}>Name</option>
                                                    <option value="email" {{ request('filter_type') == 'email' ? 'selected' : '' }}>Email</option>
                                                    <option value="mobile" {{ request('filter_type') == 'mobile' ? 'selected' : '' }}>Mobile</option>
                                                </select>
                                            </div>

                                            <!-- Search Box (Dynamic) -->
                                            <div class="col-md-4 mb-3">
                                                <label for="search" class="form-label">Search</label>
                                                <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control" placeholder="Enter search term">
                                            </div>

                                            <!-- Search Button -->
                                            <div class="col-md-2 mb-3">
                                                <button type="submit" class="btn btn-primary w-100">Search</button>
                                            </div>
                                        </div>
                                    </form>

                                    <script>
                                        function toggleSearchField() {
                                            let filterType = document.getElementById('filter_type').value;
                                            let searchBox = document.getElementById('search');

                                            // Change input type and placeholder dynamically
                                            if (filterType === "id") {
                                                searchBox.type = "number";
                                                searchBox.placeholder = "Enter ID";
                                            } else if (filterType === "name") {
                                                searchBox.type = "text";
                                                searchBox.placeholder = "Enter Name";
                                            } else if (filterType === "email") {
                                                searchBox.type = "email";
                                                searchBox.placeholder = "Enter Email";
                                            } else if (filterType === "mobile") {
                                                searchBox.type = "text";
                                                searchBox.placeholder = "Enter Mobile";
                                            } else {
                                                searchBox.type = "text";
                                                searchBox.placeholder = "Enter search term";
                                            }
                                        }

                                        // Apply on page load
                                        document.addEventListener("DOMContentLoaded", function () {
                                            toggleSearchField();
                                        });
                                    </script>
                                    {{--End: Search form--}}

                                    <div class="row">
                                        @foreach($details['users'] as $profile)
                                            <div class="col-sm-6 col-lg-3">
                                                <div class="card text-center shadow-lg">
                                                    <div class="card-body">
                                                        <div class="mb-4">
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
                                                        </div>
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


                    @foreach($details['users'] as $profile)
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
                        {{ $details['users']->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
                    </div>
                    {{--End: Pagination--}}
                </div>
            </div>

            {{-- Footer --}}
            @include('admin.includes.footer')
        </div>
    </div>
@endsection
