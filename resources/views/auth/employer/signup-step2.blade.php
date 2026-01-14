<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employer Signup - Step 2 - ATS Job Site</title>
    
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
        
        .file-upload-area {
            border: 2px dashed #edebe9;
            border-radius: 8px;
            padding: 40px 20px;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .file-upload-area:hover {
            border-color: #0078D4;
            background-color: #faf9f8;
        }
        
        .file-upload-area.dragover {
            border-color: #0078D4;
            background-color: #e8f4f8;
        }
        
        .file-upload-icon {
            font-size: 48px;
            margin-bottom: 12px;
        }
        
        .file-upload-text {
            color: #605e5c;
            font-size: 14px;
            margin-bottom: 8px;
        }
        
        .file-upload-hint {
            color: #8a8886;
            font-size: 12px;
        }
        
        input[type="file"] {
            display: none;
        }
        
        .file-preview {
            margin-top: 16px;
        }
        
        .file-preview img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            border: 1px solid #edebe9;
        }
        
        .file-name {
            margin-top: 12px;
            color: #0078D4;
            font-weight: 500;
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
    </style>
</head>
<body>
    <div class="signup-container">
        <h1 class="signup-title">Employer Signup</h1>
        <p class="signup-subtitle">Step 2 of 3: Upload Company Logo</p>
        
        <div class="progress-bar">
            <div class="progress-step completed">
                <div class="step-number">✓</div>
                <div class="step-label">Account</div>
            </div>
            <div class="progress-step active">
                <div class="step-number">2</div>
                <div class="step-label">Logo Upload</div>
            </div>
            <div class="progress-step">
                <div class="step-number">3</div>
                <div class="step-label">Company Info</div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('employer.signup.step2') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label>Upload Company Logo (JPG, PNG, GIF - Max 2MB)</label>
                <div class="file-upload-area" onclick="document.getElementById('company_logo').click()">
                    <div class="file-upload-icon">🖼️</div>
                    <div class="file-upload-text">Click to upload or drag and drop</div>
                    <div class="file-upload-hint">JPG, PNG, GIF up to 2MB</div>
                    <input type="file" id="company_logo" name="company_logo" accept="image/*" required onchange="displayFilePreview(this)">
                    <div id="file-preview" class="file-preview" style="display: none;"></div>
                    <div id="file-name" class="file-name" style="display: none;"></div>
                </div>
                @error('company_logo')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn">Continue to Company Details</button>
        </form>
    </div>
    
    <script>
        function displayFilePreview(input) {
            const file = input.files[0];
            const previewDiv = document.getElementById('file-preview');
            const fileNameDiv = document.getElementById('file-name');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewDiv.innerHTML = '<img src="' + e.target.result + '" alt="Logo Preview">';
                    previewDiv.style.display = 'block';
                };
                reader.readAsDataURL(file);
                
                fileNameDiv.textContent = 'Selected: ' + file.name;
                fileNameDiv.style.display = 'block';
            }
        }
        
        // Drag and drop functionality
        const uploadArea = document.querySelector('.file-upload-area');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight(e) {
            uploadArea.classList.add('dragover');
        }
        
        function unhighlight(e) {
            uploadArea.classList.remove('dragover');
        }
        
        uploadArea.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            document.getElementById('company_logo').files = files;
            displayFilePreview(document.getElementById('company_logo'));
        }
    </script>
</body>
</html>

