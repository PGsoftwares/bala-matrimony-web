@extends('admin.layouts.layout')
@section('title', 'About Us')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="row">
                        <!-- Create  Form -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">About Us</h4>
                                    <form method="POST" action="{{ route('about-us.store') }}">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <label for="about-us"></label>
                                            <textarea name="content" id="about-us" class="form-control">
                                                {!! $aboutUs->content ?? '' !!}
                                            </textarea>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div> <!-- row -->

                </div> <!-- container-fluid -->
            </div> <!-- page-content -->
        </div> <!-- main-content -->
        @include('admin.includes.footer')
    </div> <!-- layout-wrapper -->

    <script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('asset/tiny/tinymce-setup.js') }}"></script>
@endsection
