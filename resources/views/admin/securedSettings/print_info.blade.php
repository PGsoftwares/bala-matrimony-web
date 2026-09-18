@extends('admin.layouts.layout')
@section('title', 'Print Information')

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
                                    <h5 class="card-title mb-3">Add Print Info</h5>

                                    <form method="post" action="{{ route('print_info.store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label" for="contact-input">Contact</label>
                                            <input type="text" class="form-control" name="contact" id="contact-input" placeholder="Enter contact...">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="address-input">Address</label>
                                            <textarea  id="address-input" class="form-control" name="address" rows="3"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="formFile" class="form-label">Image</label>
                                            <input class="form-control" type="file" name="logo" id="formFile">
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
                                    <h4 class="card-title mb-3">Print Info Details</h4>
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif

                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif
                                    <div class="row">
                                        @foreach($printInfos as $printInfo)
                                            <div class="col-md-12 mb-3">
                                                <div class="card border border-warning">
                                                    <div class="row g-0">
                                                        <div class="col-md-4">
                                                            <div class="img-container">
                                                                <img src="{{ asset('printLogo/' . $printInfo->logo) }}" style="max-width: 100%;" class=" rounded-start" alt="...">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="card-body">
                                                                <h5 class="card-title">{{ $printInfo->contact }}</h5>
                                                                <p class="card-text">{{ $printInfo->address }}</p>
                                                                <div class="d-flex justify-content-end">
                                                                    <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editModal-{{ $printInfo->id }}">Edit</button>
                                                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $printInfo->id }}">Delete</button>
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

                    </div>

                    <!-- Modals -->
                    @foreach($printInfos as $printInfo)
                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal-{{ $printInfo->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $printInfo->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel-{{ $printInfo->id }}">Edit Print Info</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form method="POST" action="{{ route('print_info.update', $printInfo->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label" for="edit-contact">Contact</label>
                                                <input type="text" class="form-control" name="contact" id="edit-contact" value="{{ $printInfo->contact }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="edit-address">Address</label>
                                                <textarea id="edit-address" class="form-control" name="address" rows="3">{{ $printInfo->address }}</textarea>
                                            </div>
                                            <div class="mt-3">
                                                <label for="edit-formFile" class="form-label">Logo</label>
                                                <input class="form-control" type="file" name="logo" id="edit-formFile">
                                                <img src="{{ asset('printLogo/' . $printInfo->logo) }}" class="img-fluid rounded mt-2 mb-3 w-25" alt="Current Image">
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
                        <div class="modal fade" id="deleteModal-{{ $printInfo->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $printInfo->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel-{{ $printInfo->id }}">Delete PrintInfo</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete the Print Info?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <form action="{{ route('print_info.destroy', $printInfo->id) }}" method="POST" style="display: inline;">
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
                    <!-- End Modals -->


                </div>
            </div>
        </div>
        @include('admin.includes.footer')
    </div>
@endsection
