@extends('admin.layouts.layout')
@section('title', 'Contact viewed data')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">

                    <!-- Contact Viewed By This User -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-3">Contact Viewed</h4>
                            <table id="viewedTable" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($viewedContacts as $index => $user)
                                    <tr>
                                        <td>{{ method_exists($viewedContacts, 'firstItem') ? $viewedContacts->firstItem() + $index : $index + 1 }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->mobile }}</td>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Users Who Viewed This User -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-3">Who Viewed Contact</h4>
                            <table id="viewerTable" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($viewerContacts as $index => $user)
                                    <tr>
                                        <td>{{ method_exists($viewerContacts, 'firstItem') ? $viewerContacts->firstItem() + $index : $index + 1 }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->mobile }}</td>
                                        <td>{{ $user->email }}</td>
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
            $('#viewedTable, #viewerTable').DataTable({
                "responsive": true,
                "lengthMenu": [10, 25, 50, 100],
                "language": {
                    "search": "Search : ",
                }
            });
        });
    </script>
@endsection
