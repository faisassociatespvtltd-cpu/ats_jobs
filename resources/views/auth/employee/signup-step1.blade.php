<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employee Signup - Step 1 - ATS Job Site</title>
    
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
        
        .signup-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 100%;
            padding: 50px 40px;
        }
        
        .signup-title {
            font-size: 28px;
            font-weight: 700;
            color: #323130;
            margin-bottom: 8px;
            text-align: center;
        }
        
        .signup-subtitle {
            font-size: 14px;
            color: #605e5c;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .progress-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .progress-step {
            flex: 1;
            text-align: center;
            position: relative;
        }
        
        .progress-step::after {
            content: '';
            position: absolute;
            top: 15px;
            left: 50%;
            width: 100%;
            height: 2px;
            background: #edebe9;
            z-index: 0;
        }
        
        .progress-step:last-child::after {
            display: none;
        }
        
        .progress-step.active .step-number {
            background: #0078D4;
            color: white;
        }
        
        .step-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #edebe9;
            color: #605e5c;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            position: relative;
            z-index: 1;
            font-weight: 600;
        }
        
        .step-label {
            font-size: 12px;
            color: #605e5c;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #323130;
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #edebe9;
            border-radius: 4px;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #0078D4;
            box-shadow: 0 0 0 2px rgba(0,120,212,0.1);
        }
        
        .error-message {
            color: #d13438;
            font-size: 12px;
            margin-top: 4px;
        }
        
        .btn {
            width: 100%;
            padding: 12px;
            background-color: #0078D4;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .btn:hover {
            background-color: #106ebe;
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: #0078D4;
            text-decoration: none;
            font-size: 14px;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h1 class="signup-title">Employee Signup</h1>
        <p class="signup-subtitle">Step 1 of 3: Create your account</p>
        
        <div class="progress-bar">
            <div class="progress-step active">
                <div class="step-number">1</div>
                <div class="step-label">Account</div>
            </div>
            <div class="progress-step">
                <div class="step-number">2</div>
                <div class="step-label">CV Upload</div>
            </div>
            <div class="progress-step">
                <div class="step-number">3</div>
                <div class="step-label">Details</div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('employee.signup.step1') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
            </div>
            
            <button type="submit" class="btn">Continue to CV Upload</button>
        </form>
        
        <div class="back-link">
            <a href="{{ route('welcome') }}">← Back to Welcome</a>
        </div>
    </div>
</body>
</html>


