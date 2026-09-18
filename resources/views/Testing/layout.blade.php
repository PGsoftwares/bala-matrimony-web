<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Dependent Dropdown Example')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.1.3/css/bootstrap.min.css" />
{{--    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>--}}

</head>
<body>

@yield('content')


<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/dropdown.js') }}"></script>
</body>
</html>
