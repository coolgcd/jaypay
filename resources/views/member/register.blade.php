<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Registration Form</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .form-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .form-header h2 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-header p {
            opacity: 0.9;
            font-size: 16px;
        }

        .form-body {
            padding: 40px;
        }

        /* Error Messages */
        .error-container {
            background: #fee;
            border: 1px solid #fcc;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
        }

        .error-container ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .error-container li {
            color: #c53030;
            font-size: 14px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }

        .error-container li:before {
            content: "⚠";
            margin-right: 8px;
            color: #e53e3e;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2d3748;
            font-size: 14px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #fff;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Sponsor Check Section */
        .sponsor-section {
            background: #f7fafc;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .sponsor-input-group {
            display: flex;
            gap: 10px;
            align-items: end;
        }

        .sponsor-input-group input {
            flex: 1;
        }

        .check-btn {
            background: #4299e1;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            white-space: nowrap;
            min-height: 48px;
            -webkit-tap-highlight-color: transparent;
        }

        .check-btn:hover:not(:disabled) {
            background: #3182ce;
            transform: translateY(-1px);
        }

        .check-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .sponsor-name-display {
            margin-top: 10px;
            padding: 10px;
            background: white;
            border-radius: 6px;
            font-weight: 600;
            min-height: 40px;
            display: flex;
            align-items: center;
        }

        .sponsor-found {
            color: #38a169;
        }

        .sponsor-not-found {
            color: #e53e3e;
        }

        /* Form Row for Position */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
            min-height: 56px;
            position: relative;
            -webkit-tap-highlight-color: transparent;
            text-decoration: none;
            display: inline-block;
            box-sizing: border-box;
        }

        .submit-btn:hover:not(:disabled):not(.loading) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Loading State */
        .submit-btn.loading {
            opacity: 0.8;
            pointer-events: none;
            cursor: not-allowed;
        }

        .submit-btn.loading:after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .submit-btn.loading span {
            opacity: 0;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .container {
                margin: 0;
                border-radius: 8px;
            }

            .form-header {
                padding: 20px;
            }

            .form-header h2 {
                font-size: 24px;
            }

            .form-body {
                padding: 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .sponsor-input-group {
                flex-direction: column;
                align-items: stretch;
                gap: 15px;
            }

            .check-btn {
                width: 100%;
                min-height: 48px;
                font-size: 16px;
            }

            .submit-btn {
                min-height: 48px;
                font-size: 16px;
                padding: 14px;
                margin-top: 15px;
            }

            /* Prevent zoom on input focus for iOS */
            .form-group input,
            .form-group select {
                font-size: 16px;
            }
        }

        /* Input States */
        .form-group input.error {
            border-color: #e53e3e;
        }

        .form-group input.success {
            border-color: #38a169;
        }

        /* Required Field Indicator */
        .required:after {
            content: " *";
            color: #e53e3e;
        }

        /* Additional mobile optimizations */
        @media (max-width: 480px) {
            .form-body {
                padding: 15px;
            }
            
            .form-group {
                margin-bottom: 20px;
            }
            
            .submit-btn {
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-header">
            <img src="{{ asset('assets/images/logo.jpeg') }}" alt="Logo" style="width: 120px; max-width: 100%; height: auto; border-radius:50%; display: block; margin: 0 auto 20px;">
            <h2>Member Registration</h2>
        </div>
        
        <div class="form-body">
            @if ($errors->any())
            <div class="error-container">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('member.register.submit') }}" id="registrationForm">
                @csrf
                @if(!empty($member_id))
                    <input type="hidden" name="member_id" value="{{ $member_id }}">
                @endif
            <div class="sponsor-section">
    <div class="form-group">
        <label for="sponsor_show_id" class="required">Sponsor ID</label>
        <div class="sponsor-input-group">
            <input 
                type="text" 
                name="sponsor_show_id" 
                id="sponsor_show_id" 
                required 
                placeholder="Enter sponsor ID"
                value="{{ old('sponsor_show_id', $prefillSponsorId ?? '') }}"
                autocomplete="off"
                @if(!empty($prefillSponsorId)) readonly @endif
            >
            
            <button 
                type="button" 
                class="check-btn" 
                onclick="fetchSponsorName()" 
                id="checkSponsorBtn"
                @if(!empty($prefillSponsorId)) disabled @endif
            >
                <span>Check Sponsor</span>
            </button>
        </div>
        <div id="sponsor_name_display" class="sponsor-name-display"></div>
    </div>
</div>


                <div class="form-group">
                    <label for="position" class="required">Position</label>
        
                @if(!empty($prefillPosition))
                <select name="position_disabled" id="position" disabled>
                        <option value="left" {{ $prefillPosition == 'left' ? 'selected' : '' }}>Left</option>
                        <option value="right" {{ $prefillPosition == 'right' ? 'selected' : '' }}>Right</option>
                    </select>
                    <input type="hidden" name="position" value="{{ $prefillPosition }}">
                @else
                    <select name="position" id="position" required>
                        <option value="">Select Position</option>
                        <option value="left" {{ old('position') == 'left' ? 'selected' : '' }}>Left</option>
                        <option value="right" {{ old('position') == 'right' ? 'selected' : '' }}>Right</option>
                    </select>
                @endif



                </div>

                <div class="form-group">
                    <label for="name" class="required">Full Name</label>
                    <input type="text" name="name" id="name" required placeholder="Enter your full name" autocomplete="name">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="mobileno" class="required">Mobile Number</label>
                        <input type="tel" name="mobileno" id="mobileno" required placeholder="Enter mobile number" autocomplete="tel">
                    </div>
                    <div class="form-group">
                        <label for="emailid" class="required">Email Address</label>
                        <input type="email" name="emailid" id="emailid" required placeholder="Enter email address" autocomplete="email">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password" class="required">Password</label>
                        <input type="password" name="password" id="password" required placeholder="Create password" autocomplete="new-password">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="required">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Confirm password" autocomplete="new-password">
                    </div>
                </div>

                <button type="submit" class="submit-btn" id="submitBtn">
                    <span>Register Now</span>
                </button>

                <div style="margin-top: 20px; display: flex; flex-direction: column; gap: 10px;">
                    <a href="{{ route('member.login') }}" class="submit-btn" style="text-align: center; background: #4FD1C5;">
                        Already Registered? Log In
                    </a>
                    <a href="{{ url('/') }}" class="submit-btn" style="text-align: center; background: #A0AEC0;">
                        Return Home
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Prevent double submission
        let isSubmitting = false;
        let sponsorCheckInProgress = false;

        document.addEventListener('DOMContentLoaded', function () {
            const sponsorInput = document.getElementById('sponsor_show_id');
            if (sponsorInput && sponsorInput.value) {
                fetchSponsorName();
            }
        });

        function fetchSponsorName() {
            if (sponsorCheckInProgress) return;
            
            const showMemId = document.getElementById('sponsor_show_id').value;
            const displayElement = document.getElementById('sponsor_name_display');
            const checkButton = document.getElementById('checkSponsorBtn');
            const checkButtonSpan = checkButton.querySelector('span');
            
            if (!showMemId.trim()) {
                displayElement.textContent = 'Please enter a sponsor ID';
                displayElement.className = 'sponsor-name-display sponsor-not-found';
                return;
            }

            // Prevent multiple requests
            sponsorCheckInProgress = true;
            
            // Show loading state
            checkButtonSpan.textContent = 'Checking...';
            checkButton.disabled = true;
            displayElement.textContent = 'Verifying sponsor...';
            displayElement.className = 'sponsor-name-display';

            // Set a timeout to prevent hanging
            const timeoutId = setTimeout(() => {
                if (sponsorCheckInProgress) {
                    displayElement.textContent = '✗ Request timeout - please try again';
                    displayElement.className = 'sponsor-name-display sponsor-not-found';
                    resetSponsorButton();
                }
            }, 15000); // 15 second timeout

            fetch('{{ route('get.sponsor.name') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ sponsor_show_id: showMemId })
            })
            .then(response => {
                clearTimeout(timeoutId);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    displayElement.textContent = `✓ Sponsor Found: ${data.name}`;
                    displayElement.className = 'sponsor-name-display sponsor-found';
                } else {
                    displayElement.textContent = '✗ Sponsor not found';
                    displayElement.className = 'sponsor-name-display sponsor-not-found';
                }
            })
            .catch(error => {
                clearTimeout(timeoutId);
                console.error('Sponsor check error:', error);
                displayElement.textContent = '✗ Error checking sponsor - please try again';
                displayElement.className = 'sponsor-name-display sponsor-not-found';
            })
            .finally(() => {
                resetSponsorButton();
            });
        }

        function resetSponsorButton() {
            sponsorCheckInProgress = false;
            const checkButton = document.getElementById('checkSponsorBtn');
            const checkButtonSpan = checkButton.querySelector('span');
            checkButtonSpan.textContent = 'Check Sponsor';
            checkButton.disabled = false;
        }

        // Form validation and submission
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }

            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }

            // Prevent double submission
            isSubmitting = true;
            const submitBtn = document.getElementById('submitBtn');
            const submitBtnSpan = submitBtn.querySelector('span');
            
            // Show loading state
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
            submitBtnSpan.textContent = 'Registering...';
            
            // Set a backup timeout to reset if server doesn't respond
            setTimeout(() => {
                if (isSubmitting) {
                    isSubmitting = false;
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;
                    submitBtnSpan.textContent = 'Register Now';
                    alert('Registration is taking longer than expected. Please try again.');
                }
            }, 30000); // 30 second timeout
        });

        // Real-time password matching
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword && password !== confirmPassword) {
                this.classList.add('error');
                this.classList.remove('success');
            } else if (confirmPassword && password === confirmPassword) {
                this.classList.add('success');
                this.classList.remove('error');
            } else {
                this.classList.remove('error', 'success');
            }
        });

        // Prevent form resubmission on page reload
        if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
            isSubmitting = false;
        }
    </script>
</body>
</html>