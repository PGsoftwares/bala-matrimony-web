@extends('web.layouts.layout')

@section('title', $metaTags->title ?? 'Blog Details')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')

    {{--Breadcrumb start--}}
    <section id="page-title" data-bg-parallax="{{ asset('web/assets/default/default-breadcrumb.webp') }}">
        <div class="container">

            <a href="{{ url('api/blogs') }}" class="btn btn-outline-primary" style="position: relative;bottom: 50px; left: 10px"><i class="icon-arrow-left"> </i> Go Back</a>

            <div class="page-title">
                <h1>{{ $blog->title }}</h1>
            </div>
        </div>
    </section>
    {{--End: Breadcrumb--}}

    {{--Page contents--}}
    <section id="page-content" class="sidebar-right">
        <div class="container">
            <div class="row">
                <!-- content -->
                <div class="content col-lg-9">
                    <!-- Blog -->
                    <div id="blog" class="single-post">
                        <!-- Post single item-->
                        <div class="post-item">
                            <div class="post-item-wrap">
                                <div class="post-image">
                                    <a href="#">
                                        <img alt="" src="{{ asset('BlogImage/' .$blog->blog_image) }}">
                                    </a>
                                </div>
                                <div class="post-item-description">
                                    <h2>{{ $blog->title }}</h2>
                                    <div class="post-meta">
                                        <span class="post-meta-date"><i class="fa fa-calendar-o"></i>{{ \Carbon\Carbon::parse($blog->created_at)->format('M d, Y') }}</span>
                                    </div>
                                    <p>{!! $blog->content !!}</p>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <!-- end: content -->


            </div>
        </div>
    </section>
    {{--End: Page contents--}}


@endsection
