@extends('admin.layouts.layout')
@section('title', 'Edit Blog')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Edit Blog Form -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Edit Blog</h4>
                                    <form method="POST" action="{{ route('adminUpdateBlog', $blog->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label for="title">Title</label>
                                                    <input type="text" id="title" name="title" placeholder="Title" class="form-control" value="{{ $blog->title }}">
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="description">Description</label>
                                                    <textarea rows="4" id="description" name="description" placeholder="Description" class="form-control">{{ $blog->description }}</textarea>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="image">Image</label>
                                                    <input type="file" id="image" name="blog_image" class="form-control">
                                                    <div class="mt-2">
                                                        <img src="{{ asset('BlogImage/' . $blog->blog_image) }}" alt="Blog Image" style="max-width: 100%;height: 200px;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="form-group mb-3">
                                                    <label for="blog"></label>
                                                    <textarea name="content" id="blog" class="form-control">{{ $blog->content }}</textarea>
                                                </div>
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> <!-- row -->
                </div> <!-- container-fluid -->
            </div> <!-- page-content -->
        </div> <!-- main-content -->
        @include('admin.includes.footer')
    </div>



    <script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
    <script>
        const image_upload_handler = (blobInfo, success, failure) => {
            const xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '{{ route("blog_image.store") }}?_token={{ csrf_token() }}');

            xhr.onload = () => {
                if (xhr.status < 200 || xhr.status >= 300) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }

                try {
                    const json = JSON.parse(xhr.responseText);
                    if (!json || typeof json.url !== 'string') {
                        failure('Invalid JSON: ' + xhr.responseText);
                    } else {
                        success(json.url);
                    }
                } catch (e) {
                    failure('Error parsing response: ' + e.message);
                }
            };

            xhr.onerror = () => {
                failure('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
            };

            const formData = new FormData();
            formData.append('upload', blobInfo.blob(), blobInfo.filename());

            xhr.send(formData);
        };

        document.addEventListener('DOMContentLoaded', function () {
            tinymce.init({
                license_key: 'gpl',
                selector: '#blog',
                plugins: [
                    'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
                    'searchreplace', 'wordcount', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media',
                    'table', 'emoticons', 'help'
                ],
                toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
                    'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
                    'forecolor backcolor emoticons | help',
                images_upload_handler: image_upload_handler,
                automatic_uploads: true,
                file_picker_types: 'image',
                file_picker_callback: (cb, value, meta) => {
                    const input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');

                    input.addEventListener('change', (e) => {
                        const file = e.target.files[0];

                        const reader = new FileReader();
                        reader.addEventListener('load', () => {
                            const id = 'blobid' + (new Date()).getTime();
                            const blobCache = tinymce.activeEditor.editorUpload.blobCache;
                            const base64 = reader.result.split(',')[1];

                            // Decode base64 to binary
                            const byteCharacters = atob(base64);
                            const byteNumbers = new Array(byteCharacters.length);
                            for (let i = 0; i < byteCharacters.length; i++) {
                                byteNumbers[i] = byteCharacters.charCodeAt(i);
                            }
                            const byteArray = new Uint8Array(byteNumbers);

                            // Create a Blob object from the decoded data
                            const blob = new Blob([byteArray], { type: file.type });

                            // Create a blobInfo object
                            const blobInfo = blobCache.create(id, blob, base64);
                            blobCache.add(blobInfo);

                            // Callback with the blob URI and additional data (e.g., title)
                            cb(blobInfo.blobUri(), { title: file.name });
                        });
                        reader.readAsDataURL(file);
                    });

                    input.click();
                },

                menu: {
                    favs: { title: 'Menu', items: 'code visualaid | searchreplace | emoticons' }
                },
                menubar: 'favs file edit view insert format tools table help',
                content_style: 'body { font-family: Helvetica, Arial, sans-serif; font-size: 16px; }'
            });
        });
    </script>
@endsection


