@extends('web.layouts.layout')
@section('title', 'My Payment History')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header d-flex">
                        <span class="badge p-2">
                            <img class="rounded-circle" src="{{ asset('web/images/icon/icons8-wallet.gif') }}" alt="">
                        </span>
                        <p>Copper</p>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Order #12345</h5>
                        <p class="card-text">Amount: $100.00</p>
                        <p class="card-text">Payment Method: Credit Card</p>
                        <p class="card-text">Status: Completed</p>
                        <a href="#" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">
                        Transaction Date: 2024-07-25
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Order #12346</h5>
                        <p class="card-text">Amount: $50.00</p>
                        <p class="card-text">Payment Method: PayPal</p>
                        <p class="card-text">Status: Pending</p>
                        <a href="#" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{--    <div class="container-lg py-4">--}}

{{--        <h2 class="mb-4 fw-semibold text-primary">--}}
{{--            <i class="bi bi-receipt-cutoff me-2"></i>Payment History--}}
{{--        </h2>--}}

{{--        <div class="card shadow-sm border-0">--}}
{{--            <div class="card-header d-flex justify-content-between align-items-center">--}}
{{--                <h5 class="mb-0">Order History</h5>--}}
{{--                <form method="GET" action="">--}}
{{--                    <button type="submit" class="btn btn-sm btn-primary">Refresh</button>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--            <div class="card-body p-0">--}}
{{--                <div class="table-responsive">--}}
{{--                    <table class="table table-hover mb-0 align-middle">--}}
{{--                        <thead class="table-light sticky-top">--}}
{{--                        <tr>--}}
{{--                            <th>#</th>--}}
{{--                            <th>Order&nbsp;ID</th>--}}
{{--                            <th>Package</th>--}}
{{--                            <th class="text-end">Amount (₹)</th>--}}
{{--                            <th>Purchased On</th>--}}
{{--                            <th>Status</th>--}}
{{--                            <th class="text-center" style="width:140px;">&nbsp;</th>--}}
{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody id="history-body">--}}
{{--                        <tr>--}}
{{--                            <td>1</td>--}}
{{--                            <td>ORD1001</td>--}}
{{--                            <td>Gold Package</td>--}}
{{--                            <td class="text-end">999</td>--}}
{{--                            <td>2025-06-01</td>--}}
{{--                            <td><span class="badge bg-success">Completed</span></td>--}}
{{--                            <td class="text-center"><a href="#" class="btn btn-sm btn-outline-secondary">View</a></td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <td>2</td>--}}
{{--                            <td>ORD1002</td>--}}
{{--                            <td>Silver Package</td>--}}
{{--                            <td class="text-end">599</td>--}}
{{--                            <td>2025-06-10</td>--}}
{{--                            <td><span class="badge bg-warning text-dark">Pending</span></td>--}}
{{--                            <td class="text-center"><a href="#" class="btn btn-sm btn-outline-secondary">View</a></td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <td>3</td>--}}
{{--                            <td>ORD1003</td>--}}
{{--                            <td>Platinum Package</td>--}}
{{--                            <td class="text-end">1499</td>--}}
{{--                            <td>2025-06-15</td>--}}
{{--                            <td><span class="badge bg-danger">Failed</span></td>--}}
{{--                            <td class="text-center"><a href="#" class="btn btn-sm btn-outline-secondary">View</a></td>--}}
{{--                        </tr>--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}


{{--        --}}{{-- little loader --}}
{{--        <div id="loader" class="text-center py-5 d-none">--}}
{{--            <div class="spinner-border" role="status"></div>--}}
{{--        </div>--}}

{{--    </div>--}}


{{--    <script>--}}
{{--        (() => {--}}
{{--            const userId   = {{ Auth::id() }};--}}
{{--            const body     = document.getElementById('history-body');--}}
{{--            const loader   = document.getElementById('loader');--}}
{{--            const apiHist  = "{{ route('phonePePaymentHistory') }}";--}}
{{--            const apiCheck = "{{ route('phonePeCheckStatus') }}";--}}
{{--            const csrf     = "{{ csrf_token() }}";--}}

{{--            // -----------------------------------------------------------------------}}
{{--            // Helpers--}}
{{--            // -----------------------------------------------------------------------}}
{{--            const statusBadge = s => {--}}
{{--                const classes = {--}}
{{--                    paid    : 'bg-success',--}}
{{--                    failed  : 'bg-danger',--}}
{{--                    pending : 'bg-warning text-dark'--}}
{{--                }[s] || 'bg-secondary';--}}
{{--                return `<span class="badge ${classes} text-capitalize">${s}</span>`;--}}
{{--            };--}}

{{--            const money = amt => Number(amt).toLocaleString('en-IN', {--}}
{{--                style   : 'currency',--}}
{{--                currency: 'INR',--}}
{{--                minimumFractionDigits: 0--}}
{{--            });--}}

{{--            // -----------------------------------------------------------------------}}
{{--            // Load history on page load--}}
{{--            // -----------------------------------------------------------------------}}
{{--            async function loadHistory() {--}}
{{--                loader.classList.remove('d-none');--}}
{{--                const res = await fetch(`${apiHist}?user_id=${userId}`);--}}
{{--                loader.classList.add('d-none');--}}

{{--                if (!res.ok) {--}}
{{--                    body.innerHTML =--}}
{{--                        `<tr><td colspan="7" class="text-center text-muted py-5">--}}
{{--                    Unable to load payment history--}}
{{--                  </td></tr>`;--}}
{{--                    return;--}}
{{--                }--}}

{{--                const { payment_history: list } = await res.json();--}}

{{--                if (!list.length) {--}}
{{--                    body.innerHTML =--}}
{{--                        `<tr><td colspan="7" class="text-center text-muted py-5">--}}
{{--                    No payments found--}}
{{--                  </td></tr>`;--}}
{{--                    return;--}}
{{--                }--}}

{{--                body.innerHTML = list.map((row, i) => `--}}
{{--            <tr data-order="${row.orderId}">--}}
{{--                <td>${i + 1}</td>--}}
{{--                <td class="fw-medium">${row.orderId}</td>--}}
{{--                <td>${row.package}</td>--}}
{{--                <td class="text-end">${money(row.amount)}</td>--}}
{{--                <td>${row.purchased_date}</td>--}}
{{--                <td>${statusBadge(row.payment_status)}</td>--}}
{{--                <td class="text-center">--}}
{{--                    ${row.payment_status === 'pending'--}}
{{--                    ? `<button class="btn btn-sm btn-outline-primary check-status"--}}
{{--                                  data-order="${row.orderId}">--}}
{{--                               Check Status--}}
{{--                           </button>`--}}
{{--                    : ''--}}
{{--                }--}}
{{--                </td>--}}
{{--            </tr>--}}
{{--        `).join('');--}}
{{--            }--}}

{{--            // -----------------------------------------------------------------------}}
{{--            // Click-handler for “Check Status”--}}
{{--            // -----------------------------------------------------------------------}}
{{--            body.addEventListener('click', async e => {--}}
{{--                if (!e.target.matches('.check-status')) return;--}}
{{--                const btn      = e.target;--}}
{{--                const orderId  = btn.dataset.order;--}}
{{--                btn.disabled   = true;--}}
{{--                btn.innerHTML  = '<span class="spinner-border spinner-border-sm"></span>';--}}

{{--                try {--}}
{{--                    await fetch(`${apiCheck}?merchantOrderId=${orderId}`, {--}}
{{--                        headers: { 'X-CSRF-TOKEN': csrf }--}}
{{--                    });--}}
{{--                } catch { /* ignore */ }--}}

{{--                await loadHistory();          // refresh list--}}
{{--            });--}}

{{--            document.addEventListener('DOMContentLoaded', loadHistory);--}}
{{--        })();--}}
{{--    </script>--}}
@endsection




