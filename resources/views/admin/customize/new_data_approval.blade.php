@extends('admin.layouts.layout')
@section('title', 'Admin | User Requirements')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title mb-3">User Requirements</h4>
                            <table id="Tables" class="table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Community</th>
                                    <th>State</th>
                                    <th>City</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($newDatas as $index => $newData)
                                    <tr>
                                        <td>{{ method_exists($newDatas, 'firstItem') ? $newDatas->firstItem() + $index : $index + 1 }}</td>
                                        <td>
                                            <a href="{{ url('admin/user-details/'. $newData->id) }}">{{ $newData->id }}</a>
                                        </td>
                                        <td>{{ $newData->new_community }}</td>
                                        <td>{{ $newData->new_state }}</td>
                                        <td>{{ $newData->new_city }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
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

    @include('admin.includes.footer')
@endsection
