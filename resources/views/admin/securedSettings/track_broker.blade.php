@extends('admin.layouts.layout')
@section('title', 'Track Broker')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="row">
                        <!-- Create Track Broker Form -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Set Track Broker Setting</h5>

                                    <form method="post" action="{{ route('track_broker.store') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label" for="no_of_login-input">No of login</label>
                                            <input type="number" class="form-control" name="no_of_login" id="no_of_login-input" placeholder="Enter no of login...">
                                        </div>
{{--                                        <div class="mb-3">--}}
{{--                                            <label class="form-label" for="no_of_contact-input">No of contact</label>--}}
{{--                                            <input type="number" class="form-control" name="no_of_contact" id="no_of_contact-input" placeholder="Enter no of contact...">--}}
{{--                                        </div>--}}
{{--                                        <div class="mb-3">--}}
{{--                                            <label class="form-label" for="no_of_profile-input">No of profile</label>--}}
{{--                                            <input type="number" class="form-control" name="no_of_profile" id="no_of_profile-input" placeholder="Enter no of profile...">--}}
{{--                                        </div>--}}


                                        <div class="text-end">
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Create Track Broker Form -->

                        <!-- Track broker Table -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-3">Track Broker settings</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered rounded mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>No of login</th>
{{--                                                <th>No of contact</th>--}}
{{--                                                <th>No of profile</th>--}}
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($trackBrokerSettings as $index => $trackBrokerSetting)
                                                <tr>
                                                    <th scope="row">{{ method_exists($trackBrokerSettings, 'firstItem') ? $trackBrokerSettings->firstItem() + $index : $index + 1 }}</th>
                                                    <td>{{ $trackBrokerSetting->no_of_login }}</td>
{{--                                                    <td>{{ $trackBrokerSetting->no_of_contact }}</td>--}}
{{--                                                    <td>{{ $trackBrokerSetting->no_of_profile }}</td>--}}
                                                    <td>
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal-{{ $trackBrokerSetting->id }}">Edit</button>
                                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $trackBrokerSetting->id }}">Delete</button>
                                                    </td>
                                                </tr>

                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal-{{ $trackBrokerSetting->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $trackBrokerSetting->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel-{{ $trackBrokerSetting->id }}">Edit Track broker settings</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST" action="{{ route('track_broker.update', $trackBrokerSetting->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="edit-no_of_login">No of login</label>
                                                                        <input type="number" class="form-control" id="edit-no_of_login" name="no_of_login" value="{{ $trackBrokerSetting->no_of_login }}">
                                                                    </div>
{{--                                                                    <div class="mb-3">--}}
{{--                                                                        <label class="form-label" for="edit-no_of_contact">No of contact</label>--}}
{{--                                                                        <input type="number" class="form-control" id="edit-no_of_contact" name="no_of_contact" value="{{ $trackBrokerSetting->no_of_contact }}">--}}
{{--                                                                    </div>--}}
{{--                                                                    <div class="mb-3">--}}
{{--                                                                        <label class="form-label" for="edit-no_of_profile">No of profile</label>--}}
{{--                                                                        <input type="number" class="form-control" id="edit-no_of_profile" name="no_of_profile" value="{{ $trackBrokerSetting->no_of_profile }}">--}}
{{--                                                                    </div>--}}

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
                                                <div class="modal fade" id="deleteModal-{{ $trackBrokerSetting->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $trackBrokerSetting->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel-{{ $trackBrokerSetting->id }}">Delete Caste</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete the settings?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <form action="{{ route('track_broker.destroy', $trackBrokerSetting->id) }}" method="POST" style="display: inline;">
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
                        <!-- End Track Broker Table -->
                    </div> <!-- row -->

                </div> <!-- container-fluid -->
            </div> <!-- page-content -->
        </div> <!-- main-content -->
        @include('admin.includes.footer')
    </div> <!-- layout-wrapper -->
@endsection
