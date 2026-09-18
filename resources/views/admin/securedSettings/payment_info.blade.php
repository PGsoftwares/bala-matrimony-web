@extends('admin.layouts.layout')
@section('title', 'Payment Information')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="row">
                        <!-- Create Payment Information Form -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Add Payment Info</h5>

                                    <form method="post" action="{{ route('payment_info.store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label" for="account_name-input">Account Name</label>
                                            <input type="text" class="form-control" name="acc_name" id="account_name-input" placeholder="Enter account name...">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="account_number-input">Account Number</label>
                                            <input type="text" class="form-control" name="acc_number" id="account_number-input" placeholder="Enter account number...">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="ifsc_code-input">Ifsc Code</label>
                                            <input type="text" class="form-control" name="ifsc_code" id="ifsc_code-input" placeholder="Enter ifsc code...">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="bank-input">Bank</label>
                                            <input type="text" class="form-control" name="bank" id="bank-input" placeholder="Enter bank name...">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="pay_number-input">Pay Number</label>
                                            <input type="text" class="form-control" name="pay_number" id="pay_number-input" placeholder="Enter pay number...">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="upi_id-input">Upi Id</label>
                                            <input type="text" class="form-control" name="upi_id" id="upi_id-input" placeholder="Enter upi id...">
                                        </div>
                                        <div class="mb-3">
                                            <label for="formFile" class="form-label">QR Image</label>
                                            <input class="form-control" type="file" name="qr_image" id="formFile">
                                        </div>

                                        <div class="text-end">
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Create Print Settings Form -->

                        <!-- Print Info Cards -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-3">Payment Info Details</h4>
                                    <div class="row">
                                        @foreach($paymentInfos as $paymentInfo)
                                            <div class="col-md-12 mb-3">
                                                <div class="card border border-warning">
                                                    <div class="row g-0">
                                                        <div class="col-md-4">
                                                            <div class="img-container">
                                                                <img src="{{ asset('paymentImage/' . $paymentInfo->qr_image) }}" style="max-width: 100%;" class=" rounded-start" alt="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="card-body">
                                                                <p class="card-text"><span class="fw-bold">Account Name :</span> {{ $paymentInfo->acc_name }}</p>
                                                                <p class="card-text"><span class="fw-bold">Account Number :</span> {{ $paymentInfo->acc_number }}</p>
                                                                <p class="card-text"><span class="fw-bold">Ifsc Code :</span> {{ $paymentInfo->ifsc_code }}</p>
                                                                <p class="card-text"><span class="fw-bold">Bank :</span> {{ $paymentInfo->bank }}</p>
                                                                <p class="card-text"><span class="fw-bold">Pay Number :</span> {{ $paymentInfo->pay_number }}</p>
                                                                <p class="card-text"><span class="fw-bold">Upi Id :</span> {{ $paymentInfo->upi_id }}</p>
                                                                <div class="d-flex justify-content-end">
                                                                    <!-- Edit Button -->
                                                                    <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editModal-{{ $paymentInfo->id }}">Edit</button>
                                                                    <!-- Delete Button -->
                                                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $paymentInfo->id }}">Delete</button>
                                                                </div>
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
                        <!-- End Print Info Cards -->

                    </div> <!-- row -->

                    <!-- Modals -->
                    @foreach($paymentInfos as $paymentInfo)
                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal-{{ $paymentInfo->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $paymentInfo->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel-{{ $paymentInfo->id }}">Edit Payment Info</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form method="POST" action="{{ route('payment_info.update', $paymentInfo->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label" for="edit-acc_name">Account Name</label>
                                                <input type="text" class="form-control" name="acc_name" id="edit-acc_name" value="{{ $paymentInfo->acc_name }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="edit-acc_number">Account Number</label>
                                                <input type="text" class="form-control" name="acc_number" id="edit-acc_number" value="{{ $paymentInfo->acc_number }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="edit-ifsc_code">Ifsc Code</label>
                                                <input type="text" class="form-control" name="ifsc_code" id="edit-ifsc_code" value="{{ $paymentInfo->ifsc_code }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="edit-bank">Bank</label>
                                                <input type="text" class="form-control" name="bank" id="edit-bank" value="{{ $paymentInfo->bank }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="edit-pay_number">Pay Number</label>
                                                <input type="text" class="form-control" name="pay_number" id="edit-pay_number" value="{{ $paymentInfo->pay_number }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="edit-upi_id">Upi Id</label>
                                                <input type="text" class="form-control" name="upi_id" id="edit-upi_id" value="{{ $paymentInfo->upi_id }}">
                                            </div>
                                            <div class="mt-3">
                                                <label for="edit-formFile" class="form-label">QR Image</label>
                                                <input class="form-control" type="file" name="qr_image" id="edit-formFile">
                                                <img src="{{ asset('paymentImage/' . $paymentInfo->qr_image) }}" class="img-fluid rounded mt-2 mb-3 w-25" alt="Current Image">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Edit Modal -->

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal-{{ $paymentInfo->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $paymentInfo->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel-{{ $paymentInfo->id }}">Delete Payment Info</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete the Payment Info?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <form action="{{ route('payment_info.destroy', $paymentInfo->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Delete Modal -->
                    @endforeach
                    <!-- End Modals -->


                </div> <!-- container-fluid -->
            </div> <!-- page-content -->
        </div> <!-- main-content -->
        @include('admin.includes.footer')
    </div> <!-- layout-wrapper -->


@endsection
