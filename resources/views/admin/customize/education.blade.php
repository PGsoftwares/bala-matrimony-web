@extends('admin.layouts.layout')
@section('title', 'Education')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Create Education</h5>

                                    <form method="post" action="{{ route('education.store') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label" for="education-level-select">Qualification</label>
                                            <select class="form-select" name="education_level_id" id="education-level-select">
                                                @foreach($education_levels as $education_level)
                                                    <option value="{{ $education_level->id }}">{{ $education_level->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="education-input">Study</label>
                                            <input type="text" class="form-control" name="name" id="education-input" placeholder="Enter Education...">
                                        </div>

                                        <div class="text-end">
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-3">Educations</h4>
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif

                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif
                                    <div class="table-responsive">
                                        <table class="table table-bordered rounded mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Qualification</th>
                                                <th>Education</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($educations as $index => $education)
                                                <tr>
                                                    <th scope="row">{{ method_exists($educations, 'firstItem') ? $educations->firstItem() + $index : $index + 1 }}</th>
                                                    <td>{{ $education->qualification_name ?? $education->education_level_id }}</td>
                                                    <td>{{ $education->name }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal-{{ $education->id }}">Edit</button>
                                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $education->id }}">Delete</button>
                                                    </td>
                                                </tr>

                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal-{{ $education->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $education->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel-{{ $education->id }}">Edit Education</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST" action="{{ route('education.update', $education->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-education-select-{{ $education->id }}">Qualification</label>
                                                                        <select class="form-select" id="edit-education-select-{{ $education->id }}" name="education_level_id">
                                                                            @foreach($education_levels as $education_level)
                                                                                <option value="{{ $education_level->id }}" {{ $education_level->id == $education->education_level_id ? 'selected' : '' }}>{{ $education_level->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-study-{{ $education->id }}">Study</label>
                                                                        <input type="text" class="form-control" id="edit-study-{{ $education->id }}" name="name" value="{{ $education->name }}">
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
                                                <div class="modal fade" id="deleteModal-{{ $education->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $education->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel-{{ $education->id }}">Delete Education</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete the education "{{ $education->name }}"?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <form action="{{ route('education.destroy', $education->id) }}" method="POST" style="display: inline;">
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
                                        {{ $educations->links('vendor.pagination.bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
        @include('admin.includes.footer')
    </div>
@endsection
