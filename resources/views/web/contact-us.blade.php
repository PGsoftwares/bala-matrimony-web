@extends('web.layouts.layout')
@section('title', $metaTags->title ?? 'Contact Us')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')


@section('content')
    @include('web.includes.header')

    {{-- Banner Section --}}
    <section style="background-image: url('{{ asset('asset/img/banner/breadcrumb-banner.jpg') }}'); background-repeat: no-repeat; background-size: cover;" class="text-light">
        <div class="bg-overlay" style="background: rgba(0, 0, 0, 0.85);"></div>
        <div class="container">
            <div class="py-5 text-center">
                <h1 class="display-5 fw-bold text-white">Contact Us</h1>
            </div>
        </div>
    </section>
    {{-- End: Banner Section --}}

    <section class="py-4" style="background-image:url({{ asset('web/assets/images/homepages/pattern-4.fhss9Ko-.jpg') }});">
        <div class="container">

            <div class="row">
                <h3 class="text-uppercase">Get In Touch</h3>

                <div class="col-md-8">

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="shadow-sm bg-white p-4 rounded">
                        <form class=""  action="{{ route('storeContactUs') }}"  method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-2">
                                <div class="form-group col-md-6">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" name="name"  class="form-control shadow-none @error('name') is-invalid @enderror" placeholder="Enter your Name">
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Email</label>
                                    <input type="email" id="email"  name="email"  class="form-control shadow-none @error('email') is-invalid @enderror" placeholder="Enter your Email">
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="form-group col-md-12">
                                    <label for="subject">Subject</label>
                                    <input type="text" id="subject" name="subject"  class="form-control shadow-none @error('subject') is-invalid @enderror" placeholder="Subject...">
                                    @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <label for="message">Message</label>
                                <textarea type="text" id="message" name="message"  rows="5" class="form-control shadow-none @error('message') is-invalid @enderror" placeholder="Enter your Message"></textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="row mb-2">
                                <div class="form-group col-md-12">
                                    <label for="attachment">Attachment</label>
                                    <input type="file" id="attachment" name="attachment"  class="form-control shadow-none @error('attachment') is-invalid @enderror">
                                    @error('attachment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="captcha">Captcha</label>
                                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                                @error('g-recaptcha-response')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="d-flex justify-content-end">
                                <button class="btn button2" type="submit"><i class="fa fa-paper-plane me-2"></i>&nbsp;Send message</button>
                            </div>
                        </form>
                        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                    </div>
                </div>

                <div class="col-md-4">

                    <div class="card shadow-sm border-0 p-3">
                        <h5><i class="bi bi-geo-alt-fill gradient-text"></i> Address</h5>
                        <p class="primary_color">{{ $db['contactInfo']->address }}</p>

                        <h5><i class="bi bi-envelope-fill gradient-text"></i> Email</h5>
                        <p>
                            <a class="primary_color" href="mailto:{{ $db['contactInfo']->email }}">{{ $db['contactInfo']->email }}</a>
                        </p>

                        <h5><i class="bi bi-telephone-fill gradient-text"></i> Call Us</h5>
                        <p>
                            <a class="primary_color" href="tel:{{ $db['contactInfo']->mobile_1 }}">{{ $db['contactInfo']->mobile_1 }}</a>
                            @if(!empty($db['contactInfo']->mobile_2))
                                <br><a class="primary_color" href="tel:{{ $db['contactInfo']->mobile_2 }}">{{ $db['contactInfo']->mobile_2 }}</a>
                            @endif
                        </p>

                        <h5><i class="bi bi-clock-fill gradient-text"></i> Office Time</h5>
                        <p class="primary_color mb-1">Mon - Sat: 9.00am to 7.09pm</p>
                        <p class="primary_color">Sun: 10.00am to 5.00pm</p>

                        <h5><i class="bi bi-share-fill gradient-text"></i> Social Media</h5>
                        <div class="social-links mt-2">
                            <a href="https://www.facebook.com/share/1ApkrBb6qe/" target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                            <a href="https://www.instagram.com/balamatrimonybureau?utm_source=qr&igsi=MTk4OWFsZWk1czIxeQ==" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                            <a href="https://youtube.com/@balathidumal?si=ZNOgk2eakjqPUC_s" target="_blank" rel="noopener" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                        </div>
                        
                         <div class="mt-3">
                            <a href="#" target="_blank" rel="noopener">
                                <img src="{{ asset('asset/img/icon/google-play.webp') }}" alt="Get it on Google Play" style="height: 40px; width: auto;" class="img-fluid rounded">
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <section class="">
        <iframe class="w-100 border shadow-sm" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3914.924512368081!2d78.01299999999999!3d11.119!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3babd4a3f73349e3%3A0xc3163d15324944d!2sSri%20Sarathi%20Mahal!5e0!3m2!1sen!2sin!4v1788846352130!5m2!1sen!2sin" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </section>

    @include('web.includes.footer')
@endsection
