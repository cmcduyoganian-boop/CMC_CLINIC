<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - CMC Clinic</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap');

        :root {
            --bg-1: #edf6ff;
            --bg-2: #eefbf5;
            --primary: #1877f2;
            --primary-dark: #0f5dd5;
            --primary-soft: #eaf3ff;
            --text: #122033;
            --muted: #64748b;
            --stroke: rgba(148,163,184,0.2);
            --card: rgba(255,255,255,0.85);
            --success-bg: #ecfdf5;
            --success-text: #047857;
            --error-bg: #fff1f2;
            --error-text: #be123c;
            --warning: #f59e0b;
            --shadow: 0 30px 80px rgba(24, 40, 72, 0.16);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, var(--bg-1) 0%, #f5fbff 35%, var(--bg-2) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            color: var(--text);
        }

        .verify-shell {
            width: 100%;
            max-width: 520px;
            padding: 18px;
            border-radius: 32px;
            background: rgba(255,255,255,0.38);
            border: 1px solid rgba(255,255,255,0.5);
            box-shadow: var(--shadow);
            backdrop-filter: blur(8px);
        }

        .verify-card {
            background: var(--card);
            border: 1px solid rgba(148,163,184,0.18);
            border-radius: 28px;
            padding: 34px 28px 24px;
            box-shadow: 0 18px 40px rgba(15,23,42,0.08);
        }

        .verify-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .verify-icon {
            width: 78px;
            height: 78px;
            margin: 0 auto 16px;
            border-radius: 22px;
            background: linear-gradient(135deg, #eaf3ff 0%, #dbeafe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: var(--primary);
            box-shadow: inset 0 0 0 1px rgba(24,119,242,0.08);
        }

        .verify-title {
            font-size: clamp(1.8rem, 2vw + 0.5rem, 2.5rem);
            font-weight: 700;
            letter-spacing: -0.04em;
            color: var(--text);
            margin-bottom: 8px;
        }

        .verify-subtitle {
            font-size: 0.82rem;
            color: var(--muted);
            line-height: 1.6;
            word-break: break-word;
        }

        .verify-email {
            font-weight: 700;
            color: var(--primary);
        }

        .alert {
            padding: 12px 14px;
            border-radius: 14px;
            font-size: 0.78rem;
            line-height: 1.5;
            margin-bottom: 18px;
        }

        .alert-success {
            background: var(--success-bg);
            color: var(--success-text);
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: var(--error-bg);
            color: var(--error-text);
            border: 1px solid #fecdd3;
        }

        .info-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 14px;
            padding: 14px 16px;
            margin-bottom: 24px;
            font-size: 0.78rem;
            color: #1d4ed8;
            line-height: 1.6;
        }

        .info-box strong {
            display: block;
            margin-bottom: 4px;
        }

        .otp-section {
            margin-bottom: 22px;
        }

        .otp-label {
            font-size: 0.72rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .otp-boxes-container {
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }

        .otp-box {
            width: 48px;
            height: 52px;
            border: 1px solid var(--stroke);
            border-radius: 14px;
            background: #f9fbff;
            color: var(--text);
            font-size: 1.3rem;
            font-weight: 700;
            text-align: center;
            font-family: 'DM Sans', monospace;
            transition: all 0.2s ease;
        }

        .otp-box:focus {
            outline: none;
            border-color: rgba(24,119,242,0.5);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(24,119,242,0.08);
        }

        .otp-box.filled {
            border-color: rgba(24,119,242,0.5);
            background: #fff;
            color: var(--primary);
        }

        .otp-box.error {
            border-color: #ef4444;
            background: #fff7f7;
            animation: shake 0.4s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-4px); }
            40% { transform: translateX(4px); }
            60% { transform: translateX(-4px); }
            80% { transform: translateX(4px); }
        }

        .expiry-info {
            font-size: 0.75rem;
            color: var(--warning);
            text-align: center;
            margin-bottom: 18px;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-bottom: 18px;
        }

        .btn {
            flex: 1;
            padding: 14px 16px;
            border: none;
            border-radius: 14px;
            font-size: 0.94rem;
            font-weight: 700;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-verify {
            background: linear-gradient(135deg, var(--primary) 0%, #0ea5e9 100%);
            color: #fff;
            box-shadow: 0 16px 30px rgba(24,119,242,0.22);
        }

        .btn-verify:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 36px rgba(24,119,242,0.3);
        }

        .btn-verify:disabled {
            background: #cbd5e1;
            box-shadow: none;
            cursor: not-allowed;
            transform: none;
        }

        .resend-section {
            text-align: center;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
        }

        .resend-text {
            font-size: 0.76rem;
            color: var(--muted);
            margin-bottom: 10px;
        }

        .btn-resend {
            background: none;
            border: none;
            padding: 0;
            color: var(--primary);
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: underline;
            text-underline-offset: 2px;
        }

        .btn-resend:disabled {
            color: #94a3b8;
            cursor: not-allowed;
        }

        .back-link {
            text-align: center;
            margin-top: 16px;
        }

        .back-link a {
            font-size: 0.78rem;
            color: var(--primary);
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 520px) {
            body { padding: 14px; }

            .verify-shell { padding: 8px; }
            .verify-card { padding: 22px 18px 18px; border-radius: 22px; }

            .otp-box {
                width: 42px;
                height: 48px;
                font-size: 1.1rem;
            }

            .otp-boxes-container { gap: 6px; }
        }
    </style>
</head>
<body>
    <div class="verify-shell">
        <div class="verify-card">
            <div class="verify-header">
                <div class="verify-icon">✉️</div>
                <h1 class="verify-title">Verify Your Email</h1>
                <p class="verify-subtitle">
                    We've sent a 6-digit verification code to <span class="verify-email">{{ $pending->email }}</span>
                </p>
            </div>

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

            <div class="info-box">
                <strong>📋 How to verify:</strong>
                Enter the 6-digit code from your email below. The code expires in 10 minutes.
            </div>

            <form method="POST" action="{{ route('otp.verify', $pending->email) }}" id="otpForm">
                @csrf

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

                    <input type="hidden" name="otp" id="otpInput" value="">
                </div>

                <div class="expiry-info">
                    ⏱️ Code expires in <span id="expiryTimer">10:00</span>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-verify" id="verifyBtn" disabled>
                        Verify Email
                    </button>
                </div>
            </form>

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

            <div class="back-link">
                <a href="{{ route('register') }}">← Back to registration</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const boxes = document.querySelectorAll('.otp-box');
            const otpInput = document.getElementById('otpInput');
            const verifyBtn = document.getElementById('verifyBtn');

            boxes.forEach(box => {
                box.addEventListener('input', function() {
                    if (box.classList.contains('error')) {
                        box.classList.remove('error');
                    }
                });
            });

            boxes.forEach((box, index) => {
                box.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    updateOtpInput();

                    if (this.value && index < boxes.length - 1) {
                        boxes[index + 1].focus();
                    }

                    const allFilled = Array.from(boxes).every(b => b.value);
                    verifyBtn.disabled = !allFilled;
                });

                box.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !this.value && index > 0) {
                        boxes[index - 1].focus();
                        boxes[index - 1].value = '';
                        updateOtpInput();
                    }
                });

                box.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pasteData = e.clipboardData.getData('text/plain').replace(/[^0-9]/g, '');

                    if (pasteData.length >= 6) {
                        for (let i = 0; i < Math.min(6, pasteData.length); i++) {
                            boxes[i].value = pasteData[i];
                        }
                        updateOtpInput();
                        verifyBtn.disabled = false;
                        boxes[5].focus();
                    }
                });
            });

            boxes[0].focus();

            function updateOtpInput() {
                const otp = Array.from(boxes).map(b => b.value).join('');
                otpInput.value = otp;
            }

            let timeLeft = 600;
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

            document.getElementById('otpForm').addEventListener('submit', function(e) {
                const otp = otpInput.value;

                if (otp.length !== 6) {
                    e.preventDefault();
                    alert('Please enter all 6 digits');
                    return false;
                }

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
