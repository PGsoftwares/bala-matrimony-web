@extends('admin.layouts.layout')
@section('title', 'Mobile App Slider')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="row">
                        <!--Form -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Add Slider</h5>

                                    <form method="post" action="{{ route('appSliderStore') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Image</label>
                                            <input class="form-control @error('image') is-invalid @enderror" type="file" name="image" id="image">
                                            @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
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
                                    <h4 class="card-title mb-3">Sliders</h4>
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
                                                <th>Image</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($appSliders as $index => $appSlider)
                                                <tr>
                                                    <th scope="row">{{ method_exists($appSliders, 'firstItem') ? $appSliders->firstItem() + $index : $index + 1 }}</th>
                                                    <td>
                                                        <div class="img-container">
                                                            <img src="{{ asset('web/SliderImage/' . $appSlider->image) }}" style="max-width: 100px; height: 100px" class="  rounded-start" alt="...">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal-{{ $appSlider->id }}">Edit</button>
                                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $appSlider->id }}">Delete</button>
                                                    </td>
                                                </tr>

                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal-{{ $appSlider->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $appSlider->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel-{{ $appSlider->id }}">Edit Slider</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST" action="{{ route('appSliderUpdate', $appSlider->id) }}" enctype="multipart/form-data">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mt-3">
                                                                        <label for="edit-image" class="form-label">Image</label>
                                                                        <input class="form-control" type="file" name="image" id="edit-image">
                                                                        <img src="{{ asset('web/SliderImage/' . $appSlider->image) }}" class="img-fluid rounded mt-2 mb-3 w-25" alt="">
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
                                                <div class="modal fade" id="deleteModal-{{ $appSlider->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $appSlider->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel-{{ $appSlider->id }}">Delete Slider</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete the Slider?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <form action="{{ route('appSliderDelete', $appSlider->id) }}" method="POST" style="display: inline;">
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
                                            {{ $appSliders->links('vendor.pagination.bootstrap-5') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--End Table -->

                    </div>

                </div>
            </div>
        </div>
        @include('admin.includes.footer')
    </div>
@endsection
