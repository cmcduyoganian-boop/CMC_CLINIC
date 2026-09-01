<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMC Clinic — Register</title>
    @vite(['resources/css/app.css'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap');

        :root {
            --bg-1: #edf6ff;
            --bg-2: #eefbf5;
            --panel: rgba(255,255,255,0.8);
            --panel-strong: #fff;
            --primary: #1877f2;
            --primary-dark: #0f5dd5;
            --accent: #00b894;
            --text: #122033;
            --muted: #64748b;
            --stroke: rgba(148, 163, 184, 0.2);
            --shadow: 0 30px 80px rgba(24, 40, 72, 0.16);
        }

        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family:'DM Sans',sans-serif;
            background: linear-gradient(135deg, var(--bg-1) 0%, #f5fbff 35%, var(--bg-2) 100%);
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:22px;
            color:var(--text);
        }

        .card {
            width:100%;
            max-width:440px;
            background: rgba(255,255,255,0.92);
            border:1px solid rgba(148,163,184,0.18);
            border-radius:24px;
            box-shadow: 0 20px 50px rgba(15,23,42,0.1);
            padding:30px 24px 22px;
            backdrop-filter: blur(10px);
        }

        .logo-wrap {
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            text-align:center;
            margin-bottom:18px;
        }

        .logo-wrap img {
            display:block;
            width:76px;
            height:76px;
            object-fit:contain;
            margin:0 auto 12px;
        }

        .logo-title {
            font-size:1.08rem;
            font-weight:700;
            color: var(--text);
            letter-spacing:-0.03em;
            text-align:center;
        }

        .logo-sub {
            color: var(--muted);
            font-size:0.78rem;
            margin-top:4px;
        }

        .card-desc {
            font-size:0.8rem;
            color: var(--muted);
            line-height:1.6;
            margin-bottom:16px;
            text-align:center;
        }

        .alert {
            display:flex;
            align-items:flex-start;
            gap:10px;
            padding:12px 14px;
            border-radius:14px;
            margin-bottom:16px;
            font-size:0.8rem;
            line-height:1.5;
        }

        .alert i { margin-top:2px; flex-shrink:0; }

        .alert-error {
            background:#fff1f2;
            border:1px solid #fecdd3;
            color:#be123c;
        }

        .alert-info {
            background:#eff6ff;
            border:1px solid #bfdbfe;
            color:#1d4ed8;
        }

        form { display:block; }

        .field { margin-bottom:14px; }
        .field label {
            display:block;
            font-size:0.74rem;
            font-weight:600;
            color:#334155;
            margin-bottom:8px;
        }

        .input-wrap { position:relative; }

        .input-wrap input,
        .input-wrap select {
            width:100%;
            height:48px;
            border:1px solid var(--stroke);
            border-radius:14px;
            padding:12px 14px;
            font-size:0.91rem;
            font-family:'DM Sans',sans-serif;
            color:var(--text);
            background:#f9fbff;
            outline:none;
            transition:all .2s ease;
        }

        .input-wrap input.has-toggle { padding-right:44px; }

        .input-wrap input:focus,
        .input-wrap select:focus {
            border-color: rgba(24,119,242,0.52);
            background:#fff;
            box-shadow: 0 0 0 4px rgba(24,119,242,0.08);
        }

        .input-wrap input.err,
        .input-wrap select.err {
            border-color:#ef4444;
            background:#fff7f7;
        }

        .toggle-btn {
            position:absolute;
            right:12px;
            top:50%;
            transform:translateY(-50%);
            background:none;
            border:none;
            cursor:pointer;
            padding:6px;
            color:#94a3b8;
            display:flex;
            align-items:center;
            justify-content:center;
        }

        .toggle-btn:hover { color:#475569; }

        .err-msg {
            font-size:0.72rem;
            color:#dc2626;
            margin-top:6px;
        }

        .pwd-hint {
            font-size:0.72rem;
            color:#64748b;
            margin-top:8px;
            line-height:1.5;
        }

        .pwd-req {
            margin-top:10px;
            padding:10px 12px;
            border-radius:12px;
            background:#f8fafc;
            border:1px solid #e2e8f0;
        }

        .pwd-req li {
            list-style:none;
            font-size:0.72rem;
            line-height:1.8;
            color:#64748b;
        }

        .pwd-req .ok { color:#10b981; }
        .pwd-req .fail { color:#ef4444; }

        .btn-submit {
            width:100%;
            border:none;
            border-radius:14px;
            padding:14px 16px;
            font-size:0.96rem;
            font-weight:700;
            color:#fff;
            background: linear-gradient(135deg, var(--primary) 0%, #0ea5e9 100%);
            box-shadow: 0 16px 30px rgba(24,119,242,0.26);
            cursor:pointer;
            transition: transform .2s ease, box-shadow .2s ease;
            margin-top:8px;
            font-family:'DM Sans',sans-serif;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 36px rgba(24,119,242,0.34);
        }

        .footer-links {
            margin-top:18px;
            text-align:center;
        }

        .footer-links a {
            font-size:0.82rem;
            color: var(--muted);
            text-decoration:none;
        }

        .footer-links a span {
            color: var(--primary);
            font-weight:600;
        }

        @media (max-width: 960px) {
            .auth-shell {
                grid-template-columns: 1fr;
                max-width: 650px;
            }

            .brand-panel {
                min-height:230px;
                padding:32px 24px;
            }
        }

        @media (max-width: 560px) {
            body { padding:14px; }
            .card { padding:22px 18px 18px; border-radius:20px; }
            .form-panel { padding:18px 12px 20px; }
            .brand-title { font-size:2.2rem; }
        }
    </style>
</head>
<body>
<div class="card">

    <div class="logo-wrap">
        <img src="{{ asset('images/cmc-logo.png') }}" alt="CMC Logo">
        <div class="logo-title">Carmen Municipal College</div>
        <div class="logo-sub">School Clinic Management System</div>
    </div>

            <p class="card-desc">
                Create your account to access the clinic management system. Your account will be approved by the clinic nurse before you can start using the system.
            </p>

            <div class="alert alert-info">
                <strong>📧 Email Verification:</strong> After registration, you'll receive a 6-digit verification code via email. You must enter this code to complete your account setup.
            </div>

            @if($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <div class="field">
                    <label>Full Name *</label>
                    <div class="input-wrap">
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Enter your full name"
                               class="{{ $errors->has('name') ? 'err' : '' }}"
                               autofocus required>
                    </div>
                    @error('name')<div class="err-msg">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label>Username *</label>
                    <div class="input-wrap">
                        <input type="text"
                               name="username"
                               value="{{ old('username') }}"
                               placeholder="Create a username (for login)"
                               class="{{ $errors->has('username') ? 'err' : '' }}"
                               required>
                    </div>
                    @error('username')<div class="err-msg">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label>Email Address *</label>
                    <div class="input-wrap">
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="Enter your email"
                               class="{{ $errors->has('email') ? 'err' : '' }}"
                               required>
                    </div>
                    @error('email')<div class="err-msg">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label>Phone Number *</label>
                    <div class="input-wrap">
                        <input type="tel"
                               name="phone"
                               value="{{ old('phone') }}"
                               placeholder="Enter your phone number"
                               class="{{ $errors->has('phone') ? 'err' : '' }}"
                               required>
                    </div>
                    @error('phone')<div class="err-msg">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label>User Type *</label>
                    <div class="input-wrap">
                        <select name="role"
                                class="{{ $errors->has('role') ? 'err' : '' }}"
                                required>
                            <option value="">Select your role</option>
                            <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                            <option value="faculty" {{ old('role') === 'faculty' ? 'selected' : '' }}>Faculty</option>
                            <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="clinic_staff" {{ old('role') === 'clinic_staff' ? 'selected' : '' }}>Clinic Staff</option>
                        </select>
                    </div>
                    @error('role')<div class="err-msg">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label>Password *</label>
                    <div class="input-wrap">
                        <input type="password"
                               id="regPw"
                               name="password"
                               placeholder="Create a password (6-8 characters)"
                               class="has-toggle {{ $errors->has('password') ? 'err' : '' }}"
                               maxlength="8"
                               onkeyup="checkPassword()"
                               required>
                        <button type="button" class="toggle-btn"
                                onclick="togglePw('regPw','regPwIcon')"
                                tabindex="-1">
                            <svg id="regPwIcon" width="18" height="18" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    <div class="pwd-hint">Password must be 6–8 characters with uppercase, lowercase, number, and special character.</div>
                    <div class="pwd-req">
                        <li id="pwd-length">❌ At least 6 characters</li>
                        <li id="pwd-max">❌ Maximum 8 characters</li>
                        <li id="pwd-upper">❌ Uppercase letter (A-Z)</li>
                        <li id="pwd-lower">❌ Lowercase letter (a-z)</li>
                        <li id="pwd-number">❌ Number (0-9)</li>
                        <li id="pwd-special">❌ Special character (!@#$%^&*)</li>
                    </div>
                    @error('password')<div class="err-msg">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label>Confirm Password *</label>
                    <div class="input-wrap">
                        <input type="password"
                               id="regConfirmPw"
                               name="password_confirmation"
                               placeholder="Confirm your password"
                               class="has-toggle {{ $errors->has('password_confirmation') ? 'err' : '' }}"
                               maxlength="8"
                               required>
                        <button type="button" class="toggle-btn"
                                onclick="togglePw('regConfirmPw','regConfirmPwIcon')"
                                tabindex="-1">
                            <svg id="regConfirmPwIcon" width="18" height="18" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password_confirmation')<div class="err-msg">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn-submit" id="registerSubmitBtn">
                    <span class="submit-label">Verify Email</span>
                    <span class="submit-loading" hidden>Creating account...</span>
                </button>
            </form>

    <div class="footer-links">
        <a href="{{ route('login') }}">
            Already have an account? <span>Login</span>
        </a>
    </div>

</div>

<script>
var eyeOpen   = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
var eyeClosed = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';

function togglePw(inputId, iconId) {
    var input = document.getElementById(inputId);
    var icon  = document.getElementById(iconId);
    var show  = input.type === 'password';
    input.type = show ? 'text' : 'password';
    icon.innerHTML = show ? eyeOpen : eyeClosed;
}

function checkPassword() {
    var pwd = document.getElementById('regPw').value;

    document.getElementById('pwd-length').className = pwd.length >= 6 ? 'ok' : 'fail';
    document.getElementById('pwd-length').innerHTML = (pwd.length >= 6 ? '✓' : '❌') + ' At least 6 characters';

    document.getElementById('pwd-max').className = pwd.length <= 8 ? 'ok' : 'fail';
    document.getElementById('pwd-max').innerHTML = (pwd.length <= 8 ? '✓' : '❌') + ' Maximum 8 characters';

    document.getElementById('pwd-upper').className = /[A-Z]/.test(pwd) ? 'ok' : 'fail';
    document.getElementById('pwd-upper').innerHTML = (/[A-Z]/.test(pwd) ? '✓' : '❌') + ' Uppercase letter (A-Z)';

    document.getElementById('pwd-lower').className = /[a-z]/.test(pwd) ? 'ok' : 'fail';
    document.getElementById('pwd-lower').innerHTML = (/[a-z]/.test(pwd) ? '✓' : '❌') + ' Lowercase letter (a-z)';

    document.getElementById('pwd-number').className = /[0-9]/.test(pwd) ? 'ok' : 'fail';
    document.getElementById('pwd-number').innerHTML = (/[0-9]/.test(pwd) ? '✓' : '❌') + ' Number (0-9)';

    document.getElementById('pwd-special').className = /[!@#$%^&*()_+\-=[\]{};':"\\|,.<>\/?]/.test(pwd) ? 'ok' : 'fail';
    document.getElementById('pwd-special').innerHTML = (/[!@#$%^&*()_+\-=[\]{};':"\\|,.<>\/?]/.test(pwd) ? '✓' : '❌') + ' Special character (!@#$%^&*)';
}

document.getElementById('registerForm').addEventListener('submit', function() {
    var button = document.getElementById('registerSubmitBtn');
    button.disabled = true;
    button.querySelector('.submit-label').hidden = true;
    button.querySelector('.submit-loading').hidden = false;
});
</script>
</body>
</html>