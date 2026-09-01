<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMC Clinic — Login</title>
    @vite(['resources/css/app.css'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap');

        :root {
            --bg-1: #edf5ff;
            --bg-2: #eef8f4;
            --panel: rgba(255,255,255,0.78);
            --panel-strong: #ffffff;
            --primary: #1877f2;
            --primary-dark: #0e5fd6;
            --primary-soft: #eaf3ff;
            --accent: #12b981;
            --text: #122033;
            --muted: #64748b;
            --stroke: rgba(148, 163, 184, 0.25);
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
            padding:24px;
            color:var(--text);
        }

        .card {
            background: rgba(255,255,255,0.92);
            border: 1px solid rgba(148,163,184,0.18);
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.1);
            width:100%;
            max-width:420px;
            padding:32px 28px;
            backdrop-filter: blur(10px);
        }

        .logo-wrap {
            display:flex;
            flex-direction:column;
            align-items:center;
            margin-bottom:22px;
            text-align:center;
        }

        .logo-wrap img {
            width:78px;
            height:78px;
            object-fit:contain;
            margin-bottom:12px;
        }

        .logo-title {
            font-size:1.1rem;
            font-weight:700;
            color: var(--text);
            letter-spacing:-0.03em;
        }

        .logo-sub {
            font-size:0.78rem;
            color: var(--muted);
            margin-top:4px;
        }

        .alert {
            display:flex;
            align-items:flex-start;
            gap:10px;
            padding:12px 14px;
            border-radius:14px;
            margin-bottom:18px;
            font-size:0.8rem;
            line-height:1.5;
            animation:slideIn 0.3s ease-out;
        }

        .alert i {
            font-size:0.9rem;
            margin-top:2px;
            flex-shrink:0;
        }

        .alert-error {
            background:#fff1f2;
            border:1px solid #fecdd3;
            color:#be123c;
        }

        .alert-success {
            background:#ecfdf5;
            border:1px solid #bbf7d0;
            color:#047857;
        }

        .alert-info {
            background:#eff6ff;
            border:1px solid #bfdbfe;
            color:#1d4ed8;
        }

        @keyframes slideIn {
            from { transform: translateY(-12px); opacity:0; }
            to { transform: translateY(0); opacity:1; }
        }

        .field { margin-bottom:16px; }
        .field label {
            display:block;
            font-size:0.74rem;
            font-weight:600;
            color:#334155;
            margin-bottom:8px;
            letter-spacing:0.01em;
        }

        .input-wrap { position:relative; }

        .input-wrap input {
            width:100%;
            height:48px;
            border:1px solid var(--stroke);
            border-radius:14px;
            padding:12px 14px;
            font-size:0.92rem;
            font-family:'DM Sans',sans-serif;
            color:var(--text);
            background:#f8fbff;
            outline:none;
            transition:all .2s ease;
        }

        .input-wrap input.has-toggle { padding-right:44px; }

        .input-wrap input:focus {
            border-color: rgba(24,119,242,0.5);
            background:#fff;
            box-shadow: 0 0 0 4px rgba(24,119,242,0.08);
        }

        .input-wrap input.err {
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
            transition:color .2s ease;
        }

        .toggle-btn:hover { color:#475569; }

        .err-msg {
            font-size:0.72rem;
            color:#dc2626;
            margin-top:6px;
        }

        .btn-submit {
            width:100%;
            border:none;
            border-radius:14px;
            padding:14px 16px;
            font-size:0.96rem;
            font-weight:700;
            color:#fff;
            background: linear-gradient(135deg, var(--primary) 0%, #0ea5e9 100%);
            box-shadow: 0 16px 30px rgba(24,119,242,0.28);
            cursor:pointer;
            transition: transform .2s ease, box-shadow .2s ease;
            margin-top:6px;
            font-family:'DM Sans',sans-serif;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 36px rgba(24,119,242,0.34);
        }

        .footer-links {
            margin-top:18px;
            display:flex;
            flex-direction:column;
            align-items:center;
            gap:8px;
        }

        .footer-links a {
            font-size:0.82rem;
            color: var(--muted);
            text-decoration:none;
            transition:color .2s ease;
        }

        .footer-links a span {
            color: var(--primary);
            font-weight:600;
        }

        .footer-links a:hover {
            color:#0f172a;
        }

        .forgot-link {
            font-size:0.76rem;
            color:#64748b;
        }

        .forgot-link:hover { color: var(--primary); }

        @media (max-width: 920px) {
            .auth-shell {
                grid-template-columns: 1fr;
                min-height:auto;
            }

            .brand-panel {
                min-height:260px;
                padding:32px 24px;
            }
        }

        @media (max-width: 520px) {
            body { padding:14px; }

            .auth-panel { padding:18px 14px 20px; }
            .card { padding:24px 18px 18px; border-radius:20px; }
            .brand-title { font-size:2rem; }
            .brand-inner { max-width:100%; }
            .feature-item { padding:12px 14px; }
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

            @if (session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info">
                    <i class="fas fa-hourglass-half"></i>
                    {{ session('info') }}
                </div>
            @endif

            @if (session('email_verified'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <strong>Email Verified!</strong><br>
                        Your account has been created and is now <strong>pending admin approval</strong>. You will be notified once approved.
                    </div>
                </div>
            @elseif (session('pending_approval'))
                <div class="alert alert-info">
                    <i class="fas fa-hourglass-half"></i>
                    <div>
                        <strong>Account Pending Approval</strong><br>
                        Your account is pending admin approval. You will be able to log in once the Clinic Nurse approves your account.
                    </div>
                </div>
            @elseif (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label>Username</label>
                    <div class="input-wrap">
                        <input type="text"
                               name="username"
                               value="{{ old('username') }}"
                               placeholder="Enter your username"
                               class="{{ $errors->has('username') ? 'err' : '' }}"
                               autofocus required>
                    </div>
                    @error('username')<div class="err-msg">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label>Password</label>
                    <div class="input-wrap">
                        <input type="password"
                               id="loginPw"
                               name="password"
                               placeholder="Enter your password"
                               class="has-toggle {{ $errors->has('password') ? 'err' : '' }}"
                               required>
                        <button type="button" class="toggle-btn"
                                onclick="togglePw('loginPw','loginPwIcon')"
                                tabindex="-1">
                            <svg id="loginPwIcon" width="18" height="18" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')<div class="err-msg">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn-submit">Login</button>
            </form>

    <div class="footer-links">
        <a href="{{ route('register') }}">
            Don't have an account? <span>Register</span>
        </a>
        <a href="{{ route('password.request') }}"
           class="forgot-link">
            Forgot your password?
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

setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.style.opacity = '0';
        alert.style.transition = 'opacity 0.3s ease';
        setTimeout(() => alert.remove(), 300);
    });
}, 6000);
</script>
</body>
</html>