@extends('admin.layouts.layout')
@section('title', 'Ethnicity')

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
                                    <h5 class="card-title mb-3">Add Ethnicity</h5>

                                    <form method="post" action="{{ route('ethnicity.store') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label" for="ethnicity">Ethnicity</label>
                                            <input type="text" class="form-control" name="ethnicity" id="ethnicity" placeholder="Enter Ethnicity">
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
                                    <h4 class="card-title mb-3">Ethnicities</h4>

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
                                                <th>Ethnicity</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($ethnicities as $index => $ethnicity)
                                                <tr>
                                                    <th scope="row">{{ method_exists($ethnicities, 'firstItem') ? $ethnicities->firstItem() + $index : $index + 1 }}</th>
                                                    <td>{{ $ethnicity->name }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-{{ $ethnicity->id }}">Edit</button>
                                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $ethnicity->id }}">Delete</button>
                                                    </td>
                                                </tr>

                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal-{{ $ethnicity->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $ethnicity->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel-{{ $ethnicity->id }}">Edit Ethnicity</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST" action="{{ route('ethnicity.update', $ethnicity->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-ethnicity">Ethnicity</label>
                                                                        <input type="text" class="form-control" id="edit-ethnicity" name="ethnicity" value="{{ $ethnicity->name }}">
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
                                                <div class="modal fade" id="deleteModal-{{ $ethnicity->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $ethnicity->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel-{{ $ethnicity->id }}">Delete Ethnicity</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete the Ethnicity "{{ $ethnicity->name }}"?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <form action="{{ route('ethnicity.destroy', $ethnicity->id) }}" method="POST" style="display: inline;">
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
                                        {{ $ethnicities->links('vendor.pagination.bootstrap-5') }}
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
