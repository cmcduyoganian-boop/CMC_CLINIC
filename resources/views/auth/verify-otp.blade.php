<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - CMC Clinic</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, #f8fbff 0%, #e6f2ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        
        .verify-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            padding: 40px 32px;
        }
        
        .verify-header {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .verify-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 16px;
            background: #dbeafe;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
        }
        
        .verify-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a3a52;
            margin-bottom: 8px;
        }
        
        .verify-subtitle {
            font-size: 13px;
            color: #7f8c8d;
            word-break: break-all;
        }
        
        .verify-email {
            font-weight: 600;
            color: #3498db;
        }
        
        .alert {
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 12px;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #27ae60;
            border: 1px solid #a9dfcd;
        }
        
        .alert-error {
            background: #fadbd8;
            color: #c0392b;
            border: 1px solid #f5b7b1;
        }
        
        .info-box {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 24px;
            font-size: 12px;
            color: #0066cc;
            line-height: 1.6;
        }
        
        .info-box strong {
            display: block;
            margin-bottom: 4px;
        }
        
        .otp-section {
            margin-bottom: 24px;
        }
        
        .otp-label {
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .otp-boxes-container {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }
        
        .otp-box {
            width: 50px;
            height: 50px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 24px;
            font-weight: 700;
            text-align: center;
            color: #1a3a52;
            background: #f9fafb;
            transition: all 0.2s;
            font-family: 'DM Sans', monospace;
        }
        
        .otp-box:focus {
            outline: none;
            border-color: #3498db;
            background: white;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        .otp-box.filled {
            border-color: #3498db;
            background: white;
            color: #3498db;
        }
        
        .otp-box.error {
            border-color: #e74c3c;
            background: #fadbd8;
            animation: shake 0.5s;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        .expiry-info {
            font-size: 12px;
            color: #f39c12;
            text-align: center;
            margin-bottom: 16px;
        }
        
        .form-actions {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
        }
        
        .btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'DM Sans', sans-serif;
        }
        
        .btn-verify {
            background: #3498db;
            color: white;
        }
        
        .btn-verify:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }
        
        .btn-verify:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
            transform: none;
        }
        
        .resend-section {
            text-align: center;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }
        
        .resend-text {
            font-size: 12px;
            color: #7f8c8d;
            margin-bottom: 8px;
        }
        
        .btn-resend {
            background: none;
            border: none;
            padding: 0;
            color: #3498db;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: underline;
        }
        
        .btn-resend:hover {
            color: #2980b9;
        }
        
        .btn-resend:disabled {
            color: #bdc3c7;
            cursor: not-allowed;
        }
        
        .back-link {
            text-align: center;
            margin-top: 16px;
        }
        
        .back-link a {
            font-size: 12px;
            color: #3498db;
            text-decoration: none;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
        
        /* Responsive */
        @media (max-width: 480px) {
            .verify-card {
                padding: 24px 20px;
            }
            
            .verify-title {
                font-size: 24px;
            }
            
            .otp-box {
                width: 45px;
                height: 45px;
                font-size: 20px;
            }
            
            .otp-boxes-container {
                gap: 6px;
            }
        }
    </style>
</head>
<body>
    <div class="verify-card">
        <!-- Header -->
        <div class="verify-header">
            <div class="verify-icon">✉️</div>
            <h1 class="verify-title">Verify Your Email</h1>
            <p class="verify-subtitle">
                We've sent a 6-digit verification code to <span class="verify-email">{{ $pending->email }}</span>
            </p>
        </div>

        <!-- Messages -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->has('otp'))
            <div class="alert alert-error">
                {{ $errors->first('otp') }}
            </div>
        @endif

        <!-- Info Box -->
        <div class="info-box">
            <strong>📋 How to verify:</strong>
            Enter the 6-digit code from your email below. The code expires in 10 minutes.
        </div>

        <!-- OTP Form -->
        <form method="POST" action="{{ route('otp.verify', $pending->email) }}" id="otpForm">
            @csrf

            <!-- OTP Boxes -->
            <div class="otp-section">
                <div class="otp-label">Verification Code</div>
                
                <div class="otp-boxes-container" id="otpBoxesContainer">
                    <input type="text" class="otp-box" maxlength="1" inputmode="numeric" data-index="0" />
                    <input type="text" class="otp-box" maxlength="1" inputmode="numeric" data-index="1" />
                    <input type="text" class="otp-box" maxlength="1" inputmode="numeric" data-index="2" />
                    <input type="text" class="otp-box" maxlength="1" inputmode="numeric" data-index="3" />
                    <input type="text" class="otp-box" maxlength="1" inputmode="numeric" data-index="4" />
                    <input type="text" class="otp-box" maxlength="1" inputmode="numeric" data-index="5" />
                </div>

                <!-- Hidden input to store combined OTP -->
                <input type="hidden" name="otp" id="otpInput" value="">
            </div>

            <!-- Expiry Info -->
            <div class="expiry-info">
                ⏱️ Code expires in <span id="expiryTimer">10:00</span> minutes
            </div>

            <!-- Verify Button -->
            <div class="form-actions">
                <button type="submit" class="btn btn-verify" id="verifyBtn" disabled>
                    Verify Email
                </button>
            </div>

        </form>

            <!-- Resend Section -->
            <div class="resend-section">
                <p class="resend-text">Didn't receive the code?</p>
                <form method="POST" action="{{ route('otp.resend') }}" style="display: inline;">
                    @csrf
                    <input type="hidden" name="email" value="{{ $pending->email }}">
                    <button type="submit" class="btn-resend" id="resendBtn">
                        <span class="resend-label">Send a new code</span>
                        <span class="resend-loading" hidden>Sending...</span>
                    </button>
                </form>
            </div>

            <!-- Back Link -->
            <div class="back-link">
                <a href="{{ route('register') }}">← Back to registration</a>
            </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const boxes = document.querySelectorAll('.otp-box');
            const otpInput = document.getElementById('otpInput');
            const verifyBtn = document.getElementById('verifyBtn');
            const errorBox = document.querySelector('.alert-error');

            // Remove error state from boxes when user starts typing
            boxes.forEach(box => {
                box.addEventListener('input', function() {
                    if (box.classList.contains('error')) {
                        box.classList.remove('error');
                    }
                });
            });

            // Handle OTP input
            boxes.forEach((box, index) => {
                box.addEventListener('input', function(e) {
                    // Only allow numbers
                    this.value = this.value.replace(/[^0-9]/g, '');

                    // Update hidden input
                    updateOtpInput();

                    // Move to next box
                    if (this.value && index < boxes.length - 1) {
                        boxes[index + 1].focus();
                    }

                    // Enable verify button only if all 6 digits are filled
                    const allFilled = Array.from(boxes).every(b => b.value);
                    verifyBtn.disabled = !allFilled;
                });

                // Handle backspace
                box.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !this.value && index > 0) {
                        boxes[index - 1].focus();
                        boxes[index - 1].value = '';
                        updateOtpInput();
                    }
                });

                // Handle paste
                box.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pasteData = e.clipboardData.getData('text/plain').replace(/[^0-9]/g, '');
                    
                    if (pasteData.length >= 6) {
                        // Fill all boxes with pasted OTP
                        for (let i = 0; i < Math.min(6, pasteData.length); i++) {
                            boxes[i].value = pasteData[i];
                        }
                        updateOtpInput();
                        verifyBtn.disabled = false;
                        boxes[5].focus();
                    }
                });
            });

            // Focus first box on load
            boxes[0].focus();

            // Update hidden input with combined OTP
            function updateOtpInput() {
                const otp = Array.from(boxes).map(b => b.value).join('');
                otpInput.value = otp;
            }

            // Expiry countdown (demo - 10 minutes)
            let timeLeft = 600; // 10 minutes in seconds
            const timerDisplay = document.getElementById('expiryTimer');
            
            const countdown = setInterval(() => {
                timeLeft--;
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timerDisplay.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                
                if (timeLeft <= 0) {
                    clearInterval(countdown);
                    verifyBtn.disabled = true;
                    timerDisplay.textContent = 'Expired';
                }
            }, 1000);

            // Form submission
            document.getElementById('otpForm').addEventListener('submit', function(e) {
                const otp = otpInput.value;
                
                if (otp.length !== 6) {
                    e.preventDefault();
                    alert('Please enter all 6 digits');
                    return false;
                }

                // Add loading state
                verifyBtn.disabled = true;
                verifyBtn.textContent = 'Verifying...';
            });

            document.querySelector('.resend-section form').addEventListener('submit', function() {
                const resendBtn = document.getElementById('resendBtn');
                resendBtn.disabled = true;
                resendBtn.querySelector('.resend-label').hidden = true;
                resendBtn.querySelector('.resend-loading').hidden = false;
            });
        });
    </script>
</body>
</html>