<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="PG Software's" />
    <meta name="google-adsense-account" content="ca-pub-2298754008099936">
    <link rel="icon" type="image/x-icon" href="{{ asset('asset/img/logo/fav-icon-pg.png') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title' ?? 'Bala Matrimony Bureau')</title>
    <meta name="description" content="@yield('description')">
    <meta name="keywords" content="@yield('keywords')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&family=Roboto&display=swap" rel="stylesheet">
    <link href="{{ asset('asset/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/css/bootstrap.css') }}" rel="stylesheet">
    <script src="{{ asset('asset/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('asset/js/dropdown.js') }}"></script>
    <link rel="stylesheet" href="{{asset('asset/dialcode/intlTelInput.css')}}">
</head>

<body>


@yield('content')


<!-- WhatsApp Button -->
<div style="position: fixed; bottom: 80px; right: 20px; z-index: 9999;">
    <a href="https://wa.me/918122350963" target="_blank" class="btn btn-success rounded-circle shadow"
       style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
        <i class="bi bi-whatsapp" style="font-size: 24px;"></i>
    </a>
</div>
<!-- Scroll to Top Button -->
<div id="scrollToTopBtn" style="display: none; position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
    <button onclick="scrollToTop()" class="btn button2 rounded-circle shadow"
            style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
        <i class="bi bi-arrow-up" style="font-size: 16px;"></i>
    </button>
</div>
<!-- Scroll to Top Script -->
<script>
    function scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    window.addEventListener('scroll', function () {
        const scrollBtn = document.getElementById('scrollToTopBtn');
        if (window.scrollY > 300) {
            scrollBtn.style.display = 'block';
        } else {
            scrollBtn.style.display = 'none';
        }
    });
</script>

<script src="{{ asset('asset/js/bootstrap.min.js') }}"></script>
{{--<script>--}}
{{--    document.addEventListener('contextmenu', event => event.preventDefault());--}}
{{--    document.addEventListener('keydown', (event) => {--}}
{{--        if (event.key === "F12" ||--}}
{{--            (event.ctrlKey && event.shiftKey && event.key === 'I') || (event.ctrlKey && event.shiftKey && event.key === 'i') ||--}}
{{--            (event.ctrlKey && event.key === 'U') || (event.ctrlKey && event.key === 'u') || (event.ctrlKey && event.key === 'C') || (event.ctrlKey && event.key === 'c')) {--}}
{{--            event.preventDefault();--}}
{{--        }--}}
{{--    });--}}
{{--</script>--}}
</body>
</html>
