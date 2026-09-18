@extends('admin.layouts.layout')
@section('title', 'Happy Stories')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="row">
                        <!-- Create Happy Stories Form -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Create Happy Story</h5>

                                    <form method="post" action="{{ route('happy-stories.store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label" for="groom-input">Groom Name</label>
                                            <input type="text" class="form-control" name="groom" id="groom-input" placeholder="Enter groom name...">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="bride-input">Bride Name</label>
                                            <input type="text" class="form-control" name="bride" id="bride-input" placeholder="Enter bride name...">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="message-input">Content</label>
                                            <textarea required="" id="message-input" class="form-control" name="content" rows="3"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="formFile" class="form-label">Image</label>
                                            <input class="form-control" type="file" name="image" id="formFile">
                                        </div>

                                        <div class="text-end">
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Create Happy Stories Form -->

                        <!-- Happy Stories Cards -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-3">Happy Stories</h4>
                                    <div class="row">
                                        @foreach($happy_stories as $happy_story)
                                            <div class="col-md-12 mb-3">
                                                <div class="card border border-warning">
                                                    <div class="row g-0">
                                                        <div class="col-md-4">
                                                            <div class="img-container">
                                                                <img src="{{ asset('HappyStory/' . $happy_story->image) }}" style="max-width: 100%; height: 300px" class="  rounded-start" alt="...">
                                                            </div>

                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="card-body">
                                                                <h5 class="card-title">{{ $happy_story->groom }} - {{ $happy_story->bride }}</h5>
                                                                <p class="card-text">{{ $happy_story->content }}</p>
                                                                <div class="d-flex justify-content-end">
                                                                    <!-- Edit Button -->
                                                                    <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editModal-{{ $happy_story->id }}">Edit</button>
                                                                    <!-- Delete Button -->
                                                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $happy_story->id }}">Delete</button>
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
                        <!-- End Happy Stories Cards -->

                    </div> <!-- row -->

                    <!-- Modals -->
                    @foreach($happy_stories as $happy_story)
                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal-{{ $happy_story->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $happy_story->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel-{{ $happy_story->id }}">Edit Happy Story</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form method="POST" action="{{ route('happy-stories.update', $happy_story->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label" for="edit-groom">Groom Name</label>
                                                <input type="text" class="form-control" id="edit-groom" name="groom" value="{{ $happy_story->groom }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="edit-bride">Bride Name</label>
                                                <input type="text" class="form-control" name="bride" id="edit-bride" value="{{ $happy_story->bride }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="edit-content">Content</label>
                                                <textarea id="edit-content" class="form-control" name="content" rows="3">{{ $happy_story->content }}</textarea>
                                            </div>
                                            <div class="mt-3">
                                                <label for="edit-formFile" class="form-label">Image</label>
                                                <input class="form-control" type="file" name="image" id="edit-formFile">
                                                <img src="{{ asset('HappyStory/' . $happy_story->image) }}" class="img-fluid rounded mt-2 mb-3 w-25" alt="Current Image">
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
                        <div class="modal fade" id="deleteModal-{{ $happy_story->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $happy_story->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel-{{ $happy_story->id }}">Delete Happy Story</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete the Happy story "{{ $happy_story->groom }}" - "{{ $happy_story->bride }}"?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <form action="{{ route('happy-stories.destroy', $happy_story->id) }}" method="POST" style="display: inline;">
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


                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-end">
                        {{ $happy_stories->links('vendor.pagination.bootstrap-5') }}
                    </div>


                </div> <!-- container-fluid -->
            </div> <!-- page-content -->
        </div> <!-- main-content -->
        @include('admin.includes.footer')
    </div> <!-- layout-wrapper -->

    <!-- Additional CSS -->
    <style>
        .img-container {
            width: 100%;
            height: 200px;
            overflow: hidden;
        }

        .img-container img {
            width: 100%;
            height: 100%;
            object-fit: fill;
        }
    </style>
@endsection
