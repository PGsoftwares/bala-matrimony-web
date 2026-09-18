@extends('admin.layouts.layout')
@section('title', 'Nationality')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="row">
                        <!-- Create Form -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Add Nationality</h5>

                                    <form method="post" action="{{ route('nationality.store') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label" for="nationality">Nationality</label>
                                            <input type="text" class="form-control" name="nationality" id="nationality" placeholder="Enter Nationality">
                                        </div>
                                        <div class="text-end">
                                            <button class="btn btn-primary" type="submit">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Form -->

                        <!-- Table -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-3">Nationalities</h4>

                                    @if(session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif

                                    @if(session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif

                                    <div class="table-responsive">
                                        <table class="table table-bordered rounded mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nationality</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($nationalities as $index => $nationality)
                                                <tr>
                                                    <th scope="row">{{ method_exists($nationalities, 'firstItem') ? $nationalities->firstItem() + $index : $index + 1 }}</th>
                                                    <td>{{ $nationality->name }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-{{ $nationality->id }}">Edit</button>
                                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $nationality->id }}">Delete</button>
                                                    </td>
                                                </tr>

                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal-{{ $nationality->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $nationality->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel-{{ $nationality->id }}">Edit Nationality</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST" action="{{ route('nationality.update', $nationality->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-nationality">Nationality</label>
                                                                        <input type="text" class="form-control" id="edit-nationality" name="nationality" value="{{ $nationality->name }}">
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
                                                <div class="modal fade" id="deleteModal-{{ $nationality->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $nationality->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel-{{ $nationality->id }}">Delete Nationality</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete the Nationality "{{ $nationality->name }}"?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <form action="{{ route('nationality.destroy', $nationality->id) }}" method="POST" style="display: inline;">
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
                                    <div class="d-flex justify-content-end align-items-center mt-2">
                                        {{ $nationalities->links('vendor.pagination.bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Table -->
                    </div>

                </div>
            </div>
        </div>
        @include('admin.includes.footer')
    </div>
@endsection
