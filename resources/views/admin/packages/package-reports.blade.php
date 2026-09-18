@extends('admin.layouts.layout')
@section('title', 'Membership Plan History')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="card">
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <h4 class="card-title mb-3">Membership Plan History</h4>
                            <table id="Tables" class="table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Plan</th>
                                    <th>Cost</th>
                                    <th>Paid By</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($receipts as $index => $receipt)
                                    <tr>
                                        <td>{{ method_exists($receipts, 'firstItem') ? $receipts->firstItem() + $index : $index + 1 }}</td>
                                        <td>{{ $receipt->name }}</td>
                                        <td>{{ $receipt->package }}</td>
                                        <td>{{ $receipt->amount }}</td>
                                        <td>{{ $receipt->paid_by }}</td>
                                        <td>{{ \Carbon\Carbon::parse($receipt->recharge_date)->format('d-m-Y h:i A') }}</td>
                                        <td>{{ $receipt->status }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @include('admin.includes.footer')
    </div>

    <script>
        $(document).ready(function () {
            $('#Tables').DataTable({
                "responsive": true,
                "lengthMenu": [10, 25, 50, 100],
                "language": {
                    "search": "Search : ",
                }
            });
        });
    </script>
@endsection
