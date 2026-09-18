@extends('web.layouts.layout')

@section('title', $metaTags->title ?? 'Blogs')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')


@section('content')


    {{-- Banner Section--}}
    <section id="page-title" style="background-image:url({{ asset('web/assets/images/slider/breadcrumb-banner.jpg') }});background-repeat: no-repeat; background-size: cover;"  class="page-title-center text-light" >
        <div class="bg-overlay" style="background: rgb(0 0 0 / 85%);"></div>
        <div class="container">
            <div class="page-title">
                <h1>News and Events</h1>
            </div>
        </div>
    </section>
    {{-- End: Banner Section--}}


    {{-- Content Section--}}
    <section id="page-content" class="sidebar-right">
        <div class="container">

            <div id="blog" class="grid-layout post-3-columns m-b-30" data-item="post-item">

                @foreach($blogs as $blog)
                    <div class="post-item border">
                        <div class="post-item-wrap">
                            <div class="post-image">
                                <a href="{{ route('api/blogDetails', $blog->id) }}">
                                    <img alt="" src="{{ asset('BlogImage/' .$blog->blog_image) }}">
                                </a>
                            </div>
                            <div class="post-item-description">
                                <span class="post-meta-date"><i class="fa fa-calendar-o"></i>{{ \Carbon\Carbon::parse($blog->created_at)->format('M d, Y') }}</span>
                                <h2>
                                    <a href="{{ route('api/blogDetails', $blog->id) }}">{{ $blog->title }}</a>
                                </h2>
                                <p>{{ $blog->description }}</p>
                                <a href="{{ route('api/blogDetails', $blog->id) }}" class="item-link">Read More <i class="icon-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

        </div>
    </section>
    {{-- End: Content Section--}}


    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
@endsection

