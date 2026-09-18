@extends('admin.layouts.layout')
@section('title', 'Checkout PhonePe')

@section('content')
    <div class="main-content" style="margin-left: 0 !important;">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row d-flex justify-content-center">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4" style="text-align: center">Payment Information</h4>

                                <form action="{{ route('createOrder') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $userId }}">
                                    <input type="hidden" name="no_of_contact" value="{{ $no_of_contact }}">
                                    <input type="hidden" name="no_of_chats" value="{{ $no_of_chats }}">
                                    <input type="hidden" name="no_of_interests" value="{{ $no_of_interests }}">
                                    <input type="hidden" name="package" value="{{ $package }}">
                                    <input type="hidden" name="amount" value="{{ $amount }}">
                                    <input type="hidden" name="month" value="{{ $month }}">

                                    <div class="row">
                                        <label class="form-label p-0" for="package">Package</label>
                                        <input type="text" class="form-control mb-2" id="package"  value="{{ $package }}" readonly>

                                        <label class="form-label p-0" for="amount">Amount</label>
                                        <div class="input-group p-0 mb-2">
                                            <span class="input-group-text">Rs.</span>
                                            <input type="text" class="form-control" id="amount" value="{{ $amount }}" readonly>
                                        </div>

                                        <label class="form-label p-0" for="month">Month</label>
                                        <input type="text" class="form-control mb-4" id="month" value="{{ $month }}" readonly>

                                        <button type="submit" class="btn btn-primary w-md">Pay Now</button>
                                    </div>

                                </form>

                                <div id="errorMsg" class="text-danger mt-3">
                                    @if(session('error')){{ session('error') }}@endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
