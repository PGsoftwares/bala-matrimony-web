@extends('web.layouts.layout')

@section('title', $metaTags->title ?? 'Privacy Policy')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')


@section('content')

    {{-- Banner Section--}}
    <section id="page-title" class="page-title-center text-light" style="background-image:url({{ asset('web/assets/images/slider/about.jpg') }});background-repeat: no-repeat; background-size: cover;"  >
        <div class="bg-overlay" style="background: rgb(0 0 0 / 85%);"></div>
        <div class="container">
            <div class="page-title">
                <h1>Privacy Policy</h1>
            </div>
        </div>
    </section>
    {{-- End: Banner Section--}}


    {{-- Content Section--}}
    <section id="page-content" class="sidebar-right" style="background-image:url({{ asset('web/assets/images/slider/bg.jpg') }});background-size: cover; ">
        <div class="container-fluid">
            <div id="blog" class="single-post col-lg-10 center">

                <div class="post-item mt-5 mb-5">
                    <div class="theme-border">
                        <div class="post-item-description">
                            @foreach($privacyPolicies as $privacyPolicy)
                                <p>{!! $privacyPolicy->content !!}</p>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    {{-- End: Content Section--}}


@endsection

