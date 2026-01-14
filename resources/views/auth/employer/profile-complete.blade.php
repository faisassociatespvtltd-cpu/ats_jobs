<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profile Complete - ATS Job Site</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .complete-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 100%;
            padding: 60px 40px;
            text-align: center;
        }
        
        .success-icon {
            font-size: 80px;
            margin-bottom: 24px;
        }
        
        .complete-title {
            font-size: 32px;
            font-weight: 700;
            color: #323130;
            margin-bottom: 12px;
        }
        
        .complete-message {
            font-size: 16px;
            color: #605e5c;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .email-notice {
            background: #e8f4f8;
            border: 1px solid #0078D4;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 30px;
            font-size: 14px;
            color: #323130;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #0078D4;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.2s;
        }
        
        .btn:hover {
            background-color: #106ebe;
        }
        
        .btn-secondary {
            background-color: #605e5c;
            margin-left: 12px;
        }
        
        .btn-secondary:hover {
            background-color: #4a4846;
        }
    </style>
</head>
<body>
    <div class="complete-container">
        <div class="success-icon">✅</div>
        <h1 class="complete-title">Profile Created Successfully!</h1>
        <p class="complete-message">
            Your employer profile has been created. You can now start posting jobs and managing applications.
        </p>
        
        <div class="email-notice">
            <strong>Email Verification:</strong><br>
            We've sent a verification email to your inbox. Please check your email and click the verification link to activate your account.
        </div>
        
        <div>
            <a href="{{ route('employer.profile') }}" class="btn">View My Profile</a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Go to Dashboard</a>
        </div>
    </div>
</body>
</html>

