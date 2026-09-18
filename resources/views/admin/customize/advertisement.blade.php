@extends('admin.layouts.layout')
@section('title',  'Website Advertisement')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="row">
                        <!-- Create Advertisement Form -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Add Advertisement</h5>

                                    <form method="post" action="{{ route('advertisement.store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="home-1" class="form-label">Home 1</label>
                                            <input class="form-control" type="file" name="add_1" id="home-1">
                                        </div>

                                        <div class="mb-3">
                                            <label for="home-2" class="form-label">Home 2</label>
                                            <input class="form-control" type="file" name="add_2" id="home-2">
                                        </div>

                                        <div class="mb-3">
                                            <label for="rightSide" class="form-label">Right Side</label>
                                            <input class="form-control" type="file" name="add_3" id="rightSide">
                                        </div>

                                        <div class="mb-3">
                                            <label for="rightSide2" class="form-label">Right Side 2</label>
                                            <input class="form-control" type="file" name="add_4" id="rightSide2">
                                        </div>

                                        <div class="text-end">
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End  Advertisement Form -->

                        <!-- Advertisement Table -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-3">Advertisement</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered rounded mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Home-1</th>
                                                <th>Home-2</th>
                                                <th>Right Side</th>
                                                <th>Right Side 2</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($advertisements as $index => $advertisement)
                                                <tr>
                                                    <th scope="row">{{ method_exists($advertisements, 'firstItem') ? $advertisements->firstItem() + $index : $index + 1 }}</th>
                                                    <td>
                                                        <div class="img-container">
                                                            <img src="{{ asset('Advertisement/' . $advertisement->add_1) }}" style="max-width: 100px; height: 100px" class="  rounded-start" alt="...">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="img-container">
                                                            <img src="{{ asset('Advertisement/' . $advertisement->add_2) }}" style="max-width: 100px; height: 100px" class="  rounded-start" alt="...">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="img-container">
                                                            <img src="{{ asset('Advertisement/' . $advertisement->add_3) }}" style="max-width: 100px; height: 100px" class="  rounded-start" alt="...">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="img-container">
                                                            <img src="{{ asset('Advertisement/' . $advertisement->add_4) }}" style="max-width: 100px; height: 100px" class="  rounded-start" alt="...">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal-{{ $advertisement->id }}">Edit</button>
                                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $advertisement->id }}">Delete</button>
                                                    </td>
                                                </tr>

                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal-{{ $advertisement->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $advertisement->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel-{{ $advertisement->id }}">Edit Advertisement</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST" action="{{ route('advertisement.update', $advertisement->id) }}" enctype="multipart/form-data">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mt-3">
                                                                        <label for="edit-add1" class="form-label">Home 1</label>
                                                                        <input class="form-control" type="file" name="add_1" id="edit-add1">
                                                                        <img src="{{ asset('Advertisement/' . $advertisement->add_1) }}" class="img-fluid rounded mt-2 mb-3 w-25" alt="Current Image">
                                                                    </div>
                                                                    <div class="mt-3">
                                                                        <label for="edit-add2" class="form-label">Home 2</label>
                                                                        <input class="form-control" type="file" name="add_2" id="edit-add2">
                                                                        <img src="{{ asset('Advertisement/' . $advertisement->add_2) }}" class="img-fluid rounded mt-2 mb-3 w-25" alt="Current Image">
                                                                    </div>
                                                                    <div class="mt-3">
                                                                        <label for="edit-add3" class="form-label">Right Side</label>
                                                                        <input class="form-control" type="file" name="add_3" id="edit-add3">
                                                                        <img src="{{ asset('Advertisement/' . $advertisement->add_3) }}" class="img-fluid rounded mt-2 mb-3 w-25" alt="Current Image">
                                                                    </div>
                                                                    <div class="mt-3">
                                                                        <label for="edit-add4" class="form-label">Right Side 2</label>
                                                                        <input class="form-control" type="file" name="add_4" id="edit-add4">
                                                                        <img src="{{ asset('Advertisement/' . $advertisement->add_4) }}" class="img-fluid rounded mt-2 mb-3 w-25" alt="Current Image">
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
                                                                <h5 class="modal-title" id="deleteModalLabel-{{ $advertisement->id }}">Delete Advertisement</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete the Advertisement?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <form action="{{ route('advertisement.destroy', $advertisement->id) }}" method="POST" style="display: inline;">
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
                                            {{ $advertisements->links('vendor.pagination.bootstrap-5') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Advertisement Table -->
                    </div> <!-- row -->

                </div> <!-- container-fluid -->
            </div> <!-- page-content -->
        </div> <!-- main-content -->
        @include('admin.includes.footer')
    </div> <!-- layout-wrapper -->
@endsection

