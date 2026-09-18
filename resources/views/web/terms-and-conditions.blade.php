@extends('web.layouts.layout')
@section('title', $metaTags->title ?? 'Terms and Conditions')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    {{-- Banner Section --}}
    <section style="background-image: url('{{ asset('asset/img/banner/breadcrumb-banner.jpg') }}'); background-repeat: no-repeat; background-size: cover;" class="text-light">
        <div class="bg-overlay" style="background: rgba(0, 0, 0, 0.85);"></div>
        <div class="container">
            <div class="py-5 text-center">
                <h1 class="display-5 fw-bold text-white">Terms and Conditions</h1>
            </div>
        </div>
    </section>
    {{-- End: Banner Section --}}

    {{-- Content Section --}}
    <section class="py-5 bg-light-primary">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8">
                    <div class="bg-white p-5 rounded-4 shadow-sm">
                        @foreach($db['termsAndConditions'] as $termsAndCondition)
                            <div class="mb-3 p text-muted">
                                {!! $termsAndCondition->content !!}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- End: Content Section --}}

    @include('web.includes.footer')
@endsection



