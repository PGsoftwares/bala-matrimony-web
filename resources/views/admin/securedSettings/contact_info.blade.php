@extends('admin.layouts.layout')
@section('title',  'Contact Information')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="row">
                        <!-- Create contact info Form -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Add Contact Info</h5>

                                    <form method="post" action="{{ route('contact_info.store') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label" for="name-input">Email</label>
                                                <input type="text" class="form-control" name="email" id="email-input"  placeholder="Enter Email...">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label" for="mobile">Mobile 1</label>
                                                <input type="tel"  class="form-control " name="mobile_1" id="mobile" >
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label" for="phone">Mobile 2</label>
                                                <input type="tel" id="phone" class="form-control" name="mobile_2">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label" for="whatsapp">Whatsapp</label>
                                                <input type="text" id="whatsapp" class="form-control" name="whatsapp">
                                            </div>
                                            <div class="col-md-8 mb-3">
                                                <label class="form-label" for="address">Address</label>
                                                <textarea  rows="5"  class="form-control" name="address" id="address" placeholder="Enter Address..."></textarea>
                                            </div>
                                        </div>

                                        <div class="text-end">
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Create contact info Form -->

                        <!-- Packages Table -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-3">Contact Info Details</h4>

                                    @if (session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif


                                    <div class="table-responsive">
                                        <table class="table table-bordered rounded mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Email</th>
                                                <th>Mobile 1</th>
                                                <th>Mobile 2</th>
                                                <th>Whatsapp</th>
                                                <th>Address</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($contactInfos as $index => $contactInfo)
                                                <tr>
                                                    <th scope="row">{{ method_exists($contactInfos, 'firstItem') ? $contactInfos->firstItem() + $index : $index + 1 }}</th>
                                                    <td>{{ $contactInfo->email }}</td>
                                                    <td>{{ $contactInfo->mobile_1 }}</td>
                                                    <td>{{ $contactInfo->mobile_2 }}</td>
                                                    <td>{{ $contactInfo->whatsapp }}</td>
                                                    <td>{{ $contactInfo->address }}</td>
                                                    <td>
                                                        <!-- Edit Button -->
                                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal-{{ $contactInfo->id }}">Edit</button>
                                                        <!-- Delete Button -->
                                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $contactInfo->id }}">Delete</button>
                                                    </td>
                                                </tr>

                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal-{{ $contactInfo->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $contactInfo->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel-{{ $contactInfo->id }}">Edit Contact Information</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST" action="{{ route('contact_info.update', $contactInfo->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-email">Email</label>
                                                                        <input type="email" class="form-control" id="edit-email" name="email" value="{{ $contactInfo->email }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="mobile">Mobile 1</label>
                                                                        <input type="tel"  class="form-control" id="mobile" name="mobile_1" value="{{ $contactInfo->mobile_1 }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="phone">Mobile 2</label>
                                                                        <input type="tel" class="form-control" id="phone" name="mobile_2" value="{{ $contactInfo->mobile_2 }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="whatsapp">Whatsapp</label>
                                                                        <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="{{ $contactInfo->whatsapp }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-address">Address</label>
                                                                        <textarea class="form-control" id="edit-address" name="address" >{{ $contactInfo->address }}</textarea>
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
                                                <div class="modal fade" id="deleteModal-{{ $contactInfo->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $contactInfo->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel-{{ $contactInfo->id }}">Delete Contact Information</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete the Contact Details?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <form action="{{ route('contact_info.destroy', $contactInfo->id) }}" method="POST" style="display: inline;">
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
                        <!-- End Contact Information Table -->
                    </div> <!-- row -->

                </div> <!-- container-fluid -->
            </div> <!-- page-content -->
        </div> <!-- main-content -->
        @include('admin.includes.footer')
    </div> <!-- layout-wrapper -->
@endsection


