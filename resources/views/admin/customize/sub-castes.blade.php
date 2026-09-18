@extends('admin.layouts.layout')

@section('title',  'Admin - Subcastes')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">

                    <div class="row">
                        <!-- Create Sub-Caste Form -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Create Sub-Caste</h5>

                                    <form method="post" action="{{ route('sub-castes.store') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label" for="caste-select">Caste</label>
                                            <select class="form-select" name="caste_id" id="caste-select">
                                                @foreach($castes as $caste)
                                                    <option value="{{ $caste->id }}">{{ $caste->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="sub-caste-input">Sub-Caste Name</label>
                                            <input type="text" class="form-control" name="name" id="sub-caste-input" placeholder="Enter sub-caste...">
                                        </div>


                                        <div class="text-end">
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Create Sub-Caste Form -->

                        <!-- Sub-Castes Table -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-3">Sub-Castes</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered rounded mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Caste</th>
                                                <th>Sub-Caste</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($subCastes as $index => $subCaste)
                                                <tr>
                                                    <th scope="row">{{ method_exists($subCastes, 'firstItem') ? $subCastes->firstItem() + $index : $index + 1 }}</th>
                                                    <td>{{ $subCaste->caste_name ?? $subCaste->caste_id }}</td>
                                                    <td>{{ $subCaste->name }}</td>
                                                    <td>
                                                        <!-- Edit Button -->
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal-{{ $subCaste->id }}">Edit</button>
                                                        <!-- Delete Button -->
                                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $subCaste->id }}">Delete</button>
                                                    </td>
                                                </tr>

                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal-{{ $subCaste->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $subCaste->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel-{{ $subCaste->id }}">Edit Sub-Caste</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST" action="{{ route('sub-castes.update', $subCaste->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-caste-select-{{ $subCaste->id }}">Caste</label>
                                                                        <select class="form-select" id="edit-caste-select-{{ $subCaste->id }}" name="caste_id">
                                                                            @foreach($castes as $caste)
                                                                                <option value="{{ $caste->id }}" {{ $caste->id == $subCaste->caste_id ? 'selected' : '' }}>{{ $caste->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-sub-caste-{{ $subCaste->id }}">Sub-Caste Name</label>
                                                                        <input type="text" class="form-control" id="edit-sub-caste-{{ $subCaste->id }}" name="name" value="{{ $subCaste->name }}">
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
                                                <div class="modal fade" id="deleteModal-{{ $subCaste->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $subCaste->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel-{{ $subCaste->id }}">Delete Sub-Caste</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete the sub-caste "{{ $subCaste->name }}"?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <form action="{{ route('sub-castes.destroy', $subCaste->id) }}" method="POST" style="display: inline;">
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
                                        <!-- Pagination Links -->
                                        <div class="d-flex justify-content-end mt-2">
                                            {{ $subCastes->links('vendor.pagination.bootstrap-5') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Sub-Castes Table -->
                    </div> <!-- row -->

                </div> <!-- container-fluid -->
            </div> <!-- page-content -->
        </div> <!-- main-content -->
        @include('admin.includes.footer')
    </div> <!-- layout-wrapper -->
@endsection
