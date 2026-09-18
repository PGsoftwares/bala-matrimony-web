@extends('admin.layouts.layout')
@section('title', 'Highlighted Profiles')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="row">
                        <!-- Create Highlighted Profiles Form -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Create Highlighted Profile</h5>

                                    <form method="post" action="{{ route('highlighted-profiles.store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label" for="highlighted-profile">Profile</label>
                                            <select class="form-control" name="user_id" id="highlighted-profile">
                                                <option value="" disabled selected>Select a user</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }} , {{ $user->mobile }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="text-end">
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Create Highlighted Profiles Form -->

                        <!-- Highlighted Profiles Cards -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-3">Highlighted Profiles</h4>
                                    <div class="row">
                                        @foreach($highlighted_profiles as $highlighted_profile)
                                            <div class="col-md-12 mb-3">
                                                <div class="card border border-warning">
                                                    <div class="row g-0">
                                                        <div class="col-md-4">
                                                            <div class="img-container">
                                                                <img src="{{ asset('Profile Image/' . $highlighted_profile->profile_image) }}" style="max-width: 100%; height: 200px" class="img-fluid rounded-start" alt="...">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="card-body">
                                                                <p class="card-text">Name: {{ $highlighted_profile->name }}</p>
                                                                <p class="card-text">Email: {{ $highlighted_profile->email }}</p>
                                                                <p class="card-text">Mobile: {{ $highlighted_profile->mobile }}</p>
                                                                <!-- Add other fields as necessary -->
                                                                <div class="d-flex justify-content-end">
                                                                    <!-- Edit Button -->
{{--                                                                    <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editModal-{{ $highlighted_profile->highlighted_id }}">Edit</button>--}}
                                                                    <!-- Delete Button -->
                                                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $highlighted_profile->highlighted_id }}">Delete</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Highlighted Profiles Cards -->
                    </div> <!-- row -->

                    <!-- Delete Modal -->
                    @foreach($highlighted_profiles as $highlighted_profile)
                        <div class="modal fade" id="deleteModal-{{ $highlighted_profile->highlighted_id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $highlighted_profile->highlighted_id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel-{{ $highlighted_profile->highlighted_id }}">Delete Highlighted Profile</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete the Highlighted profile "{{ $highlighted_profile->name }}"?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <form action="{{ route('highlighted-profiles.destroy', $highlighted_profile->highlighted_id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <!-- End Delete Modal -->

                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-end">
                        {{ $highlighted_profiles->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div> <!-- container-fluid -->
            </div> <!-- page-content -->
        </div> <!-- main-content -->
        @include('admin.includes.footer')
    </div> <!-- layout-wrapper -->
@endsection
