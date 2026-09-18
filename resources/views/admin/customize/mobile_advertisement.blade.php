@extends('admin.layouts.layout')
@section('title', 'Mobile Advertisement')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="row">
                        <!-- Create Mobile Advertisement Form -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Add Mobile Advertisement</h5>

                                    <form method="post" action="{{ route('mobile_advertisement.store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Image</label>
                                            <input class="form-control" type="file" name="image" id="image">
                                        </div>

                                        <div class="text-end">
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End  Mobile Advertisement Form -->

                        <!-- Mobile Advertisement Table -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-3">Mobile Advertisement</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered rounded mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Image</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($mobileAdvertisements as $index => $advertisement)
                                                <tr>
                                                    <th scope="row">{{ method_exists($mobileAdvertisements, 'firstItem') ? $mobileAdvertisements->firstItem() + $index : $index + 1 }}</th>
                                                    <td>
                                                        <div class="img-container">
                                                            <img src="{{ asset('Advertisement/' . $advertisement->image) }}" style="max-width: 100px; height: 100px" class="  rounded-start" alt="...">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <!-- Edit Button -->
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal-{{ $advertisement->id }}">Edit</button>
                                                        <!-- Delete Button -->
                                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $advertisement->id }}">Delete</button>
                                                    </td>
                                                </tr>

                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal-{{ $advertisement->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $advertisement->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel-{{ $advertisement->id }}">Edit Mobile Advertisement</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST" action="{{ route('mobile_advertisement.update', $advertisement->id) }}" enctype="multipart/form-data">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mt-3">
                                                                        <label for="edit-image" class="form-label">Image</label>
                                                                        <input class="form-control" type="file" name="image" id="edit-image">
                                                                        <img src="{{ asset('Advertisement/' . $advertisement->image) }}" class="img-fluid rounded mt-2 mb-3 w-25" alt="">
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
                                                <div class="modal fade" id="deleteModal-{{ $advertisement->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $advertisement->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel-{{ $advertisement->id }}">Delete Mobile Advertisement</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete the Mobile Advertisement?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <form action="{{ route('mobile_advertisement.destroy', $advertisement->id) }}" method="POST" style="display: inline;">
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
                                            {{ $mobileAdvertisements->links('vendor.pagination.bootstrap-5') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Mobile Advertisement Table -->
                    </div> <!-- row -->

                </div> <!-- container-fluid -->
            </div> <!-- page-content -->
        </div> <!-- main-content -->
        @include('admin.includes.footer')
    </div> <!-- layout-wrapper -->
@endsection

