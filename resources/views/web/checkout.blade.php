@extends('admin.layouts.layout')

@section('title', $metaTags->title ?? 'checkout')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    <div class="main-content" style="margin-left: 0 !important;">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row d-flex justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Payment Information</h4>

                                <form id="payment-form" action="{{ route('purchase') }}" method="POST">
                                    @csrf

                                    <!-- Package Name -->
                                    <div class="row mb-4">
                                        <label for="name" class="col-sm-3 col-form-label">Package</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="name" id="name" value="{{ $package }}" readonly>
                                        </div>
                                    </div>

                                    <!-- Package Amount -->
                                    <div class="row mb-4">
                                        <label for="amount" class="col-sm-3 col-form-label">Amount</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="amount" id="amount" value="{{ $amount }}" readonly>
                                        </div>
                                    </div>

                                    <!-- Currency -->
                                    <div class="row mb-4">
                                        <label for="currency" class="col-sm-3 col-form-label">Currency</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="currency" id="currency" value="INR" readonly>
                                        </div>
                                    </div>

                                    <!-- Pay Now Button -->
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary w-md">Pay Now</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
