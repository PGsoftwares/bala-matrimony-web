@extends('admin.layouts.layout')
@section('title', 'Profile Edited Users')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content background_color">
            <div class="page-content">
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

                            <h4 class="card-title mb-3">Profile Edited Users</h4>
                            <table id="Tables" class="table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Column</th>
                                    <th>Old Value</th>
                                    <th>New Value</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($editedProfiles as $index => $editedProfile)
                                    <tr>
                                        <td>{{ method_exists($editedProfiles, 'firstItem') ? $editedProfiles->firstItem() + $index : $index + 1 }}</td>
                                        <td><a href="{{ url('admin/user-details/'. $editedProfile->user_id) }}">{{ $editedProfile->user_id }}</a></td>
                                        <td>{{ $editedProfile->field_name }}</td>
                                        <td>{{ $editedProfile->old_value }}</td>
                                        <td>{{ $editedProfile->new_value }}</td>
                                        <td>{{ \Carbon\Carbon::parse($editedProfile->changed_at)->format('d-m-Y h:i A') }}</td>
                                        <td>
                                            <form action="{{ route('editedProfileStatusUpdate', $editedProfile->id) }}" method="post">
                                                @csrf
                                                <div class="d-flex gap-1">
                                                    <select name="status" id="" class="form-select-sm">
                                                        <option value="">-- Select --</option>
                                                        <option value="pending" {{ $editedProfile->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="accepted" {{ $editedProfile->status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                                        <option value="rejected" {{ $editedProfile->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                    </select>
                                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                                </div>
                                            </form>
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
