@extends('admin.layouts.layout')
@section('title',  'Blogs')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="row">

                        @foreach($blogs as $blog)
                        <div class="col-sm-4">
                            <div class="card p-1 border border-primary shadow-none">
                                <div class="p-3">
                                    <h5><a href="{{ route('adminBlogDetails', $blog->id) }}" class="text-primary">{{ $blog->title }}</a></h5>
                                    <p class="text-muted mb-0">{{ \Carbon\Carbon::parse($blog->created_at)->format('d M, Y') }}</p>
                                </div>

                                <div class="position-relative">
                                    <img src="{{ asset('BlogImage/' .$blog->blog_image) }}" alt="" class="img-thumbnail">
                                </div>

                                <div class="p-3">
                                    <p>{{ $blog->description }}</p>

                                    <div>
                                        <a href="{{ route('adminBlogDetails', $blog->id) }}" class="text-primary">Read more <i class="mdi mdi-arrow-right"></i></a>
                                    </div>
                                </div>
                                <hr class="m-0">
                                <div class="card-body d-flex justify-content-end">
                                    <a href="{{ route('adminEditBlog', $blog->id) }}" class="btn btn-primary me-2">Edit</a>

                                    <form action="{{ route('deleteBlog', $blog->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this blog?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                        @endforeach

                    </div>

                </div> <!-- container-fluid -->
            </div> <!-- page-content -->
        </div> <!-- main-content -->
        @include('admin.includes.footer')
    </div>

@endsection


