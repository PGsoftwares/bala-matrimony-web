<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Interest Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .email-header {
            background-color: rgba(150, 237, 79, 0.63);
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }
        .email-body {
            padding: 20px;
            color: #333333;
            line-height: 1.6;
        }
        .email-body h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .email-body p {
            font-size: 16px;
            margin: 10px 0;
        }
        .email-footer {
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #888888;
            background-color: #f9f9f9;
            border-top: 1px solid #dddddd;
        }
        .view-profile-btn {
            display: inline-block;
            margin-top: 20px;
            background-color: rgba(150, 237, 79, 0.63);
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
        }
        .view-profile-btn:hover {
            background-color: rgba(150, 237, 79, 0.63);
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="email-header">
        <h1>You Have a New Interest</h1>
    </div>
    <div class="email-body">
        <h1>Hello!</h1>
        <p><strong>{{ $user->name }}</strong> has shown interest in your profile.</p>
        <p>You can view their profile and learn more about them by clicking the button below:</p>
        <a href="{{ url('/') }}" class="view-profile-btn">View Profile</a>
    </div>
    <div class="email-footer">
        <p>If you have any questions, feel free to contact our support team.</p>
        <p>&copy; {{ date('Y') }} Bala Matrimony Bureau. All rights reserved.</p>
    </div>
</div>
</body>
</html>
