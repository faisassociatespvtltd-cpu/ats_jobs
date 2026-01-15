<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employer Signup - Step 3 - ATS Job Site</title>
    
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
            max-width: 600px;
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
        
        .progress-step.completed .step-number {
            background: #107c10;
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
        
        .form-group label .required {
            color: #d13438;
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
        
        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
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
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h1 class="signup-title">Employer Signup</h1>
        <p class="signup-subtitle">Step 3 of 3: Company Details</p>
        
        <div class="progress-bar">
            <div class="progress-step completed">
                <div class="step-number">✓</div>
                <div class="step-label">Account</div>
            </div>
            <div class="progress-step completed">
                <div class="step-number">✓</div>
                <div class="step-label">Logo Upload</div>
            </div>
            <div class="progress-step active">
                <div class="step-number">3</div>
                <div class="step-label">Company Info</div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('employer.signup.step3') }}">
            @csrf
            
            <div class="form-group">
                <label for="company_name">Company Name <span class="required">*</span></label>
                <input type="text" id="company_name" name="company_name" class="form-control" value="{{ old('company_name') }}" required>
                @error('company_name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="company_address">Company Address</label>
                <textarea id="company_address" name="company_address" class="form-control">{{ old('company_address') }}</textarea>
                @error('company_address')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="contact_person_name">Contact Person Name</label>
                <input type="text" id="contact_person_name" name="contact_person_name" class="form-control" value="{{ old('contact_person_name') }}">
                @error('contact_person_name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="tel" id="phone_number" name="phone_number" class="form-control" value="{{ old('phone_number') }}">
                    @error('phone_number')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="whatsapp_number">WhatsApp Number</label>
                    <input type="tel" id="whatsapp_number" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number') }}">
                    @error('whatsapp_number')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <label for="website_url">Website URL</label>
                <input type="url" id="website_url" name="website_url" class="form-control" value="{{ old('website_url') }}" placeholder="https://example.com">
                @error('website_url')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="industry">Industry</label>
                    <input type="text" id="industry" name="industry" class="form-control" value="{{ old('industry') }}">
                    @error('industry')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="company_size">Company Size</label>
                    <input type="text" id="company_size" name="company_size" class="form-control" value="{{ old('company_size') }}" placeholder="e.g., 50-100 employees">
                    @error('company_size')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="license_number">License Number</label>
                    <input type="text" id="license_number" name="license_number" class="form-control" value="{{ old('license_number') }}">
                    @error('license_number')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="registration_number">Registration Number</label>
                    <input type="text" id="registration_number" name="registration_number" class="form-control" value="{{ old('registration_number') }}">
                    @error('registration_number')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tax_number">Tax Number</label>
                    <input type="text" id="tax_number" name="tax_number" class="form-control" value="{{ old('tax_number') }}">
                    @error('tax_number')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="company_type">Company Type</label>
                    <input type="text" id="company_type" name="company_type" class="form-control" value="{{ old('company_type') }}" placeholder="e.g., Pvt Ltd, LLC">
                    @error('company_type')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="founded_year">Founded Year</label>
                <input type="number" id="founded_year" name="founded_year" class="form-control" value="{{ old('founded_year') }}" min="1800" max="{{ date('Y') }}" placeholder="e.g., 2012">
                @error('founded_year')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="company_description">Company Description</label>
                <textarea id="company_description" name="company_description" class="form-control" rows="4">{{ old('company_description') }}</textarea>
                @error('company_description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn">Complete Profile</button>
        </form>
    </div>
</body>
</html>

