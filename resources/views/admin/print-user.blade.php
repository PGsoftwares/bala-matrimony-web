@extends('admin.layouts.layout')
@section('title', 'Print Profile - ' . ($user->name ?? 'Candidate'))

@section('content')
    <div class="d-print-none">
        @include('admin.includes.header')
        @include('admin.includes.sidebar')
    </div>

    <div class="main-content">
        <div class="page-content pt-3">
            <div class="container-fluid">

                <!-- Action Bar (Hidden on Print) -->
                <div class="row mb-3 d-print-none sticky-top" style="top: 70px; z-index: 999;">
                    <div class="col-12">
                        <div class="card shadow-sm border mb-0 bg-white">
                            <div class="card-body py-2 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <a href="{{ url('admin/user-details/' . $user->id) }}" class="btn btn-outline-secondary">
                                    <i class="bx bx-arrow-back me-1"></i> Back to User Details
                                </a>
                                <div class="d-flex align-items-center gap-2">
                                    <!-- <button type="button" onclick="window.print()" class="btn btn-primary px-3 shadow-sm">
                                        <i class="bx bx-printer font-size-16 me-1 align-middle"></i> Print Profile
                                    </button> -->
                                    <button type="button" onclick="downloadPDF()" id="btnDownloadPdf" class="btn btn-success px-3 shadow-sm" style="background-color: #0d6b38; border-color: #0d6b38;">
                                        <i class="bx bx-download font-size-16 me-1 align-middle"></i> Download PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center" style="padding-top:50px">
                    <div class="col-lg-12">
                        @foreach($userAndUserDetails as $u)
                        <div class="card print-container shadow-none border" id="printable-area" style="background-color: #fff;">
                            <div class="card-body p-3 p-md-4">

                                <!-- Organization Header -->
                                <div class="row align-items-center pb-2 border-bottom border-2 print-section-block">
                                    <div class="col-6">
                                        @if(!empty($db['printInfo']->logo) && file_exists(public_path('printLogo/' . $db['printInfo']->logo)))
                                            <img src="{{ asset('printLogo/' . $db['printInfo']->logo) }}" alt="Bala Matrimony Bureau" style="max-height: 60px; max-width: 230px; object-fit: contain;"/>
                                        @else
                                            <img src="{{ asset('asset/img/logo/pg.webp') }}" alt="Bala Matrimony Bureau" style="max-height: 60px; max-width: 230px; object-fit: contain;"/>
                                        @endif
                                    </div>
                                    <div class="col-6 text-end">
                                        <h5 class="fw-bold mb-1" style="color: #0d6b38;">Bala Matrimony Bureau</h5>
                                        <p class="mb-0 font-size-12 text-muted"><strong>Address:</strong> {{ $db['printInfo']->address ?? 'Head Office' }}</p>
                                        <p class="mb-0 font-size-12 text-muted"><strong>Contact:</strong> {{ $db['printInfo']->contact ?? '' }}</p>
                                    </div>
                                </div>

                                <!-- Profile Summary Bar -->
                                <div class="row my-3 p-2 rounded align-items-center print-section-block" style="background-color: #f8f9fa; border-left: 5px solid #0d6b38;">
                                    <div class="col-auto">
                                        @php
                                            $defaultImage = asset('asset/img/default/default.png');
                                            if (isset($u->gender)) {
                                                $defaultImage = $u->gender === 'Male'
                                                    ? asset('asset/img/default/male.webp')
                                                    : ($u->gender === 'Female' ? asset('asset/img/default/female.webp') : $defaultImage);
                                            }
                                            $profileImg = $defaultImage;
                                            if (!empty($u->profile_image) && file_exists(public_path('Profile Image/' . $u->profile_image))) {
                                                $profileImg = asset('Profile Image/' . $u->profile_image);
                                            }
                                        @endphp
                                        <img src="{{ $profileImg }}" alt="{{ $u->name }}" class="rounded border" style="width: 90px; height: 105px; object-fit: cover;">
                                    </div>
                                    <div class="col">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h3 class="fw-bold mb-1" style="color: #094d27; font-size: 20px;">{{ $u->name }}</h3>
                                                <p class="mb-1 text-muted font-size-13">
                                                    <strong>Matrimony ID:</strong> BMB{{ $user->id ?? $u->user_id ?? $u->id }} &nbsp;|&nbsp; 
                                                    <strong>Profile For:</strong> {{ $u->profile_for ?? 'Self' }}
                                                </p>
                                                <p class="mb-0 text-muted font-size-13">
                                                    <strong>Gender:</strong> {{ $u->gender ?? '-' }} &nbsp;|&nbsp; 
                                                    <strong>Age:</strong> {{ !empty($u->dob) ? \Carbon\Carbon::parse($u->dob)->age . ' Yrs' : '-' }}
                                                </p>
                                            </div>
                                            <div class="col-md-6 text-md-end mt-2 mt-md-0">
                                                <p class="mb-1 text-muted font-size-12"><strong>Registration Date:</strong> {{ !empty($user->created_at) ? \Carbon\Carbon::parse($user->created_at)->format('d-M-Y') : '-' }}</p>
                                                <p class="mb-1 text-muted font-size-12"><strong>Membership:</strong> <span class="badge bg-success font-size-11">{{ !empty($receipt->package) ? $receipt->package : ($u->package ?? 'Standard') }}</span></p>
                                                <p class="mb-0 text-muted font-size-12"><strong>Marital Status:</strong> {{ $u->marital_status ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- SECTION 1: Personal & Physical Details -->
                                <div class="mb-3 print-section-block">
                                    <h5 class="section-title pb-1 mb-2 border-bottom font-size-15" style="color: #0d6b38; border-color: #0d6b38 !important;">
                                        <i class="bx bx-user me-2"></i>1. Personal & Physical Details
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless info-table mb-0">
                                                <tbody>
                                                    <tr><th style="width: 40%;">Full Name</th><td>: {{ $u->name }}</td></tr>
                                                    <tr><th>Gender</th><td>: {{ $u->gender ?? '-' }}</td></tr>
                                                    <tr><th>Date of Birth</th><td>: {{ !empty($u->dob) ? \Carbon\Carbon::parse($u->dob)->format('d-M-Y') : '-' }}</td></tr>
                                                    <tr><th>Birth Time</th><td>: {{ !empty($u->birth_time) ? \Carbon\Carbon::parse($u->birth_time)->format('h:i A') : '-' }}</td></tr>
                                                    <tr><th>Birth Place</th><td>: {{ implode(', ', array_filter([$u->birth_city, $u->birth_state, $u->birth_country])) ?: '-' }}</td></tr>
                                                    <tr><th>Mother Tongue</th><td>: {{ $u->mother_tongue ?? '-' }}</td></tr>
                                                    <tr><th>Marital Status</th><td>: {{ $u->marital_status ?? '-' }}</td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless info-table mb-0">
                                                <tbody>
                                                    <tr><th style="width: 40%;">Height</th><td>: {{ $u->height ?? '-' }}</td></tr>
                                                    <tr><th>Weight</th><td>: {{ $u->weight ? $u->weight . ' Kg' : '-' }}</td></tr>
                                                    <tr><th>Body Type</th><td>: {{ $u->body_type ?? '-' }}</td></tr>
                                                    <tr><th>Skin Tone / Complexion</th><td>: {{ $u->skin_tone ?? '-' }}</td></tr>
                                                    <tr><th>Physical Status</th><td>: {{ $u->physical_status ?? 'Normal' }}</td></tr>
                                                    <tr><th>Eating Habit</th><td>: {{ $u->eating_habit ?? '-' }}</td></tr>
                                                    <tr><th>Drinking / Smoking</th><td>: {{ $u->drinking_habit ?? 'No' }} / {{ $u->smoking_habit ?? 'No' }}</td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- SECTION 2: Religious & Astrological / Horoscope Details -->
                                <div class="mb-3 print-section-block">
                                    <h5 class="section-title pb-1 mb-2 border-bottom font-size-15" style="color: #0d6b38; border-color: #0d6b38 !important;">
                                        <i class="bx bx-sun me-2"></i>2. Religion & Astrological Details
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless info-table mb-0">
                                                <tbody>
                                                    <tr><th style="width: 40%;">Religion</th><td>: {{ $u->religion ?? '-' }}</td></tr>
                                                    <tr><th>Community / Caste</th><td>: {{ $u->caste ?? ($u->new_community ?? '-') }}</td></tr>
                                                    <tr><th>Sub Caste</th><td>: {{ $u->sub_caste ?? '-' }}</td></tr>
                                                    <tr><th>Gothram</th><td>: {{ $u->gothram ?? '-' }}</td></tr>
                                                    <tr><th>Kulam</th><td>: {{ $u->kulam ?? '-' }}</td></tr>
                                                    <tr><th>Family God (Kula Deivam)</th><td>: {{ $u->family_god ?? '-' }}</td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless info-table mb-0">
                                                <tbody>
                                                    <tr><th style="width: 40%;">Rashi / Moon Sign</th><td>: {{ $u->rashi ?? '-' }}</td></tr>
                                                    <tr><th>Nakshatra / Star</th><td>: {{ $u->nakshatra ?? '-' }}</td></tr>
                                                    <tr><th>Lagnam</th><td>: {{ $u->lagnam ?? '-' }}</td></tr>
                                                    <tr><th>Padam</th><td>: {{ $u->padam ?? '-' }}</td></tr>
                                                    <tr><th>Dosham / Chevvai</th><td>: {{ $u->dosham ?? 'No' }}</td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    @if(!empty($u->horoscope_image) && file_exists(public_path('Horoscope Image/' . $u->horoscope_image)))
                                        <div class="mt-2 p-2 border rounded text-center horoscope-chart-box" style="background-color: #fafafa;">
                                            <p class="fw-bold mb-1 text-muted font-size-12">Horoscope Chart</p>
                                            <img src="{{ asset('Horoscope Image/' . $u->horoscope_image) }}" alt="Horoscope Chart" style="max-height: 180px; max-width: 100%; object-fit: contain;">
                                        </div>
                                    @endif
                                </div>

                                <!-- SECTION 3: Education & Career Information -->
                                <div class="mb-3 print-section-block">
                                    <h5 class="section-title pb-1 mb-2 border-bottom font-size-15" style="color: #0d6b38; border-color: #0d6b38 !important;">
                                        <i class="bx bx-briefcase me-2"></i>3. Education & Professional Details
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless info-table mb-0">
                                                <tbody>
                                                    <tr><th style="width: 40%;">Highest Qualification</th><td>: {{ $u->qualification ?? '-' }}</td></tr>
                                                    <tr><th>Education Details</th><td>: {{ $u->education ?? '-' }}</td></tr>
                                                    <tr><th>Employed In</th><td>: {{ $u->employed_in ?? '-' }}</td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless info-table mb-0">
                                                <tbody>
                                                     <tr><th style="width: 40%;">Occupation Type</th><td>: {{ $u->occupation_type ?? '-' }}</td></tr>
                                                     <tr><th>Occupation / Role</th><td>: {{ $u->occupation ?? '-' }}</td></tr>
                                                     <tr><th>Monthly Income</th><td>: @if(empty($u->monthly_income))-@elseif(is_numeric(str_replace(',', '', trim($u->monthly_income))))₹{{ number_format((float)str_replace(',', '', trim($u->monthly_income))) }}@else{{ (str_starts_with(trim($u->monthly_income), '₹') || str_starts_with(trim($u->monthly_income), 'Rs')) ? trim($u->monthly_income) : '₹' . trim($u->monthly_income) }}@endif</td></tr>
                                                    @if(!empty($u->work_country))
                                                        <tr><th>Working Country / Visa</th><td>: {{ $u->work_country }} {{ $u->visa_status ? '(' . $u->visa_status . ')' : '' }}</td></tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- SECTION 4: Family Details -->
                                <div class="mb-3 print-section-block">
                                    <h5 class="section-title pb-1 mb-2 border-bottom font-size-15" style="color: #0d6b38; border-color: #0d6b38 !important;">
                                        <i class="bx bx-home-heart me-2"></i>4. Family Background
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless info-table mb-0">
                                                <tbody>
                                                    <tr><th style="width: 40%;">Father's Name</th><td>: {{ $u->father_name ?? '-' }}</td></tr>
                                                    <tr><th>Father's Profession</th><td>: {{ $u->father_profession ?? '-' }}</td></tr>
                                                    <tr><th>Mother's Name</th><td>: {{ $u->mother_name ?? '-' }}</td></tr>
                                                    <tr><th>Mother's Profession</th><td>: {{ $u->mother_profession ?? '-' }}</td></tr>
                                                    <tr><th>Family Type</th><td>: {{ $u->family_type ?? '-' }}</td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless info-table mb-0">
                                                <tbody>
                                                    <tr><th style="width: 40%;">Family Status</th><td>: {{ $u->family_status ?? '-' }}</td></tr>
                                                    <tr><th>Family Values</th><td>: {{ $u->family_values ?? '-' }}</td></tr>
                                                    <tr><th>Brothers</th><td>: Elder: {{ $u->elder_brother ?? '0' }}, Younger: {{ $u->younger_brother ?? '0' }}, Elder Married: {{ $u->elder_married_brother ?? '0' }}, Younger Married: {{ $u->younger_married_brother ?? '0' }}</td></tr>
                                                    <tr><th>Sisters</th><td>: Elder: {{ $u->elder_sister ?? '0' }}, Younger: {{ $u->younger_sister ?? '0' }}, Elder Married: {{ $u->elder_married_sister ?? '0' }}, Younger Married: {{ $u->younger_married_sister ?? '0' }}</td></tr>
                                                    <tr><th>Property Details</th><td>: {{ $u->property_details ?? '-' }}</td></tr>
                                                    @if(!empty($u->property_info))
                                                        <tr><th>Property Info</th><td>: {{ $u->property_info }}</td></tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @if(!empty($u->about_family))
                                        <div class="mt-2 p-2 bg-light rounded font-size-12">
                                            <strong>About Family:</strong> {{ $u->about_family }}
                                        </div>
                                    @endif
                                </div>

                                <!-- SECTION 5: Location & Contact Details -->
                                <div class="mb-3 print-section-block">
                                    <h5 class="section-title pb-1 mb-2 border-bottom font-size-15" style="color: #0d6b38; border-color: #0d6b38 !important;">
                                        <i class="bx bx-phone-call me-2"></i>5. Contact & Address Information
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless info-table mb-0">
                                                <tbody>
                                                    <tr><th style="width: 40%;">Mobile Number</th><td>: {{ $u->country_code ? '+' . $u->country_code . ' ' : '' }}{{ $u->mobile }}</td></tr>
                                                    <tr><th>Email Address</th><td>: {{ $u->email }}</td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless info-table mb-0">
                                                <tbody>
                                                    <tr><th style="width: 40%;">City / District</th><td>: {{ $u->city ?? '-' }}, {{ $u->state ?? '-' }}</td></tr>
                                                    <tr><th>Country & PIN</th><td>: {{ $u->country ?? '-' }} {{ $u->pin_code ? '- ' . $u->pin_code : '' }}</td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @if(!empty($u->address))
                                        <div class="mt-2 font-size-12">
                                            <strong>Full Address:</strong> {{ $u->address }}, {{ $u->city }}, {{ $u->state }}, {{ $u->country }} {{ $u->pin_code ? '- ' . $u->pin_code : '' }}
                                        </div>
                                    @endif
                                    @if(!empty($u->about_me))
                                        <div class="mt-2 p-2 bg-light rounded font-size-12">
                                            <strong>About Candidate:</strong> {{ $u->about_me }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Footer Note -->
                                <div class="mt-3 pt-2 border-top text-center text-muted font-size-11 print-section-block">
                                    <p class="mb-0">Generated by Bala Matrimony Bureau on {{ date('d-M-Y h:i A') }}. This document is confidential and intended for authorized matrimonial purposes only.</p>
                                </div>

                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function downloadPDF() {
            var element = document.getElementById('printable-area');
            var btn = document.getElementById('btnDownloadPdf');
            if (!element) return;
            
            var originalHtml = btn ? btn.innerHTML : '';
            if (btn) {
                btn.innerHTML = '<i class="bx bx-loader-alt bx-spin font-size-16 me-1 align-middle"></i> Generating PDF...';
                btn.disabled = true;
            }

            var opt = {
                margin:       [8, 8, 8, 8],
                filename:     '{{ preg_replace("/[^A-Za-z0-9_\-]/", "_", $user->name ?? "Candidate") }}_Profile_{{ $user->id }}.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true, logging: false },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' },
                pagebreak:    { mode: ['avoid-all', 'css', 'legacy'], avoid: ['.print-section-block', '.horoscope-chart-box', 'table', 'tr', 'img'] }
            };

            if (typeof html2pdf !== 'undefined') {
                html2pdf().set(opt).from(element).save().then(function() {
                    if (btn) {
                        btn.innerHTML = originalHtml;
                        btn.disabled = false;
                    }
                }).catch(function(err) {
                    console.error('PDF generation error:', err);
                    if (btn) {
                        btn.innerHTML = originalHtml;
                        btn.disabled = false;
                    }
                    window.print();
                });
            } else {
                if (btn) {
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                }
                window.print();
            }
        }
    </script>

    <style>
        .info-table th {
            font-weight: 600;
            color: #495057;
            padding: 3px 6px 3px 0;
            white-space: nowrap;
            font-size: 13px;
        }
        .info-table td {
            color: #212529;
            padding: 3px 0;
            font-size: 13px;
        }
        .print-section-block,
        .horoscope-chart-box,
        table,
        tr,
        img {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
        @media print {
            body {
                background-color: #fff !important;
                font-size: 12px !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .main-content {
                margin-left: 0 !important;
                padding: 0 !important;
            }
            .page-content {
                padding: 0 !important;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
            .card-body {
                padding: 0 !important;
            }
            .d-print-none {
                display: none !important;
            }
            .print-section-block,
            .horoscope-chart-box,
            table,
            tr,
            img {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            @page {
                size: A4;
                margin: 8mm 8mm 8mm 8mm;
            }
        }
    </style>

    <div class="d-print-none">
        @include('admin.includes.footer')
    </div>
@endsection
