@extends('web.layouts.layout')
@section('title', $metaTags->title ?? '')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <style>
        .nav-tabs .nav-link {
            /*font-weight: 500;*/
            /*border-radius: 8px 8px 0 0;*/
            /*transition: background 0.3s ease, color 0.3s ease;*/
        }

        .nav-tabs .nav-link.active {
            background-color: #296e1c;
            color: #fff;
        }

        /*.tab-pane {*/
        /*    background-color: #f8f9fa;*/
        /*    border: 1px solid #dee2e6;*/
        /*    border-top: none;*/
        /*    padding: 10px;*/
        /*    border-radius: 0 0 8px 8px;*/
        /*    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);*/
        /*}*/

        h5 {
            color: #0d6efd;
        }

        .nav-tabs {
            display: flex;
            flex-wrap: nowrap;
            background: #e9ecef;
            padding: 0.5rem;
            border-radius: 8px;
        }
    </style>


    <section id="page-content">

        <div class="container-fluid">
            <div class="row">
                @include('web.includes.aside')

                <div class="content col-sm-12 col-lg-8">
                    <div class="container mt-1 p-0">

                        <ul class="nav nav-tabs" id="mainTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="viewed-by-me-tab" data-toggle="tab" data-target="#viewed-by-me"
                                        type="button" role="tab">Contact Viewed by Me
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="viewed-me-tab" data-toggle="tab" data-target="#viewed-me"
                                        type="button" role="tab">Contact Viewed Me
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="mainTabContent">
                            {{-- Tab 1 Content --}}
                            <div class="tab-pane fade show active" id="viewed-by-me" role="tabpanel" aria-labelledby="viewed-by-me-tab">

                                <div class="row mt-3 g-4">
                                    @foreach($viewedContacts as $viewedContact)
                                        <div class="col-md-6 mb-4">
                                            <div class="card border-0 shadow rounded-4 overflow-hidden">
                                                <div class="row g-0 align-items-center">
                                                    <!-- Left: Profile Image -->
                                                    <div class="col-4 text-center bg-light p-3">
                                                        <img src="{{ $viewedContact->profile_image }}"
                                                             class="img-fluid rounded-circle border border-3 border-white shadow"
                                                             alt="User Photo"
                                                             style="width: 100px; height: 100px; object-fit: cover;">
                                                    </div>

                                                    <!-- Right: Info -->
                                                    <div class="col-8">
                                                        <div class="card-body p-0">
                                                            <h5 class="fw-bold mb-1">{{ $viewedContact->name }} (VM{{ $viewedContact->id }})</h5>
                                                            <p class="mb-2 text-secondary">
                                                                <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($viewedContact->viewed_on)->format('M d, Y h:i A') }}
                                                            </p>
                                                            <button class="btn primary_button px-3 toggle-view-btn"
                                                                    type="button"
                                                                    data-target="viewDetails-{{ $viewedContact->id }}">
                                                                <i class="bi bi-eye-fill me-1"></i> View
                                                            </button>

                                                        </div>
                                                    </div>

                                                    <!-- Collapsible content -->
                                                    <div class="col-12 mt-3 d-none" id="viewDetails-{{ $viewedContact->id }}">
                                                        <div class="card card-body border">
                                                            <p><strong>Mobile:</strong> {{ $viewedContact->mobile ?? 'N/A' }}</p>
                                                            <p><strong>Email:</strong> {{ $viewedContact->email ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>



                            {{-- Tab 2 Content --}}
                            <div class="tab-pane fade p-3" id="viewed-me" role="tabpanel" aria-labelledby="viewed-me-tab">

                                <div class="row mt-3 g-4">
                                    @foreach($viewerContacts as $viewerContact)
                                        <div class="col-md-6 mb-4">
                                            <div class="card border-0 shadow rounded-4 overflow-hidden">
                                                <div class="row g-0 align-items-center">
                                                    <!-- Left: Profile Image -->
                                                    <div class="col-4 text-center bg-light p-3">
                                                        <img src="{{ $viewerContact->profile_image }}"
                                                             class="img-fluid rounded-circle border border-3 border-white shadow"
                                                             alt="User Photo"
                                                             style="width: 100px; height: 100px; object-fit: cover;">
                                                    </div>

                                                    <!-- Right: Info -->
                                                    <div class="col-8">
                                                        <div class="card-body p-0">
                                                            <h5 class="fw-bold mb-1">{{ $viewerContact->name }} (VM{{ $viewerContact->id }})</h5>
                                                            <p class="mb-2 text-secondary">
                                                                <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($viewerContact->viewed_on)->format('M d, Y h:i A') }}
                                                            </p>
                                                            <button class="btn primary_button px-3 toggle-view-btn"
                                                                    type="button"
                                                                    data-target="viewDetails-{{ $viewerContact->id }}">
                                                                <i class="bi bi-eye-fill me-1"></i> View
                                                            </button>

                                                        </div>
                                                    </div>

                                                    <!-- Collapsible content -->
                                                    <div class="col-12 mt-3 d-none" id="viewDetails-{{ $viewerContact->id }}">
                                                        <div class="card card-body border">
                                                            <p><strong>Mobile:</strong> {{ $viewerContact->mobile ?? 'N/A' }}</p>
                                                            <p><strong>Email:</strong> {{ $viewerContact->email ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>


                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>


    @include('web.includes.footer')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.toggle-view-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const targetId = btn.getAttribute('data-target');
                    const targetDiv = document.getElementById(targetId);

                    // Hide all other details
                    document.querySelectorAll('.toggle-view-btn').forEach(b => {
                        const id = b.getAttribute('data-target');
                        if (id !== targetId) {
                            document.getElementById(id)?.classList.add('d-none');
                        }
                    });

                    // Toggle visibility of selected
                    targetDiv.classList.toggle('d-none');
                });
            });
        });
    </script>
@endsection
