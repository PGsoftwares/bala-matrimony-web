@extends('admin.layouts.layout')
@section('title', 'Profile Export')

@section('content')

    <div id="layout-wrapper">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')

        <div class="main-content">
            <div class="page-content background_color">
                <div class="container-fluid">
                    <div class="row">
                        {{-- Filter Form --}}
                        <form method="GET" action="{{ route('Export') }}" class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label for="from_date" class="form-label">From Date</label>
                                <input type="date" id="from_date" name="from_date" class="form-control" value="{{ $from ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <label for="to_date" class="form-label">To Date</label>
                                <input type="date" id="to_date" name="to_date" class="form-control" value="{{ $to ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select name="gender" id="gender" class="form-select">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ ($gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ ($gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-grid">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </form>

                        {{-- User Table --}}
                        <div class="mt-4" style="overflow-x: scroll">
                            <table class="table table-bordered table-striped" id="userTable" >
                                <thead class="">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Gender</th>
                                    <th>DOB</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Profile For</th>
                                    <th>Birth Time</th>
                                    <th>Birth Country</th>
                                    <th>Mother Tongue</th>
                                    <th>Marital status</th>
                                    <th>Skin Tone</th>
                                    <th>Height</th>
                                    <th>Body Type</th>
                                    <th>Physical Status</th>
                                    <th>Eating Habit</th>
                                    <th>Drinking Habit</th>
                                    <th>Smoking Habit</th>
                                    <th>Religion</th>
                                    <th>Caste</th>
                                    <th>Education</th>
                                    <th>Occupation</th>
                                    <th>Employed In</th>
                                    <th>Monthly Income</th>
                                    <th>Father Status</th>
                                    <th>Mother Status</th>
                                    <th>Family Type</th>
                                    <th>Family Status</th>
                                    <th>Family Values</th>
                                    <th>Elder Brother</th>
                                    <th>Younger Brother</th>
                                    <th>Elder Married Brother</th>
                                    <th>Younger Married Brother</th>
                                    <th>Elder Sister</th>
                                    <th>Younger Sister</th>
                                    <th>Elder Married Sister</th>
                                    <th>Younger Married Sister</th>
                                    <th>Property Details</th>
                                    <th>Rashi</th>
                                    <th>Gothram</th>
                                    <th>Dosha</th>
                                    <th>Country</th>
                                    <th>State</th>
                                    <th>City</th>
                                    <th>Registration Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->gender }}</td>
                                        <td>{{ \Carbon\Carbon::parse($user->dob)->format('d-m-Y') }}</td>
                                        <td>{{ $user->mobile }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->profile_for }}</td>
                                        <td>{{ $user->birth_time }}</td>
                                        <td>{{ $user->birth_country }}</td>
                                        <td>{{ $user->mother_tongue }}</td>
                                        <td>{{ $user->marital_status }}</td>
                                        <td>{{ $user->skin_tone }}</td>
                                        <td>{{ $user->height }}</td>
                                        <td>{{ $user->body_type }}</td>
                                        <td>{{ $user->physical_status }}</td>
                                        <td>{{ $user->eating_habit }}</td>
                                        <td>{{ $user->drinking_habit }}</td>
                                        <td>{{ $user->smoking_habit }}</td>
                                        <td>{{ $user->religion }}</td>
                                        <td>{{ $user->caste }}</td>
                                        <td>{{ $user->education }}</td>
                                        <td>{{ $user->occupation }}</td>
                                        <td>{{ $user->employed_in }}</td>
                                        <td>{{ $user->monthly_income }}</td>
                                        <td>{{ $user->father_profession }}</td>
                                        <td>{{ $user->mother_profession }}</td>
                                        <td>{{ $user->family_type }}</td>
                                        <td>{{ $user->family_status }}</td>
                                        <td>{{ $user->family_values }}</td>
                                        <td>{{ $user->elder_brother }}</td>
                                        <td>{{ $user->younger_brother }}</td>
                                        <td>{{ $user->elder_married_brother }}</td>
                                        <td>{{ $user->younger_married_brother }}</td>
                                        <td>{{ $user->elder_sister }}</td>
                                        <td>{{ $user->younger_sister }}</td>
                                        <td>{{ $user->elder_married_sister }}</td>
                                        <td>{{ $user->younger_married_sister }}</td>
                                        <td>{{ $user->property_details }}</td>
                                        <td>{{ $user->rashi }}</td>
                                        <td>{{ $user->gothram }}</td>
                                        <td>{{ $user->dosham }}</td>
                                        <td>{{ $user->country }}</td>
                                        <td>{{ $user->state }}</td>
                                        <td>{{ $user->city }}</td>
                                        <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d-m-Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center text-muted" colspan="46">
                                            No users found for the selected filters.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        @include('admin.includes.footer')
    </div>


    <link rel="stylesheet" href="{{asset('asset/datatable/dataTables.dataTables.min.css')}}">
    <script src="{{ asset('asset/datatable/dataTables.min.js') }}"></script>
    <style>
        #userTable th,
        #userTable td {
            white-space: nowrap;
        }
    </style>
    @if($users->count())
        <script>
            $(document).ready(function () {
                $('#userTable').DataTable({
                    lengthMenu: [10, 25, 50, { label: 'All', value: -1 }],
                    scrollX: true,
                    layout: {
                        topEnd: {
                            buttons: [
                                {
                                    extend: 'copy',
                                    title: 'Bala Matrimony Bureau',
                                    filename: 'bala_matrimony_bureau'
                                },
                                {
                                    extend: 'excel',
                                    title: 'Bala Matrimony Bureau',
                                    filename: 'bala_matrimony_bureau'
                                },
                                {
                                    extend: 'pdf',
                                    title: 'Bala Matrimony Bureau',
                                    filename: 'bala_matrimony_bureau',
                                    orientation: 'landscape',
                                    pageSize: 'A3'
                                }
                            ]
                        }
                    }
                });
            });
        </script>
    @endif

@endsection
