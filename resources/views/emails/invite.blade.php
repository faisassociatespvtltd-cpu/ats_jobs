<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f2f1; margin: 0; padding: 0; }
        .email-container { max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .header { background-color: #0078d4; padding: 20px; text-align: center; color: #ffffff; }
        .content { padding: 30px; color: #323130; }
        .btn { display: inline-block; background-color: #0078d4; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 4px; font-weight: 600; margin-top: 20px; }
        .footer { background-color: #f3f2f1; padding: 15px; text-align: center; font-size: 12px; color: #605e5c; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1 style="margin: 0; font-size: 24px;">ATS Job Portal</h1>
        </div>
        <div class="content">
            <h2 style="font-size: 20px; margin-top: 0;">You're Invited!</h2>
            <p>Hi there,</p>
            <p><strong>{{ $senderName }}</strong> thinks you'd find ATS Job Portal valuable and has invited you to join.</p>
            <p>Discover thousands of job opportunities, or find the perfect candidate for your company. Join a growing community of professionals today.</p>
            <div style="text-align: center;">
                <a href="{{ $referralLink }}" class="btn">Accept Invitation</a>
            </div>
            <p style="margin-top: 30px; font-size: 14px;">Or copy and paste this link into your browser:<br> <a href="{{ $referralLink }}" style="color: #0078d4;">{{ $referralLink }}</a></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} ATS Job Portal. All rights reserved.<br>
            If you did not expect this invitation, you can safely ignore this email.
        </div>
    </div>
</body>
</html>
