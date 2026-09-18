@extends('admin.layouts.layout')
@section('title', 'Admin - Occupation')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="row">
                        <!-- Create Occupation Form -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Create Occupation</h5>

                                    <form method="post" action="{{ route('occupation.store') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label" for="occupation-level-select">Occupation Type</label>
                                            <select class="form-select" name="occupation_type_id" id="occupation-level-select">
                                                @foreach($occupation_types as $occupation_type)
                                                    <option value="{{ $occupation_type->id }}">{{ $occupation_type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="occupation-input">Occupation</label>
                                            <input type="text" class="form-control" name="name" id="occupation-input" placeholder="Enter Occupation...">
                                        </div>


                                        <div class="text-end">
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Create Occupation Form -->

                        <!-- Occupation Table -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-3">Occupations</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered rounded mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Occupation Type</th>
                                                <th>Occupation</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($occupations as $index => $occupation)
                                                <tr>
                                                    <th scope="row">{{ method_exists($occupations, 'firstItem') ? $occupations->firstItem() + $index : $index + 1 }}</th>
                                                    <td>{{ $occupation->occupation_type_name ?? $occupation->occupation_type_id }}</td>
                                                    <td>{{ $occupation->name }}</td>
                                                    <td>
                                                        <!-- Edit Button -->
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal-{{ $occupation->id }}">Edit</button>
                                                        <!-- Delete Button -->
                                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $occupation->id }}">Delete</button>
                                                    </td>
                                                </tr>

                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal-{{ $occupation->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $occupation->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel-{{ $occupation->id }}">Edit Occupation</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST" action="{{ route('occupation.update', $occupation->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-occupation-select-{{ $occupation->id }}">Occupation Type</label>
                                                                        <select class="form-select" id="edit-occupation-select-{{ $occupation->id }}" name="occupation_type_id">
                                                                            @foreach($occupation_types as $occupation_type)
                                                                                <option value="{{ $occupation_type->id }}" {{ $occupation_type->id == $occupation->occupation_type_id ? 'selected' : '' }}>{{ $occupation_type->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-study-{{ $occupation->id }}">Occupation</label>
                                                                        <input type="text" class="form-control" id="edit-study-{{ $occupation->id }}" name="name" value="{{ $occupation->name }}">
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
                                                <div class="modal fade" id="deleteModal-{{ $occupation->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $occupation->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel-{{ $occupation->id }}">Delete Occupation</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete the occupation "{{ $occupation->name }}"?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <form action="{{ route('occupation.destroy', $occupation->id) }}" method="POST" style="display: inline;">
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
                                        {{ $occupations->links('vendor.pagination.bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Occupation Table -->
                    </div> <!-- row -->

                </div> <!-- container-fluid -->
            </div> <!-- page-content -->
        </div> <!-- main-content -->
        @include('admin.includes.footer')
    </div> <!-- layout-wrapper -->
@endsection
