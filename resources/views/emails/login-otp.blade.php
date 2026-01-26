<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login OTP</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .header {
            margin-bottom: 30px;
        }
        .header h1 {
            color: #333333;
            font-size: 24px;
            margin: 0;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #0078D4; /* Primary Brand Color */
            letter-spacing: 5px;
            margin: 20px 0;
            background-color: #f0f8ff;
            padding: 15px;
            border-radius: 4px;
            display: inline-block;
        }
        .message {
            color: #555555;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #999999;
            border-top: 1px solid #eeeeee;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div style="padding: 20px;">
        <div class="container">
            <div class="header">
                <h1>ATS Jobs Login Helper</h1>
            </div>
            
            <p class="message">Hello,</p>
            <p class="message">Use the following One-Time Password (OTP) to complete your login or registration verify. This code is valid for 10 minutes.</p>
            
            <div class="otp-code">{{ $otp }}</div>
            
            <p class="message">If you did not request this code, please ignore this email.</p>
            
            <div class="footer">
                &copy; {{ date('Y') }} ATS Jobs. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
