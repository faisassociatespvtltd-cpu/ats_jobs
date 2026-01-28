<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employee Signup - Step 3 - ATS Job Site</title>
    
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
        
        .location-suggestions {
            position: absolute;
            background: white;
            border: 1px solid #edebe9;
            border-radius: 4px;
            max-height: 200px;
            overflow-y: auto;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-top: 4px;
        }
        
        .location-suggestion-item {
            padding: 10px 12px;
            cursor: pointer;
            border-bottom: 1px solid #edebe9;
        }
        
        .location-suggestion-item:hover {
            background-color: #faf9f8;
        }
        
        .location-suggestion-item:last-child {
            border-bottom: none;
        }
        
        .form-group {
            position: relative;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h1 class="signup-title">Employee Signup</h1>
        <p class="signup-subtitle">Step 3 of 3: Personal Details</p>
        
        <div class="progress-bar">
            <div class="progress-step completed">
                <div class="step-number">✓</div>
                <div class="step-label">Account</div>
            </div>
            <div class="progress-step completed">
                <div class="step-number">✓</div>
                <div class="step-label">CV Upload</div>
            </div>
            <div class="progress-step active">
                <div class="step-number">3</div>
                <div class="step-label">Details</div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('employee.signup.step3') }}">
            @csrf
            
            <div class="form-group">
                <label for="name">Full Name <span class="required">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $parsedData['name'] ?? '') }}" required>
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" class="form-control">{{ old('address', $parsedData['address'] ?? '') }}</textarea>
                @error('address')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="tel" id="phone_number" name="phone_number" class="form-control" value="{{ old('phone_number', $parsedData['phone'] ?? '') }}">
                    @error('phone_number')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="whatsapp_number">WhatsApp Number</label>
                    <input type="tel" id="whatsapp_number" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $parsedData['phone'] ?? '') }}">
                    @error('whatsapp_number')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="cnic">CNIC (National ID)</label>
                    <input type="text" id="cnic" name="cnic" class="form-control" value="{{ old('cnic') }}" placeholder="12345-1234567-1">
                    @error('cnic')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="city">City <span class="required">*</span></label>
                    <input type="text" id="city" name="city" class="form-control location-autocomplete" value="{{ old('city', $parsedData['address'] ?? '') }}" placeholder="Start typing city name..." autocomplete="off">
                    <input type="hidden" id="location" name="location">
                    <div id="location-suggestions" class="location-suggestions" style="display: none;"></div>
                    @error('city')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="state">State/Province</label>
                    <input type="text" id="state" name="state" class="form-control" value="{{ old('state') }}" readonly>
                    @error('state')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" class="form-control" value="{{ old('country') }}" readonly>
                    @error('country')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <label for="expected_salary">Expected Salary</label>
                <input type="number" id="expected_salary" name="expected_salary" class="form-control" value="{{ old('expected_salary') }}" placeholder="50000" step="0.01" min="0">
                @error('expected_salary')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="skills">Skills</label>
                <textarea id="skills" name="skills" class="form-control" rows="3" placeholder="e.g., Laravel, MySQL, REST APIs">{{ old('skills', isset($parsedData['skills']) ? implode(', ', $parsedData['skills']) : '') }}</textarea>
                @error('skills')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="experience">Experience</label>
                <textarea id="experience" name="experience" class="form-control" rows="3" placeholder="e.g., 2 years in web development">{{ old('experience', isset($parsedData['experience_items']) ? implode("\n", $parsedData['experience_items']) : '') }}</textarea>
                @error('experience')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="date_of_birth">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                    @error('date_of_birth')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" class="form-control">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <button type="submit" class="btn">Complete Profile</button>
        </form>
    </div>
    
    <script>
        let debounceTimer;
        const cityInput = document.getElementById('city');
        const stateInput = document.getElementById('state');
        const countryInput = document.getElementById('country');
        const locationInput = document.getElementById('location');
        const suggestionsDiv = document.getElementById('location-suggestions');
        
        cityInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            if (query.length < 2) {
                suggestionsDiv.style.display = 'none';
                stateInput.value = '';
                countryInput.value = '';
                locationInput.value = '';
                return;
            }
            
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                searchLocation(query);
            }, 300);
        });
        
        function searchLocation(query) {
            const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&addressdetails=1`;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    displaySuggestions(data);
                })
                .catch(error => {
                    console.error('Location search error:', error);
                });
        }
        
        function displaySuggestions(results) {
            suggestionsDiv.innerHTML = '';
            
            if (results.length === 0) {
                suggestionsDiv.style.display = 'none';
                return;
            }
            
            results.forEach(result => {
                const item = document.createElement('div');
                item.className = 'location-suggestion-item';
                
                const city = result.address.city || result.address.town || result.address.village || result.address.municipality || result.name || '';
                const state = result.address.state || result.address.region || '';
                const country = result.address.country || '';
                
                item.textContent = `${city}${state ? ', ' + state : ''}${country ? ', ' + country : ''}`;
                
                item.addEventListener('click', function() {
                    cityInput.value = city;
                    stateInput.value = state;
                    countryInput.value = country;
                    locationInput.value = `${city}${state ? ', ' + state : ''}${country ? ', ' + country : ''}`;
                    suggestionsDiv.style.display = 'none';
                });
                
                suggestionsDiv.appendChild(item);
            });
            
            suggestionsDiv.style.display = 'block';
        }
        
        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!cityInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.style.display = 'none';
            }
        });
    </script>
</body>
</html>

