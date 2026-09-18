@extends('admin.layouts.layout')
@section('title', 'Meta Tags')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="row">
                        <!-- Create SEO Form -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Create SEO</h5>

                                    <form method="post" action="{{ route('seo.store') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-6 mb-3">
                                                <label class="form-label" for="page-input">Page</label>
                                                <input type="text" class="form-control" name="page" id="page-input" placeholder="Enter page...">
                                            </div>
                                            <div class="col-sm-6 mb-3">
                                                <label class="form-label" for="title-input">Title</label>
                                                <input type="text" class="form-control" name="title" id="title-input" placeholder="Enter title...">
                                            </div>
                                            <div class="col-sm-6 mb-3">
                                                <label class="form-label" for="description-input">Description</label>
                                                <input type="text" class="form-control" name="description" id="description-input" placeholder="Enter description...">
                                            </div>
                                            <div class="col-sm-6 mb-3">
                                                <label class="form-label" for="keywords-input">Keywords</label>
                                                <input type="text" class="form-control" name="keywords" id="keywords-input" placeholder="Enter keywords...">
                                            </div>
                                        </div>

                                        <div class="text-end">
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Create SEO Form -->

                        <!-- SEO Table -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-3">SEO</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered rounded mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Page</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Keywords</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($metaTags as $index => $metaTag)
                                                <tr>
                                                    <th scope="row">{{ $metaTags->firstItem() + $index }}</th>
                                                    <td>{{ $metaTag->page }}</td>
                                                    <td>{{ $metaTag->title }}</td>
                                                    <td>{{ $metaTag->description }}</td>
                                                    <td>{{ $metaTag->keywords }}</td>
                                                    <td>
                                                        <!-- Edit Button -->
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal-{{ $metaTag->id }}">Edit</button>
                                                        <!-- Delete Button -->
                                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $metaTag->id }}">Delete</button>
                                                    </td>
                                                </tr>

                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal-{{ $metaTag->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $metaTag->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel-{{ $metaTag->id }}">Edit Meta Tags</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST" action="{{ route('seo.update', $metaTag->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-page-{{ $metaTag->id }}">Page</label>
                                                                        <input type="text" class="form-control" id="edit-page-{{ $metaTag->id }}" name="page" value="{{ $metaTag->page }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-title-{{ $metaTag->id }}">Title</label>
                                                                        <input type="text" class="form-control" id="edit-title-{{ $metaTag->id }}" name="title" value="{{ $metaTag->title }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-description-{{ $metaTag->id }}">Description</label>
                                                                        <input type="text" class="form-control" id="edit-description-{{ $metaTag->id }}" name="description" value="{{ $metaTag->description }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-keywords-{{ $metaTag->id }}">Keywords</label>
                                                                        <input type="text" class="form-control" id="edit-keywords-{{ $metaTag->id }}" name="keywords" value="{{ $metaTag->keywords }}">
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
                                                <div class="modal fade" id="deleteModal-{{ $metaTag->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $metaTag->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel-{{ $metaTag->id }}">Delete Meta Tags</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete the Page "{{ $metaTag->page }}"?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <form action="{{ route('seo.destroy', $metaTag->id) }}" method="POST" style="display: inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End Delete Modal -->

                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">No Records Found</td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>

                                        <!-- Pagination Links -->
                                        <div class="d-flex justify-content-end mt-2">
                                            {{ $metaTags->links('vendor.pagination.bootstrap-5') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End SEO Table -->
                    </div> <!-- row -->

                </div> <!-- container-fluid -->
            </div> <!-- page-content -->
        </div> <!-- main-content -->
        @include('admin.includes.footer')
    </div> <!-- layout-wrapper -->
@endsection
