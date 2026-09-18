@extends('admin.layouts.layout')
@section('title', 'Enquiry & Complaints')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content background_color">
            <div class="page-content ">
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

                            <h4 class="card-title mb-3">Enquiry & Complaints</h4>
                            <table id="Tables" class="table-bordered">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Subject</th>
                                    <th>Message</th>
                                    <th>Attachment</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($enquiries as $enquiry)
                                    <tr>
                                        <td>{{ $enquiry->id }}</td>
                                        <td>{{ $enquiry->name }}</td>
                                        <td>{{ $enquiry->email }}</td>
                                        <td>{{ $enquiry->mobile }}</td>
                                        <td>{{ $enquiry->subject }}</td>
                                        <td>{{ $enquiry->message }}</td>
                                        <td>
                                            <a href="{{ asset('Attachment/' . $enquiry->attachment) }}" target="_blank">{{ $enquiry->attachment }}</a>
                                        </td>
                                        <td>
                                            @if($enquiry->status != 'unsolved')
                                                <span class="badge bg-success" style="padding: 8px 15px">Solved</span>
                                            @else
                                                <span class="badge bg-danger" style="padding: 8px 15px">Unsolved</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal-{{ $enquiry->id }}">Edit</button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $enquiry->id }}">Delete</button>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal-{{ $enquiry->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Status</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="{{ route('updateEnquiry', $enquiry->id) }}" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="edit-status" class="form-label">Status</label>
                                                            <select class="form-control" name="status" id="edit-status">
                                                                <option value="">Select Status</option>
                                                                <option value="solved" {{ $enquiry->status == 'solved' ? 'selected' : '' }}>Solved</option>
                                                                <option value="unsolved" {{ $enquiry->status == 'unsolved' ? 'selected' : '' }}>Unsolved</option>
                                                            </select>
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

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal-{{ $enquiry->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Complaint or Enquiry</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete the complaint or enquiry "{{ $enquiry->name }}"?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <form action="{{ route('deleteEnquiry', $enquiry->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                responsive: true,
                scrollX: true,
                lengthMenu: [10, 25, 50, 100],
                language: {
                    search: "Search : ",
                }
            });
        });
    </script>
@endsection
