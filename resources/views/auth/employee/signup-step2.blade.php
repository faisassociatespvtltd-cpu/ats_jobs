<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employee Signup - Step 2 - ATS Job Site</title>
    
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
        
        .btn:disabled {
            background-color: #edebe9;
            color: #8a8886;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h1 class="signup-title">Employee Signup</h1>
        <p class="signup-subtitle">Step 2 of 3: Upload your CV</p>
        
        <div class="progress-bar">
            <div class="progress-step completed">
                <div class="step-number">✓</div>
                <div class="step-label">Account</div>
            </div>
            <div class="progress-step active">
                <div class="step-number">2</div>
                <div class="step-label">CV Upload</div>
            </div>
            <div class="progress-step">
                <div class="step-number">3</div>
                <div class="step-label">Details</div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('employee.signup.step2') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label>Upload CV (PDF, DOC, DOCX - Max 10MB)</label>
                <div class="file-upload-area" onclick="document.getElementById('cv').click()">
                    <div class="file-upload-icon">📄</div>
                    <div class="file-upload-text">Click to upload or drag and drop</div>
                    <div class="file-upload-hint">PDF, DOC, DOCX up to 10MB</div>
                    <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" required onchange="displayFileName(this)">
                    <div id="file-name" class="file-name" style="display: none;"></div>
                </div>
                @error('cv')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn" id="submit-btn">Continue to Personal Details</button>
        </form>
    </div>
    
    <script>
        function displayFileName(input) {
            const fileName = input.files[0]?.name;
            const fileNameDiv = document.getElementById('file-name');
            if (fileName) {
                fileNameDiv.textContent = 'Selected: ' + fileName;
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
            document.getElementById('cv').files = files;
            displayFileName(document.getElementById('cv'));
        }
    </script>
</body>
</html>


