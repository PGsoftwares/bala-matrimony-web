<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Reset OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }
        h3 {
            color: #333;
        }
        p {
            color: #555;
            line-height: 1.6;
        }
        .otp {
            font-size: 24px;
            font-weight: bold;
            color: #007BFF;
            padding: 10px;
            border: 1px solid #007BFF;
            border-radius: 4px;
            display: inline-block;
            margin: 20px 0;
        }
        .footer {
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h3>Password Reset OTP</h3>
    <p>Your password reset OTP is:</p>
    <div class="otp">{{ $otp }}</div>
    <p>Please use this OTP to reset your password. The OTP will expire in 24 hours.</p>
    <p>Thank you for using Bala Matrimony Bureau!</p>
    <div class="footer">
        &copy; {{ date('Y') }} Bala Matrimony Bureau. All rights reserved.
    </div>
</div>
</body>
</html>
