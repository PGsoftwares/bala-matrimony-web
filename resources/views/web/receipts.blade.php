@extends('admin.layouts.layout')

@section('title', $metaTags->title ?? '')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')


    <div class="main-content py-5">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row d-flex justify-content-center">


                    <div class="col-lg-6 ">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Payment Information</h4>

                                <form method="POST" action="{{ route('receipts.store') }}">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $userId }}">
                                    <input type="hidden" name="no_of_contact" value="{{ $no_of_contact }}">

                                    <div class="row mb-4">
                                        <label for="name" class="col-sm-3 col-form-label">Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="name" id="amount" placeholder="Please Enter Name" required>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="amount" class="col-sm-3 col-form-label">Amount</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="amount" id="amount" value="{{ $amount }}" readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="package" class="col-sm-3 col-form-label">Package</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="package" id="package" value="{{ $package }}" readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="currency" class="col-sm-3 col-form-label">Currency</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="currency" value="INR">
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="month" class="col-sm-3 col-form-label">Month</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="month" id="month" value="{{ $month }}" readonly>
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary w-md">Pay Now</button>
                                    </div>
                                </form>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>


                </div>


            </div>
        </div>
    </div>











    {{--     Toast message container--}}
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="toast-header">
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body text-success fw-bold">
                {{ session('success') }}
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sessionMessage = "{{ session('success') }}";
            if (sessionMessage) {
                const toastElement = document.getElementById('liveToast');
                const toastBootstrap = new bootstrap.Toast(toastElement);
                toastBootstrap.show();
            }

            new DataTable('#user-list', {
                layout: {
                    topStart: {
                        buttons: ['pageLength'],
                        // buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    },
                    responsive: true
                }
            });

        });

    </script>
    
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>

@endsection

