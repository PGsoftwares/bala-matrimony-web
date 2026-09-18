@extends('admin.layouts.layout')
@section('title', 'Membership Packages')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="row">
                        <!-- Create Packages Form -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Add Packages</h5>

                                    <form method="post" action="{{ route('packages.store') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label" for="package-input">Package</label>
                                                <input type="text" class="form-control" name="name" id="package-input" placeholder="Enter Package...">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label" for="amount-input">Amount</label>
                                                <input type="number"  class="form-control" name="amount" id="amount-input" placeholder="Enter Amount...">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label" for="month-input">Month <small class="text-muted">(0 for Without Expiry Date)</small></label>
                                                <input type="number" min="0" class="form-control" name="month" id="month-input" placeholder="Enter Month (0 for Without Expiry Date)..." value="0">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label" for="contact-input">Number of Contacts</label>
                                                <input type="number"  class="form-control" name="no_of_contact" id="contact-input" placeholder="Enter Contact count...">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label" for="chat-input">Number of Chats</label>
                                                <input type="number"  class="form-control" name="no_of_chats" id="chat-input" placeholder="Enter Chat count...">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label" for="interest-input">Number of Interests</label>
                                                <input type="number"  class="form-control" name="no_of_interests" id="interest-input" placeholder="Enter Interest count...">
                                            </div>
                                        </div>

                                        <div class="text-end">
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Create Packages Form -->

                        <!-- Packages Table -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-3">Packages Details</h4>

                                    @if (session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif


                                    <div class="table-responsive">
                                        <table class="table table-bordered rounded mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Package</th>
                                                <th>Amount</th>
                                                <th>Month</th>
                                                <th>No of Contact</th>
                                                <th>No of Chats</th>
                                                <th>No of Interests</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($packages as $index => $package)
                                                <tr>
                                                    <th scope="row">{{ method_exists($packages, 'firstItem') ? $packages->firstItem() + $index : $index + 1 }}</th>
                                                    <td>{{ $package->name }}</td>
                                                    <td>{{ $package->amount }}</td>
                                                    <td>{{ (empty($package->month) || $package->month == 0) ? 'Without Expiry Date' : $package->month . ' Month' . ($package->month > 1 ? 's' : '') }}</td>
                                                    <td>{{ $package->no_of_contact }}</td>
                                                    <td>{{ $package->no_of_chats }}</td>
                                                    <td>{{ $package->no_of_interests }}</td>
                                                    <td>
                                                        <!-- Edit Button -->
                                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal-{{ $package->id }}">Edit</button>
                                                        <!-- Delete Button -->
                                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $package->id }}">Delete</button>
                                                    </td>
                                                </tr>

                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal-{{ $package->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $package->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel-{{ $package->id }}">Edit Package</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST" action="{{ route('packages.update', $package->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-packages">Package</label>
                                                                        <input type="text" class="form-control" id="edit-packages" name="name" value="{{ $package->name }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-amount">Amount</label>
                                                                        <input type="number" step="1.00" class="form-control" id="edit-amount" name="amount" value="{{ $package->amount }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-month">Month <small class="text-muted">(0 for Without Expiry Date)</small></label>
                                                                        <input type="number" min="0" class="form-control" id="edit-month" name="month" value="{{ $package->month }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-contact">Number of Contacts</label>
                                                                        <input type="number" class="form-control" id="edit-contact" name="no_of_contact" value="{{ $package->no_of_contact }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="chat-edit">Number of Chats</label>
                                                                        <input type="number"  class="form-control" name="no_of_chats" id="chat-edit" value="{{ $package->no_of_chats }}" placeholder="Enter Chat count...">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="interest-edit">Number of Interests</label>
                                                                        <input type="number"  class="form-control" name="no_of_interests" id="interest-edit" value="{{ $package->no_of_interests }}" placeholder="Enter Interest count...">
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
                                                <div class="modal fade" id="deleteModal-{{ $package->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $package->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel-{{ $package->id }}">Delete Package</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete the Package "{{ $package->name }}"?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <form action="{{ route('packages.destroy', $package->id) }}" method="POST" style="display: inline;">
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
                                </div>
                            </div>
                        </div>
                        <!-- End Packages Table -->
                    </div> <!-- row -->

                </div> <!-- container-fluid -->
            </div> <!-- page-content -->
        </div> <!-- main-content -->
        @include('admin.includes.footer')
    </div> <!-- layout-wrapper -->
@endsection


