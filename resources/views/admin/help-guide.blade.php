@extends('admin.layouts.layout')
@section('title', 'Help Guide')

@section('content')
    @include('admin.includes.header')
    @include('admin.includes.sidebar')

    <div class="main-content ">
        <div class="page-content background_color">
            <div class="container-fluid">


                <!-- Accordion for Help Sections -->
                <div class="accordion" id="helpAccordion">
                    <!-- 1. Admin -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingAdmin">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdmin" aria-expanded="false" aria-controls="collapseAdmin">
                                1. Admin Dashboard
                            </button>
                        </h2>
                        <div id="collapseAdmin" class="accordion-collapse collapse" aria-labelledby="headingAdmin" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                <h6 class="fw-semibold">Users:</h6>
                                <ul class="list-group list-group-flush mb-3">
                                    <li class="list-group-item"><strong>Total Users:</strong> Shows the total number of users registered on the platform.</li>
                                    <li class="list-group-item"><strong>Male Users:</strong> Displays the number of users registered as male.</li>
                                    <li class="list-group-item"><strong>Female Users:</strong> Displays the number of users registered as female.</li>
                                    <li class="list-group-item"><strong>New Users Today:</strong> Count of users registered on the current day.</li>
                                    <li class="list-group-item"><strong>New Users This Month:</strong> Count of users registered during the current month.</li>
                                    <li class="list-group-item"><strong>New Users This Year:</strong> Count of users registered during the current year.</li>
                                </ul>

                                <h6 class="fw-semibold">Membership Packages:</h6>
                                <ul class="list-group list-group-flush mb-3">
                                    <li class="list-group-item"><strong>Subscribed User Count</strong></li>
                                </ul>

                                <div class="alert alert-primary">
                                    <i class="bi bi-lightbulb me-1"></i>
                                    Tip: Use this dashboard daily to track growth, analyze package usage, and oversee platform demographics.
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 2. Registration -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOtp">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOtp" aria-expanded="false" aria-controls="collapseOtp">
                                2. Registration
                            </button>
                        </h2>
                        <div id="collapseOtp" class="accordion-collapse collapse" aria-labelledby="headingOtp" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                <ol class="mb-3">
                                    <li>Go to the <strong>Registration</strong> page.</li>
                                    <li>Fill in your basic details:
                                        <ul>
                                            <li>Name</li>
                                            <li>Email</li>
                                            <li>Mobile Number</li>
                                            <li>Password</li>
                                        </ul>
                                    </li>
                                    <li>Click <span class="text-primary fw-semibold">Register</span>.</li>
                                    <li>After successful verification, you’ll be redirected to complete your profile.</li>
                                </ol>

                            </div>
                        </div>
                    </div>
                    <!-- 3. Member List and Search -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingMembers">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMembers" aria-expanded="false" aria-controls="collapseMembers">
                                3. All Members List & Search
                            </button>
                        </h2>
                        <div id="collapseMembers" class="accordion-collapse collapse" aria-labelledby="headingMembers" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                <p>The <strong>All Members</strong> page allows administrators to view and manage every registered profile with powerful search and filter tools.</p>

                                <h6 class="fw-semibold">Search & Filter Options:</h6>
                                <ul class="list-group list-group-flush mb-3">
                                    <li class="list-group-item"><strong>User ID:</strong> Search using unique profile ID.</li>
                                    <li class="list-group-item"><strong>Name:</strong> Filter users by their full name.</li>
                                    <li class="list-group-item"><strong>Mobile Number:</strong> Look up users by phone number.</li>
                                    <li class="list-group-item"><strong>Email Address:</strong> Search by registered email.</li>
                                    <li class="list-group-item"><strong>From Date & To Date:</strong> Filter users based on their registration date range.</li>
                                    <li class="list-group-item"><strong>Profile Status:</strong> Choose between <span class="badge bg-primary">Approved</span>, <span class="badge bg-warning text-dark">Pending</span>, or <span class="badge bg-danger">Rejected</span> profiles.</li>
                                </ul>

                                <h6 class="fw-semibold">Member List View:</h6>
                                <ul class="list-group list-group-flush mb-3">
                                    <li class="list-group-item">Each row includes user ID, name, contact info and profile status.</li>
                                    <li class="list-group-item">Admins can click to <strong>view full profile</strong> or take actions like approve/reject/delete.</li>
                                </ul>

                            </div>
                        </div>
                    </div>
                    <!-- 4. Payment Plans & Package Update -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingPaymentPlans">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePaymentPlans" aria-expanded="false" aria-controls="collapsePaymentPlans">
                                4. Payment Plans & Package Update
                            </button>
                        </h2>
                        <div id="collapsePaymentPlans" class="accordion-collapse collapse" aria-labelledby="headingPaymentPlans" data-bs-parent="#helpAccordion">
                            <div class="accordion-body">
                                <h6 class="fw-semibold">Upgrade to a Premium Package in Simple Steps:</h6>
                                <ol class="mb-3">
                                    <li>Navigate to the <strong>Payment Plans</strong> page to view available packages.</li>
                                    <li>Select your preferred package (Silver, Gold, etc.).</li>
                                    <li>Click on <strong>Pay Now</strong> to proceed with online payment via the secure gateway.</li>
                                    <li>Upon successful payment, your package will be automatically updated.</li>
                                    <li>You can verify this on the <strong>My Plan Details</strong> screen.</li>
                                </ol>

                                <h6 class="fw-semibold">Manual Payment via Scanner or Bank Transfer:</h6>
                                <ul class="list-group list-group-flush mb-3">
                                    <li class="list-group-item">If you paid using a QR scanner or transferred to the admin account manually, your package will <strong>not be updated automatically</strong>.</li>
                                    <li class="list-group-item">Please contact the admin with your <strong>payment screenshot or transaction ID</strong>.</li>
                                    <li class="list-group-item">After verification, the admin will manually activate your package.</li>
                                </ul>

                                <div class="alert alert-info">
                                    <i class="bi bi-clock-history me-1"></i>
                                    Manual payments may take a few hours to reflect depending on admin verification.
                                </div>
                            </div>
                        </div>
                    </div>


                </div>


            </div>
        </div>
    </div>

    @include('admin.includes.footer')
@endsection
