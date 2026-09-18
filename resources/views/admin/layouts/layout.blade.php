<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <meta name="description" content="Bala Matrimony Bureau">
    <meta name="keywords" content="Bala Matrimony Bureau">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Admin Dashboard" name="description" />
    <meta name="author"  content="PG Software's"  />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('asset/img/logo/fav-icon-pg.png') }}">

    <!-- Bootstrap Css -->
    <link href="{{asset('dashboard/admin/css/bootstrap.min.css')}}"  rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('dashboard/admin/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('dashboard/admin/css/app.min.css')}}"  rel="stylesheet" type="text/css" />
    <link href="{{asset('dashboard/admin/css/style.css')}}"  rel="stylesheet" type="text/css" />
    <!-- App js -->
{{--    <script src="{{asset('dashboard/admin/js/plugin.js')}}"></script>--}}
    <!-- Date Picker css -->
    <link href="{{asset('dashboard/admin/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <!-- Spectrum  css -->
    <link href="{{asset('dashboard/admin/libs/spectrum-colorpicker2/spectrum.min.css')}}" rel="stylesheet" type="text/css">

    <!-- DataTables -->
    <script src="{{ asset('asset/js/xlsx.full.min.js') }}"></script>
    <link rel="stylesheet" href="{{asset('asset/dialcode/intlTelInput.css')}}">
    <link rel="stylesheet" href="{{asset('dashboard/admin/DataTables/datatables.min.css')}}">
    <link rel="stylesheet" href="{{asset('asset/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('dashboard/admin/css/select2-custom.css')}}">
    <script src="{{ asset('asset/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('asset/js/dropdown.js') }}"></script>

    <style>
        .step {
            display: none;
        }
        .step.active {
            display: block;
        }
    </style>

</head>
<body data-topbar="light">


@yield('content')



<!-- JAVASCRIPT -->
{{--<script src="{{asset('dashboard/admin/libs/jquery/jquery.min.js')}}"></script>--}}
<script src="{{asset('dashboard/admin/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('dashboard/admin/libs/metismenu/metisMenu.min.js')}}"></script>
<script src="{{asset('dashboard/admin/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{asset('dashboard/admin/libs/node-waves/waves.min.js')}}"></script>

<!-- apexcharts -->
<script src="{{asset('dashboard/admin/libs/apexcharts/apexcharts.min.js')}}"></script>

<script src="{{asset('dashboard/admin/js/pages/dashboard.init.js')}}"></script>

<script src="{{asset('dashboard/admin/js/app.js')}}"></script>

{{-- Dial code js --}}
<script src="{{asset('asset/js/script.js')}}"></script>
<script src="{{asset('asset/dialcode/intlTelInput.min.js')}}"></script>
<script src="{{asset('asset/dialcode/utils.js')}}"></script>

<!-- jquery step -->
<script src="{{asset('dashboard/admin/libs/jquery-steps/build/jquery.steps.min.js')}}"></script>

<!-- form wizard init -->
{{--<script src="{{asset('dashboard/admin/js/pages/form-wizard.init.js')}}"></script>--}}

<!-- form advanced init -->
{{--<script src="{{asset('dashboard/admin/js/pages/form-advanced.init.js')}}"></script>--}}
<!-- Date Picker js -->
<script src="{{asset('dashboard/admin/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<!-- Spectrum  js -->
<script src="{{asset('dashboard/admin/libs/spectrum-colorpicker2/spectrum.min.js')}}"></script>

<!-- Required datatable js -->
<script src="{{asset('dashboard/admin/DataTables/datatables.min.js')}}"></script>
<script src="{{asset('asset/js/select2.min.js')}}"></script>

<script>
    function togglePasswordVisibility(inputId, btn) {
        var input = document.getElementById(inputId);
        if (!input) return;
        var icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            if (icon) {
                icon.className = 'mdi mdi-eye-off-outline';
            }
        } else {
            input.type = 'password';
            if (icon) {
                icon.className = 'mdi mdi-eye-outline';
            }
        }
    }
</script>

</body>
</html>
