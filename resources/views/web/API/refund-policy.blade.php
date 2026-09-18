@extends('web.layouts.layout')

@section('title', $metaTags->title ?? 'Refund Policy')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')


@section('content')


    {{-- Banner Section--}}
    <section id="page-title" style="background-image:url({{ asset('web/assets/images/slider/breadcrumb-banner.jpg') }});background-repeat: no-repeat; background-size: cover;"  class="page-title-center text-light" >
        <div class="bg-overlay" style="background: rgb(0 0 0 / 85%);"></div>
        <div class="container">
            <div class="page-title">
                <h1>Refund Policy</h1>
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
                            @foreach($refundPolicies as $refundPolicy)
                                <p>{!! $refundPolicy->content !!}</p>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    {{-- End: Content Section--}}


@endsection

