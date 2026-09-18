@extends('admin.layouts.layout')
@section('title', 'Seasonal Notification')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="row">
                        <!-- Create Seasonal Notification Form -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Send Notification</h5>

                                    @if(session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif


                                    <form method="post" action="{{ route('seasonalNotificationStore') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label" for="title-input">Notification Title</label>
                                            <input type="text" class="form-control" name="title" id="title-input" placeholder="Enter notification title..." required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="message-input">Message</label>
                                            <textarea id="message-input" class="form-control" name="message" rows="4" placeholder="Enter your message..." required></textarea>
                                        </div>

                                        <div class="text-end">
                                            <button class="btn btn-primary" type="submit">Send Notification</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Create Seasonal Notification Form -->

                        <!-- Seasonal Notification List -->
                        <div class="col-md-12 mt-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Sent Seasonal Notifications</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped dt-responsive nowrap w-100">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Title</th>
                                                    <th>Message</th>
                                                    <th>Sent At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($notifications ?? [] as $index => $item)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td><strong>{{ $item->title }}</strong></td>
                                                        <td>{{ $item->message }}</td>
                                                        <td>{{ !empty($item->sent_at) ? \Carbon\Carbon::parse($item->sent_at)->format('d-m-Y H:i:s') : 'N/A' }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">No notifications sent yet.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Seasonal Notification List -->
                    </div> <!-- row -->

                </div> <!-- container-fluid -->
            </div> <!-- page-content -->
        </div> <!-- main-content -->
        @include('admin.includes.footer')
    </div> <!-- layout-wrapper -->
@endsection
