@extends('admin.layouts.layout')
@section('title', 'Coupons')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-3">Coupon List</h4>
                            <table id="Table" class="table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Coupon code</th>
                                    <th>Male</th>
                                    <th>Female</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($coupons as $index => $coupon)
                                    <tr>
                                        <th scope="row">{{ method_exists($coupons, 'firstItem') ? $coupons->firstItem() + $index : $index + 1 }}</th>
                                        <td>{{ $coupon->coupon_code }}</td>
                                        <td>{{ $coupon->male_count }}</td>
                                        <td>{{ $coupon->female_count }}</td>
                                        <td>{{ $coupon->total }}</td>
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
            $('#Table').DataTable({
                "lengthMenu": [10, 25, 50, 100],
                "language": {
                    "search": "Search Coupons : ",
                }
            });
        });
    </script>
@endsection
