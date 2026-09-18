@extends('admin.layouts.layout')
@section('title', 'Contacted Member Lists')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title mb-3">Contacted Members</h4>
                            <table id="Tables" class="table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($userContacts as $index => $user)
                                    <tr>
                                        <td>{{ method_exists($userContacts, 'firstItem') ? $userContacts->firstItem() + $index : $index + 1 }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->mobile }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="{{ route('contactViewsData', $user->user_id) }}">view</a>
                                        </td>
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
