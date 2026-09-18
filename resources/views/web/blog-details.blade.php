@extends('web.layouts.layout')

@section('title', $metaTags->title ?? 'Blog Details')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')


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
                                <div class="post-item-description ">
                                    <h2>{{ $blog->title }}</h2>
                                    <div class="post-meta">
                                        <span class="post-meta-date"><i class="fa fa-calendar-o"></i>{{ \Carbon\Carbon::parse($blog->created_at)->format('M d, Y') }}</span>
                                    </div>
                                    <p class="p-2">{!! $blog->content !!}</p>
                                </div>

                                <div class="post-navigation">
                                    @if($previousBlog)
                                        <a href="{{ route('blogDetails', $previousBlog->id) }}" class="post-prev">
                                            <div class="post-prev-title">
                                                <span>Previous Post</span>{{ $previousBlog->title }}
                                            </div>
                                        </a>
                                    @endif

                                    <a href="{{ url('/') }}" class="post-all">
                                        <i class="icon-grid"> </i>
                                    </a>

                                    @if($nextBlog)
                                        <a href="{{ route('blogDetails', $nextBlog->id) }}" class="post-next">
                                            <div class="post-next-title">
                                                <span>Next Post</span>{{ $nextBlog->title }}
                                            </div>
                                        </a>
                                    @endif
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <!-- end: content -->

                <!-- Sidebar-->
                <div class="sidebar sticky-sidebar col-lg-3">


                    <div class="widget">
                        <div class="tabs">
                            <ul class="nav nav-tabs" id="tabs-posts" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#featured" role="tab" aria-controls="featured" aria-selected="false">Featured</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#recent" role="tab" aria-controls="recent" aria-selected="false">Recent</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="tabs-posts-content">

                                <div class="tab-pane active" id="featured" role="tabpanel" aria-labelledby="featured-tab">
                                    <div class="post-thumbnail-list">

                                        @foreach($featuredBlogs as $featuredBlog)
                                        <div class="post-thumbnail-entry">
                                            <img alt="" src="{{ asset('BlogImage/' .$featuredBlog->blog_image) }}">
                                            <div class="post-thumbnail-content">
                                                <a href="#">{{ $featuredBlog->title }}</a>
                                                <span class="post-date"><i class="icon-clock"></i>{{ \Carbon\Carbon::parse($featuredBlog->created_at)->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        @endforeach

                                    </div>
                                </div>

                                <div class="tab-pane fade" id="recent" role="tabpanel" aria-labelledby="recent-tab">
                                    <div class="post-thumbnail-list">

                                        @foreach($recentBlogs as $recentBlog)
                                        <div class="post-thumbnail-entry">
                                            <img alt="" src="{{ asset('BlogImage/' .$recentBlog->blog_image) }}">
                                            <div class="post-thumbnail-content">
                                                <a href="#">{{ $recentBlog->title }}</a>
                                                <span class="post-date"><i class="icon-clock"></i>{{ \Carbon\Carbon::parse($recentBlog->created_at)->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <!-- end: Sidebar-->
            </div>
        </div>
    </section>
    {{--End: Page contents--}}

    @include('web.includes.footer')
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>

@endsection
