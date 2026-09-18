@extends('admin.layouts.layout')
@section('title', 'Admin Information')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Create Admin</h5>

                                    <form method="post" action="{{ route('admin_info.store') }}">
                                        @csrf
                                        <div class="row">
                                            <input type="hidden" name="role" value="admin">

                                            <div class="col-md-4 mb-3">
                                                <label class="form-label" for="name-input">Name</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name-input" value="{{ old('name') }}" placeholder="Enter Name...">
                                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label" for="email-input">Email</label>
                                                <input type="email"  class="form-control @error('email') is-invalid @enderror" name="email" id="email-input" value="{{ old('email') }}" placeholder="Enter Email...">
                                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label" for="mobile">Mobile</label>
                                                <input type="tel" id="mobile" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" >
                                                @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label" for="password-input">Password</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password-input" placeholder="Enter Password...">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('password-input', this)">
                                                        <i class="mdi mdi-eye-outline"></i>
                                                    </button>
                                                    @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-end">
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-3">Admin Details</h4>

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
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Mobile</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($adminInfos as $index => $adminInfo)
                                                <tr>
                                                    <th scope="row">{{ method_exists($adminInfos, 'firstItem') ? $adminInfos->firstItem() + $index : $index + 1 }}</th>
                                                    <td>{{ $adminInfo->name }}</td>
                                                    <td>{{ $adminInfo->email }}</td>
                                                    <td>{{ $adminInfo->mobile }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal-{{ $adminInfo->id }}">Edit</button>
                                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $adminInfo->id }}">Delete</button>
                                                    </td>
                                                </tr>

                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal-{{ $adminInfo->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $adminInfo->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel-{{ $adminInfo->id }}">Edit Admin</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST" action="{{ route('admin_info.update', $adminInfo->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-name">Name</label>
                                                                        <input type="text" class="form-control" id="edit-name" name="name" value="{{ $adminInfo->name }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-email">Email</label>
                                                                        <input type="email"  class="form-control" id="edit-email" name="email" value="{{ $adminInfo->email }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="phone">Mobile</label>
                                                                        <input type="tel" class="form-control" id="phone" name="mobile" value="{{ $adminInfo->mobile }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-password-{{ $adminInfo->id }}">Password</label>
                                                                        <div class="input-group">
                                                                            <input type="password" class="form-control" id="edit-password-{{ $adminInfo->id }}" name="password" value="" placeholder="Leave blank to keep current password">
                                                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('edit-password-{{ $adminInfo->id }}', this)">
                                                                                <i class="mdi mdi-eye-outline"></i>
                                                                            </button>
                                                                        </div>
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
                                                <div class="modal fade" id="deleteModal-{{ $adminInfo->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $adminInfo->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel-{{ $adminInfo->id }}">Delete Admin</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete the Admin "{{ $adminInfo->name }}"?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <form action="{{ route('admin_info.destroy', $adminInfo->id) }}" method="POST" style="display: inline;">
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
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    @include('admin.includes.footer')
@endsection
