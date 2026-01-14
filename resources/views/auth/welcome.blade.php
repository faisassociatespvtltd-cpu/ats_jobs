<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Welcome - ATS Job Site</title>
    
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
        
        .welcome-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 900px;
            width: 100%;
            padding: 60px 40px;
            text-align: center;
        }
        
        .welcome-title {
            font-size: 42px;
            font-weight: 700;
            color: #323130;
            margin-bottom: 16px;
        }
        
        .welcome-subtitle {
            font-size: 18px;
            color: #605e5c;
            margin-bottom: 50px;
        }
        
        .user-type-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .user-type-card {
            background: #faf9f8;
            border: 2px solid #edebe9;
            border-radius: 8px;
            padding: 40px 30px;
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .user-type-card:hover {
            border-color: #0078D4;
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,120,212,0.2);
        }
        
        .user-type-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        
        .user-type-title {
            font-size: 24px;
            font-weight: 600;
            color: #323130;
            margin-bottom: 12px;
        }
        
        .user-type-description {
            font-size: 14px;
            color: #605e5c;
            line-height: 1.6;
        }
        
        .login-link {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #edebe9;
        }
        
        .login-link a {
            color: #0078D4;
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .welcome-container {
                padding: 40px 20px;
            }
            
            .welcome-title {
                font-size: 32px;
            }
            
            .user-type-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <h1 class="welcome-title">Welcome to ATS Job Site</h1>
        <p class="welcome-subtitle">Find your dream job or the perfect candidate</p>
        
        <div class="user-type-cards">
            <a href="{{ route('employee.signup') }}" class="user-type-card">
                <div class="user-type-icon">👤</div>
                <h2 class="user-type-title">I'm an Employee</h2>
                <p class="user-type-description">
                    Looking for job opportunities? Sign up as an employee to upload your CV, 
                    complete your profile, and start applying to jobs.
                </p>
            </a>
            
            <a href="{{ route('employer.signup') }}" class="user-type-card">
                <div class="user-type-icon">🏢</div>
                <h2 class="user-type-title">I'm an Employer</h2>
                <p class="user-type-description">
                    Need to hire talent? Sign up as an employer to post jobs, 
                    manage applications, and find the right candidates.
                </p>
            </a>
        </div>
        
        <div class="login-link">
            Already have an account? <a href="{{ route('login') }}">Login here</a>
        </div>
    </div>
</body>
</html>

