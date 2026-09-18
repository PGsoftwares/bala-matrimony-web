@extends('admin.layouts.layout')

@section('title',  'Admin - Table Approval')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">

                    <div class="row">
                        <!-- Create Table Approval Form -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Add Table Approval</h5>

                                    <form method="post" action="{{ route('table-approval.store') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label" for="table_name">Table Name</label>
                                            <select class="form-select" name="table_name" id="table_name" required>
                                                <option value="">Select Table</option>
                                                @foreach($tableNames as $tableName)
                                                    <option value="{{ $tableName }}">{{ $tableName }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="visibility">Visibility</label>
                                            <select class="form-select" name="status" id="visibility">
                                                <option value="enable">Enable</option>
                                                <option value="disable">Disable</option>
                                            </select>
                                        </div>

                                        <div class="text-end">
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Add Table Approval Form -->

                        <!-- Approval Table -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-3">Tables</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered rounded mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Table</th>
                                                <th>Visibility</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($tableApprovals as $index => $tableApproval)
                                                <tr>
                                                    <th scope="row">{{ method_exists($tableApprovals, 'firstItem') ? $tableApprovals->firstItem() + $index : $index + 1 }}</th>
                                                    <td>{{ $tableApproval->table_name }}</td>
                                                    <td>{{ $tableApproval->status }}</td>
                                                    <td>
                                                        <!-- Edit Button -->
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal-{{ $tableApproval->id }}">Edit</button>
                                                        <!-- Delete Button -->
                                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $tableApproval->id }}">Delete</button>
                                                    </td>
                                                </tr>

                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal-{{ $tableApproval->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $tableApproval->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel-{{ $tableApproval->id }}">Edit Table Approval</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST" action="{{ route('table-approval.update', $tableApproval->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-table">Table Name</label>
                                                                        <select class="form-select" name="table_name" id="edit-table" required>
                                                                            @foreach($tableNames as $tableName)
                                                                                <option value="{{ $tableName }}" {{ $tableApproval->table_name == $tableName ? 'selected' : '' }}>
                                                                                    {{ $tableName }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-visibility">Visibility</label>
                                                                        <select class="form-select" id="edit-visibility" name="status">
                                                                            <option value="enable" {{ $tableApproval->status == 'enable' ? 'selected' : '' }}>Enable</option>
                                                                            <option value="disable" {{ $tableApproval->status == 'disable' ? 'selected' : '' }}>Disable</option>
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
                                                <!-- End Edit Modal -->

                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal-{{ $tableApproval->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $tableApproval->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel-{{ $tableApproval->id }}">Delete Approval</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete the Approval "{{ $tableApproval->table_name }}"?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <form action="{{ route('table-approval.destroy', $tableApproval->id) }}" method="POST" style="display: inline;">
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
                                            </tbody>
                                        </table>
                                    </div>

                                    {{--Pagination start--}}
                                    <div class="d-flex justify-content-end mt-2">
                                        {{ $tableApprovals->links('vendor.pagination.bootstrap-5') }}
                                    </div>
                                    {{--End: Pagination--}}

                                </div>
                            </div>
                        </div>
                        <!-- End  Approval Table -->
                    </div> <!-- row -->

                </div> <!-- container-fluid -->
            </div> <!-- page-content -->
        </div> <!-- main-content -->
        @include('admin.includes.footer')
    </div> <!-- layout-wrapper -->
@endsection

