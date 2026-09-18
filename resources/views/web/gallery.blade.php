@extends('web.layouts.layout')
@section('title', 'My Gallery')

@section('content')
    @include('web.includes.header')

    <section class="container">
        <div class="row">
            <div class="col-md-8 mt-4 mb-5">
                <div class="card border-0 shadow p-3 rounded-4">
                    <h5 class="fw-bold">My Gallery</h5>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <div id="alert-box"></div>

                    <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="gallery_images" class="form-label">Upload Images</label>
                            <input type="file" class="form-control" name="gallery_images[]" multiple>
                            @error('gallery_images.*')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn button2 rounded-pill">Upload</button>
                        </div>

                    </form>

                    <hr class="my-4">

                    <div class="row">
                        @foreach($galleryImages as $img)
                            <div class="col-md-4 mb-3 position-relative">
                                <div class="rounded shadow-sm overflow-hidden" style="height: 200px;">
                                    <img src="{{ asset('GalleryImage/' . $img->image) }}"
                                         class="w-100 h-100 object-fit-cover"
                                         alt="Image">

                                    {{-- Delete icon --}}
                                    <button type="button"
                                            class="btn btn-sm btn-danger position-absolute top-0 end-0 me-4 mt-2 delete-image"
                                            data-id="{{ $img->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>

            @include('web.includes.right-aside')
        </div>
    </section>

    <script>
        $(document).on('click', '.delete-image', function () {
            const id = $(this).data('id');

            if (confirm('Are you sure you want to delete this image?')) {
                $.ajax({
                    url: `gallery-delete/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        $('#alert-box').html(`
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Image deleted successfully.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
                        $(`button[data-id="${id}"]`).closest('.col-md-4').remove();
                    },
                    error: function () {
                        $('#alert-box').html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Something went wrong. Please try again.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
                    }
                });
            }
        });
    </script>
    <script src="{{ asset('asset/js/jquery-3.7.1.min.js') }}"></script>
    @include('web.includes.footer')
@endsection
