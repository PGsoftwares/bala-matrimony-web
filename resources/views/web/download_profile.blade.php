<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Marriage Profile</title>
    <style>
        @font-face {
            font-family: 'NotoSansTamil';
            src: url('{{ public_path('fonts/NotoSansTamil-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        body {
            font-family: 'notosanstamil', DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }
        .a4-sheet {
            width: 100%;
            padding: 15px;
            margin: auto;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header img {
            height: 50px;
        }
        .watermark img {
            opacity: 0.06;
            position: absolute;
            top: 40%;
            left: 25%;
            transform: rotate(-30deg);
            width: 300px;
        }
        .section {
            margin-top: 10px;
        }
        .section-title {
            font-size: 14px;
            border-bottom: 1px solid #ccc;
            margin-bottom: 6px;
            padding-bottom: 4px;
            color: #0d6efd;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 6px;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            width: 160px;
        }
        .profile-image, .horoscope-image {
            width: 180px;
            height: 230px;
            object-fit: cover;
            border: 1px solid #ccc;
            margin-top: 8px;
        }
        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: justify;
            color: #555;
            border-top: 1px dashed #ccc;
            padding-top: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .footer .left {
            flex: 1;
            text-align: left;
        }

        .footer .right {
            flex: 1;
            text-align: right;
        }

        .footer img {
            height: 20px;
            vertical-align: middle;
        }
    </style>
</head>
<body>
<div class="a4-sheet">
    <div class="watermark">
        <img src="{{ public_path('web/images/logo.png') }}" alt="Watermark">
    </div>

    <div class="header">
        <img src="{{ public_path('web/images/logo.png') }}" alt="Logo"><br>
        <strong>Bala Matrimony Bureau</strong><br>
        Email: support@pgmatrimony.in | Mobile: +91-9042218777
    </div>

    <div style="text-align:center; font-weight:bold; background-color:#aab1a9; color:white; padding:5px 0;">
        ID: BMB{{ $user->id }} | Caste: {{ $userDetail->caste }} | Caste: {{ $userDetail->sub_caste }}
    </div>

    <div style="display: flex; justify-content: space-between;">
        <div style="width: 65%;">
            <table class="info-table">
                <tr>
                    <td class="info-label">Name</td>
                    <td>: {{ $user->name }}</td>
                    <td rowspan="10">
                        @if($userDetail->profile_image)
                            <img src="{{ public_path('Profile Image/'. $userDetail->profile_image) }}" class="profile-image" alt="">
                        @endif
                    </td>
                </tr>
                <tr><td class="info-label">Date of Birth</td><td>: {{ \Carbon\Carbon::parse($userDetail->dob)->format('d.m.Y') }}</td></tr>
                <tr><td class="info-label">Height</td><td>: {{ $userDetail->height }}</td></tr>
                <tr><td class="info-label">Marital Status</td><td>: {{ $userDetail->marital_status }}</td></tr>
                <tr><td class="info-label">Rashi</td><td>: {{ $userDetail->rashi }}</td></tr>
                <tr><td class="info-label">Nakshatra</td><td>: {{ $userDetail->nakshatra }}</td></tr>
                <tr><td class="info-label">Gothram</td><td>: {{ $userDetail->gothram ?? '-' }}</td></tr>
                <tr><td class="info-label">Dosham</td><td>: {{ $userDetail->dosham }}</td></tr>
                <tr><td class="info-label">Father</td><td>: {{ !empty($userDetail->father_name) ? $userDetail->father_name . (!empty($userDetail->father_profession) ? ' (' . $userDetail->father_profession . ')' : '') : ($userDetail->father_profession ?? '-') }}</td></tr>
                <tr>
                    <td class="info-label">Mother</td>
                    <td>: {{ !empty($userDetail->mother_name) ? $userDetail->mother_name . (!empty($userDetail->mother_profession) ? ' (' . $userDetail->mother_profession . ')' : '') : ($userDetail->mother_profession ?? '-') }}</td>
                    <td rowspan="10">
                        @if($userDetail->horoscope_image)
                            <img src="{{ public_path('Horoscope Image/'.$userDetail->horoscope_image) }}" class="horoscope-image" alt="">
                        @endif
                    </td>
                </tr>
                <tr><td class="info-label">Religion</td><td>: {{ $userDetail->religion }}</td></tr>
                <tr><td class="info-label">Caste</td><td>: {{ $userDetail->caste }} {{ !empty($userDetail->sub_caste) ? '(' . $userDetail->sub_caste . ')' : '' }}</td></tr>
                <tr><td class="info-label">Brother</td><td>: Elder: {{ $userDetail->elder_brother ?? '0' }}, Younger: {{ $userDetail->younger_brother ?? '0' }}, Elder Married: {{ $userDetail->elder_married_brother ?? '0' }}, Younger Married: {{ $userDetail->younger_married_brother ?? '0' }}</td></tr>
                <tr><td class="info-label">Sister</td><td>: Elder: {{ $userDetail->elder_sister ?? '0' }}, Younger: {{ $userDetail->younger_sister ?? '0' }}, Elder Married: {{ $userDetail->elder_married_sister ?? '0' }}, Younger Married: {{ $userDetail->younger_married_sister ?? '0' }}</td></tr>
                <tr><td class="info-label">Education</td><td>: {{ $userDetail->education }}</td></tr>
                <tr><td class="info-label">Occupation</td><td>: {{ $userDetail->occupation }}</td></tr>
                <tr><td class="info-label">Income</td><td>: @if(empty($userDetail->monthly_income))-@elseif(is_numeric(str_replace(',', '', trim($userDetail->monthly_income))))₹{{ number_format((float)str_replace(',', '', trim($userDetail->monthly_income))) }} / Month@else{{ (str_starts_with(trim($userDetail->monthly_income), '₹') || str_starts_with(trim($userDetail->monthly_income), 'Rs')) ? trim($userDetail->monthly_income) : '₹' . trim($userDetail->monthly_income) }} / Month@endif</td></tr>
                <tr><td class="info-label">Property</td><td>: {{ $userDetail->property_details }} {{ !empty($userDetail->property_info) ? '(' . $userDetail->property_info . ')' : '' }}</td></tr>
                <tr><td class="info-label">Resident city</td><td>: {{ $user->city }}</td></tr>
                <tr><td class="info-label">Mobile</td><td>: +91 {{ $user->mobile }}</td></tr>
            </table>
        </div>
        <div style="width: 30%; text-align: right;">
            @if($userDetail->profile_image)
                <img src="{{ public_path('Profile Image/'. $userDetail->profile_image) }}" class="profile-image" alt="">
            @endif
            <br>
            @if($userDetail->horoscope_image)
                <img src="{{ public_path('Horoscope Image/'.$userDetail->horoscope_image) }}" class="horoscope-image" alt="">
            @endif
        </div>
    </div>

    <div class="footer">
        <div class="left">
            For more details visit <strong>www.pgmatrimony.in</strong>
        </div>
        <div class="right">
            Powered by <img src="{{ public_path('web/images/logo.png') }}" alt="Logo">
        </div>
    </div>
</div>
</body>
</html>
