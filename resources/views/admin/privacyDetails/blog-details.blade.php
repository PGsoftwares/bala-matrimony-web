@extends('admin.layouts.layout')
@section('title', 'Blog Details')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="product-detai-imgs">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="tab-content" id="v-pills-tabContent">
                                                            <div class="tab-pane fade show active" id="product-1" role="tabpanel" aria-labelledby="product-1-tab">
                                                                <div>
                                                                    <img src="{{ asset('BlogImage/' .$blog->blog_image) }}" alt="" class="img-fluid mx-auto d-block">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6">
                                            <div class="mt-4 mt-xl-3">
                                                <h4 class="mt-1 mb-3">{{ $blog->title }}</h4>
                                                <h6 class="text-success text-uppercase">{{ \Carbon\Carbon::parse($blog->created_at)->format('M d, Y') }}</h6>
                                                <p class="text-muted mb-4">{{ $blog->description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end row -->

                                    <div class="mt-5">
                                        <p>{!! $blog->content !!}</p>
                                    </div>
                                    <!-- end Specifications -->



                                </div>
                            </div>
                            <!-- end card -->
                        </div>
                    </div>

                </div> <!-- container-fluid -->
            </div> <!-- page-content -->
        </div> <!-- main-content -->
        @include('admin.includes.footer')
    </div>

@endsection


