@extends('admin.layouts.layout')

@section('title',  'Admin - Family God')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">

                    <div class="row">
                        <!-- Create Family god Form -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Create Family God</h5>

                                    <form method="post" action="{{ route('family-god.store') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label" for="family_god-input">Name</label>
                                            <input type="text" class="form-control" name="family_god" id="family_god-input" placeholder="Enter family god...">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="visibility">Visibility</label>
                                            <select class="form-select" name="admin_status" id="visibility">
                                                <option value="1">Visible</option>
                                                <option value="0">Not Visible</option>
                                            </select>
                                        </div>

                                        <div class="text-end">
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Create Family God Form -->

                        <!-- Family God Table -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-3">Family God</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered rounded mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Family God</th>
                                                <th>Visibility</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($family_gods as $index => $family_god)
                                                <tr>
                                                    <th scope="row">{{ method_exists($family_gods, 'firstItem') ? $family_gods->firstItem() + $index : $index + 1 }}</th>
                                                    <td>{{ $family_god->family_god }}</td>
                                                    <td>{{ $family_god->admin_status ? 'Visible' : 'Not Visible' }}</td>
                                                    <td>
                                                        <!-- Edit Button -->
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal-{{ $family_god->id }}">Edit</button>
                                                        <!-- Delete Button -->
                                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $family_god->id }}">Delete</button>
                                                    </td>
                                                </tr>

                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal-{{ $family_god->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $family_god->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel-{{ $family_god->id }}">Edit Caste</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST" action="{{ route('family-god.update', $family_god->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-family_god">Name</label>
                                                                        <input type="text" class="form-control" id="edit-family_god" name="family_god" value="{{ $family_god->family_god }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-visibility">Visibility</label>
                                                                        <select class="form-select" id="edit-visibility" name="admin_status">
                                                                            <option value="1" {{ $family_god->admin_status ? 'selected' : '' }}>Visible</option>
                                                                            <option value="0" {{ !$family_god->admin_status ? 'selected' : '' }}>Not Visible</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Edit Modal -->

                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal-{{ $family_god->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $family_god->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel-{{ $family_god->id }}">Delete Family God</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete the family god "{{ $family_god->family_god }}"?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <form action="{{ route('family-god.destroy', $family_god->id) }}" method="POST" style="display: inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Delete Modal -->
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Pagination Links -->
                                    <div class="d-flex justify-content-end mt-2">
                                        {{ $family_gods->links('vendor.pagination.bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Family God Table -->
                    </div> <!-- row -->

                </div> <!-- container-fluid -->
            </div> <!-- page-content -->
        </div> <!-- main-content -->
        @include('admin.includes.footer')
    </div> <!-- layout-wrapper -->
@endsection

