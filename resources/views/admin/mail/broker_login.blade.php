<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Broker Alert</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4;">
<table width="100%" cellpadding="0" cellspacing="0" style="margin: 0; padding: 0;">
    <tr>
        <td align="center" style="padding: 20px;">
            <table width="600px" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); overflow: hidden;">
                <!-- Header Section -->
                <tr>
                    <td style="background-color: #f07525; padding: 20px; text-align: center; color: #ffffff;">
                        <h1 style="margin: 0; font-size: 24px;">Broker Alert</h1>
                    </td>
                </tr>
                <!-- Body Section -->
                <tr>
                    <td style="padding: 20px; text-align: left;">
                        <p style="font-size: 16px; margin: 0 0 10px;">
                            The user <strong style="color: #f07525;">{{ $user->name }}</strong> has logged in {{ $user->login_count }} times.
                        </p>
                        <p style="font-size: 16px; margin: 0 0 20px;">Details:</p>
                        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #f0f0f0; font-weight: bold;">ID</td>
                                <td style="padding: 10px; border-bottom: 1px solid #f0f0f0;">{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #f0f0f0; font-weight: bold;">Name</td>
                                <td style="padding: 10px; border-bottom: 1px solid #f0f0f0;">{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #f0f0f0; font-weight: bold;">Mobile</td>
                                <td style="padding: 10px; border-bottom: 1px solid #f0f0f0;">{{ $user->mobile }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; font-weight: bold;">Login Count</td>
                                <td style="padding: 10px;">{{ $user->login_count }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- Footer Section -->
                <tr>
                    <td style="background-color: #f4f4f4; padding: 15px; text-align: center; color: #888888; font-size: 14px;">
                        This is an automated message. Please do not reply.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
