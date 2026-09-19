@extends('admin.layouts.layout')
@section('title', 'Payment Transactions List')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')

        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <!-- Page Title Header -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between pb-2">
                                <h4 class="mb-sm-0 font-size-18"><i class="bx bx-credit-card me-2 text-primary"></i>Payment Transactions List</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Payments</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alerts -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-check-all me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-block-helper me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Summary KPI Cards -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card mini-stat bg-primary text-white shadow-sm">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <div class="float-end">
                                            <span class="avatar-title rounded-circle bg-white bg-opacity-25 text-white font-size-22 p-2">
                                                <i class="bx bx-rupee"></i>
                                            </span>
                                        </div>
                                        <p class="font-size-13 text-uppercase mb-1 opacity-75">Total Revenue</p>
                                        <h3 class="mb-0 text-white font-size-24">&#8377; {{ number_format($stats['total_revenue'] ?? 0, 2) }}</h3>
                                    </div>
                                    <div class="pt-2 border-top border-white border-opacity-25 text-white font-size-12">
                                        <span class="badge bg-success me-1"><i class="mdi mdi-check"></i> {{ $stats['paid_count'] ?? 0 }}</span> Successful Payments
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card mini-stat bg-success text-white shadow-sm">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <div class="float-end">
                                            <span class="avatar-title rounded-circle bg-white bg-opacity-25 text-white font-size-22 p-2">
                                                <i class="bx bx-calendar-check"></i>
                                            </span>
                                        </div>
                                        <p class="font-size-13 text-uppercase mb-1 opacity-75">This Month</p>
                                        <h3 class="mb-0 text-white font-size-24">&#8377; {{ number_format($stats['month_revenue'] ?? 0, 2) }}</h3>
                                    </div>
                                    <div class="pt-2 border-top border-white border-opacity-25 text-white font-size-12">
                                        Today: <strong>&#8377; {{ number_format($stats['today_revenue'] ?? 0, 2) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card mini-stat bg-warning text-white shadow-sm">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <div class="float-end">
                                            <span class="avatar-title rounded-circle bg-white bg-opacity-25 text-white font-size-22 p-2">
                                                <i class="bx bx-time-five"></i>
                                            </span>
                                        </div>
                                        <p class="font-size-13 text-uppercase mb-1 opacity-75">Pending Orders</p>
                                        <h3 class="mb-0 text-white font-size-24">{{ $stats['pending_count'] ?? 0 }}</h3>
                                    </div>
                                    <div class="pt-2 border-top border-white border-opacity-25 text-white font-size-12">
                                        Awaiting gateway confirmation
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card mini-stat bg-danger text-white shadow-sm">
                                <div class="card-body">
                                    <div class="mb-2">
                                        <div class="float-end">
                                            <span class="avatar-title rounded-circle bg-white bg-opacity-25 text-white font-size-22 p-2">
                                                <i class="bx bx-error-circle"></i>
                                            </span>
                                        </div>
                                        <p class="font-size-13 text-uppercase mb-1 opacity-75">Failed / Expired</p>
                                        <h3 class="mb-0 text-white font-size-24">{{ ($stats['failed_count'] ?? 0) }}</h3>
                                    </div>
                                    <div class="pt-2 border-top border-white border-opacity-25 text-white font-size-12">
                                        <span>Closed: {{ $stats['closed_count'] ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Card -->
                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-body py-3">
                            <form method="GET" action="{{ url('admin/payments') }}" class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label font-size-13 mb-1">Status</label>
                                    <select name="status" class="form-select form-select-sm">
                                        <option value="all" {{ ($filters['status'] ?? '') == 'all' ? 'selected' : '' }}>All Statuses</option>
                                        <option value="paid" {{ ($filters['status'] ?? '') == 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="failed" {{ ($filters['status'] ?? '') == 'failed' ? 'selected' : '' }}>Failed</option>
                                        <option value="closed" {{ ($filters['status'] ?? '') == 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label font-size-13 mb-1">Gateway / Method</label>
                                    <select name="payment_method" class="form-select form-select-sm">
                                        <option value="all" {{ ($filters['payment_method'] ?? '') == 'all' ? 'selected' : '' }}>All Gateways</option>
                                        <option value="razorpay" {{ ($filters['payment_method'] ?? '') == 'razorpay' ? 'selected' : '' }}>Razorpay</option>
                                        <option value="phonepe" {{ ($filters['payment_method'] ?? '') == 'phonepe' ? 'selected' : '' }}>PhonePe</option>
                                        <option value="admin" {{ ($filters['payment_method'] ?? '') == 'admin' ? 'selected' : '' }}>Admin / Manual</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label font-size-13 mb-1">From Date</label>
                                    <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="form-control form-control-sm">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label font-size-13 mb-1">To Date</label>
                                    <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="form-control form-control-sm">
                                </div>

                                <div class="col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                                        <i class="bx bx-filter-alt me-1"></i> Filter
                                    </button>
                                    <a href="{{ url('admin/payments') }}" class="btn btn-light btn-sm" title="Reset Filters">
                                        <i class="bx bx-reset"></i> Reset
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Payment Transactions Table -->
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="card-title mb-0">Transaction Records</h4>
                                <span class="badge bg-light text-dark font-size-13">Total: {{ count($receipts) }} entries</span>
                            </div>

                            <div class="table-responsive">
                                <table id="PaymentsTable" class="table table-bordered table-hover align-middle mb-0 dt-responsive nowrap w-100">
                                    <thead class="table-light">
                                    <tr>
                                        <th style="width: 40px;">#</th>
                                        <th>Order / Trans ID</th>
                                        <th>Member Details</th>
                                        <th>Plan Name</th>
                                        <th>Amount</th>
                                        <th>Gateway</th>
                                        <th>Date & Time</th>
                                        <th>Expiry Date</th>
                                        <th>Status</th>
                                        <th class="text-center" style="width: 70px;">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($receipts as $index => $receipt)
                                        <tr>
                                            <td>{{ method_exists($receipts, 'firstItem') ? $receipts->firstItem() + $index : $index + 1 }}</td>
                                            <td>
                                                <span class="font-monospace fw-semibold font-size-12 text-dark">{{ $receipt->order_id ?? ('#' . $receipt->id) }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-semibold text-primary font-size-14">{{ $receipt->user_name ?? 'N/A' }}</span>
                                                    @if(!empty($receipt->user_profile_id))
                                                        <small class="text-muted"><i class="bx bx-id-card me-1"></i>{{ $receipt->user_profile_id }}</small>
                                                    @endif
                                                    @if(!empty($receipt->user_mobile))
                                                        <small class="text-muted"><i class="bx bx-phone me-1"></i>{{ $receipt->user_mobile }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <span class="badge bg-soft-info text-info font-size-13">{{ $receipt->package }}</span>
                                                    <small class="d-block text-muted">{{ (empty($receipt->month) || $receipt->month == 0) ? 'Without Expiry Date' : $receipt->month . ' Month(s)' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success font-size-14">&#8377; {{ number_format($receipt->amount, 2) }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $method = strtolower($receipt->payment_method ?? $receipt->paid_by ?? 'online');
                                                @endphp
                                                @if(str_contains($method, 'razorpay'))
                                                    <span class="badge bg-primary text-white p-1 px-2"><i class="bx bx-shield-quarter me-1"></i>Razorpay</span>
                                                @elseif(str_contains($method, 'phonepe'))
                                                    <span class="badge bg-purple text-white p-1 px-2" style="background-color: #5f259f !important;"><i class="bx bx-mobile-alt me-1"></i>PhonePe</span>
                                                @elseif(str_contains($method, 'admin') || str_contains($method, 'manual'))
                                                    <span class="badge bg-secondary p-1 px-2"><i class="bx bx-user me-1"></i>Admin</span>
                                                @else
                                                    <span class="badge bg-info p-1 px-2">{{ ucfirst($method) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ !empty($receipt->recharge_date) ? \Carbon\Carbon::parse($receipt->recharge_date)->format('d-M-Y h:i A') : ($receipt->created_at ? \Carbon\Carbon::parse($receipt->created_at)->format('d-M-Y h:i A') : 'N/A') }}
                                                </small>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ (!empty($receipt->expiry_date) && !empty($receipt->month) && (int)$receipt->month > 0) ? \Carbon\Carbon::parse($receipt->expiry_date)->format('d-M-Y') : 'Without Expiry Date' }}
                                                </small>
                                            </td>
                                            <td>
                                                @php
                                                    $st = strtolower($receipt->status ?? 'pending');
                                                @endphp
                                                @if($st === 'paid')
                                                    <span class="badge bg-success font-size-12"><i class="mdi mdi-check-circle me-1"></i>Paid</span>
                                                @elseif($st === 'pending')
                                                    <span class="badge bg-warning font-size-12 text-dark"><i class="mdi mdi-clock-outline me-1"></i>Pending</span>
                                                @elseif($st === 'failed')
                                                    <span class="badge bg-danger font-size-12"><i class="mdi mdi-close-circle me-1"></i>Failed</span>
                                                @elseif($st === 'closed')
                                                    <span class="badge bg-secondary font-size-12"><i class="mdi mdi-archive me-1"></i>Closed</span>
                                                @else
                                                    <span class="badge bg-light text-dark font-size-12">{{ ucfirst($st) }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="showReceiptDetails({{ $receipt->id }})" title="View Transaction Details">
                                                    <i class="bx bx-show font-size-14"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-4 text-muted">
                                                <i class="bx bx-receipt font-size-24 d-block mb-1"></i>
                                                No payment records found.
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @include('admin.includes.footer')
    </div>

    <!-- Transaction Detail Modal -->
    <div class="modal fade" id="paymentDetailModal" tabindex="-1" aria-labelledby="paymentDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="paymentDetailModalLabel"><i class="bx bx-receipt me-2 text-primary"></i>Transaction Breakdown</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="modalContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            if ($('#PaymentsTable').find('tbody tr').length > 0 && !$('#PaymentsTable tbody tr td[colspan]').length) {
                $('#PaymentsTable').DataTable({
                    "responsive": true,
                    "order": [[ 0, "asc" ]],
                    "lengthMenu": [10, 25, 50, 100],
                    "language": {
                        "search": "Search in list: ",
                    }
                });
            }
        });

        function showReceiptDetails(id) {
            var modal = new bootstrap.Modal(document.getElementById('paymentDetailModal'));
            modal.show();

            $('#modalContent').html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>');

            $.ajax({
                url: "{{ url('admin/payments') }}/" + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status && response.receipt) {
                        var r = response.receipt;
                        var statusBadge = '';
                        if (r.status === 'paid') statusBadge = '<span class="badge bg-success">Paid</span>';
                        else if (r.status === 'pending') statusBadge = '<span class="badge bg-warning text-dark">Pending</span>';
                        else if (r.status === 'failed') statusBadge = '<span class="badge bg-danger">Failed</span>';
                        else statusBadge = '<span class="badge bg-secondary">' + r.status + '</span>';

                        var html = `
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-light">
                                        <h6 class="fw-bold text-primary mb-2"><i class="bx bx-user me-1"></i> Customer Information</h6>
                                        <p class="mb-1"><strong>Name:</strong> ${r.user_name || 'N/A'}</p>
                                        <p class="mb-1"><strong>Profile ID:</strong> ${r.user_profile_id || 'N/A'}</p>
                                        <p class="mb-1"><strong>Phone:</strong> ${r.user_mobile || 'N/A'}</p>
                                        <p class="mb-0"><strong>Email:</strong> ${r.user_email || 'N/A'}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-light">
                                        <h6 class="fw-bold text-primary mb-2"><i class="bx bx-credit-card me-1"></i> Payment Overview</h6>
                                        <p class="mb-1"><strong>Order ID:</strong> <span class="font-monospace">${r.order_id || '#' + r.id}</span></p>
                                        <p class="mb-1"><strong>Amount Paid:</strong> <span class="text-success fw-bold">&#8377; ${parseFloat(r.amount).toFixed(2)}</span></p>
                                        <p class="mb-1"><strong>Payment Method:</strong> ${r.payment_method || r.paid_by || 'Online'}</p>
                                        <p class="mb-0"><strong>Status:</strong> ${statusBadge}</p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-3 border rounded">
                                        <h6 class="fw-bold text-primary mb-2"><i class="bx bx-package me-1"></i> Package Entitlements & Usage</h6>
                                        <div class="row text-center g-2 mt-1">
                                            <div class="col-md-4">
                                                <div class="border rounded p-2 bg-white">
                                                    <small class="text-muted d-block">Contacts</small>
                                                    <strong>${r.no_of_contact || 0} Total</strong>
                                                    <div class="text-muted font-size-11">Viewed: ${r.no_of_viewed || 0} | Balance: ${r.balance || 0}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border rounded p-2 bg-white">
                                                    <small class="text-muted d-block">Chat Messages</small>
                                                    <strong>${r.no_of_chats || 0} Total</strong>
                                                    <div class="text-muted font-size-11">Used: ${r.viewed_chats || 0} | Balance: ${r.balance_chats || 0}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border rounded p-2 bg-white">
                                                    <small class="text-muted d-block">Interests</small>
                                                    <strong>${r.no_of_interests || 0} Total</strong>
                                                    <div class="text-muted font-size-11">Used: ${r.viewed_interests || 0} | Balance: ${r.balance_interests || 0}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted font-size-12"><strong>Recharged Date:</strong> ${r.recharge_date || 'N/A'}</p>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <p class="mb-1 text-muted font-size-12"><strong>Valid Till:</strong> ${(!r.month || parseInt(r.month) === 0 || !r.expiry_date) ? 'Without Expiry Date' : r.expiry_date}</p>
                                </div>
                            </div>
                        `;
                        $('#modalContent').html(html);
                    } else {
                        $('#modalContent').html('<div class="alert alert-danger">Failed to load payment details.</div>');
                    }
                },
                error: function() {
                    $('#modalContent').html('<div class="alert alert-danger">An error occurred while loading payment details.</div>');
                }
            });
        }
    </script>
@endsection
