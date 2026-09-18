@extends('admin.layouts.layout')
@section('title', 'User Verifications')

@section('content')
    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">

                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title mb-3">User Verifications</h4>
                            <table id="Tables" class="table-bordered">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Email Verification</th>
                                    <th>Mobile Verification</th>
                                    <th>Photo</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($userVerifications as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->mobile }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->email_verified_at)
                                                <span class="badge bg-success" style="padding: 8px 15px">Verified</span>
                                            @else
                                                <span class="badge bg-danger" style="padding: 8px 15px">Unverified</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->otp_verified_at)
                                                <span class="badge bg-success" style="padding: 8px 15px">Verified</span>
                                            @else
                                                <span class="badge bg-danger" style="padding: 8px 15px">Unverified</span>
                                            @endif
                                        </td>
                                        <td id="photoViewer" class="imageViewer">
                                            @if(!empty($user->photo) && file_exists(public_path('Photos/' . $user->photo)))
                                                <img src="{{ asset('Photos/' . $user->photo) }}" alt="" style="max-width: 100%; height: 200px; cursor: zoom-in">

                                                <form action="{{ route('photoVerify', $user->id) }}" method="post" class="mt-2">
                                                    @csrf
                                                    <select name="photo_verified_at" class="form-select mb-1">
                                                        <option value="pending" {{ $user->photo_verified_at === 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="verified" {{ $user->photo_verified_at === 'verified' ? 'selected' : '' }}>Verify</option>
                                                        <option value="not verified" {{ $user->photo_verified_at === 'not verified' ? 'selected' : '' }}>Reject</option>
                                                    </select>
                                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                                </form>
                                            @endif
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

    <link rel="stylesheet" href="{{ asset('asset/viewer/viewer.css') }}">
    <script src="{{ asset('asset/viewer/viewer.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#Tables').DataTable({
                "responsive": true,
                "lengthMenu": [10, 25, 50, 100],
                "language": {
                    "search": "Search User:",
                }
            });
        });

        window.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.imageViewer').forEach(function (el) {
                new Viewer(el, {
                    inline: false,
                    navbar: false,
                    toolbar: true,
                    tooltip: true,
                    movable: true,
                    zoomable: true,
                    rotatable: false,
                    scalable: false,
                    transition: true,
                });
            });
        });
    </script>
@endsection
