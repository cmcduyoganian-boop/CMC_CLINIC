<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMC Clinic — Forgot Password</title>
    @vite(['resources/css/app.css'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&display=swap');
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family:'DM Sans',sans-serif; background:#f0f4f8;
            min-height:100vh; display:flex; align-items:center;
            justify-content:center; padding:16px;
        }
        .card {
            background:white; border-radius:20px;
            box-shadow:0 4px 24px rgba(0,0,0,.08);
            width:100%; max-width:380px; padding:36px 32px;
        }
        .logo-wrap { display:flex; flex-direction:column; align-items:center; margin-bottom:24px; }
        .logo-wrap img { width:80px; height:80px; object-fit:contain; margin-bottom:10px; }
        .logo-title { font-size:18px; font-weight:700; color:#0f172a; text-align:center; }
        .logo-sub   { font-size:13px; color:#6b7280; text-align:center; margin-top:2px; }

        .card-desc { font-size:13px; color:#64748b; text-align:center; margin-bottom:20px; line-height:1.6; }

        .alert { padding:10px 14px; border-radius:10px; font-size:13px; margin-bottom:16px; }
        .alert-success { background:#f0fdf4; border:1px solid #bbf7d0; color:#15803d; }
        .alert-error   { background:#fef2f2; border:1px solid #fecaca; color:#dc2626; }

        .field { margin-bottom:14px; }
        .field label { display:block; font-size:12px; font-weight:500; color:#374151; margin-bottom:5px; }
        .input-wrap input {
            width:100%; border:1px solid #e5e7eb; border-radius:10px;
            padding:10px 14px; font-size:13px; font-family:'DM Sans',sans-serif;
            color:#374151; background:#f9fafb; outline:none; transition:all .15s;
        }
        .input-wrap input:focus {
            border-color:#2563eb; background:white;
            box-shadow:0 0 0 3px rgba(37,99,235,.08);
        }
        .err-msg { font-size:11px; color:#ef4444; margin-top:4px; }

        .btn-submit {
            width:100%; background:#2563eb; color:white; border:none;
            border-radius:50px; padding:12px; font-size:14px; font-weight:600;
            font-family:'DM Sans',sans-serif; cursor:pointer; transition:background .2s; margin-top:6px;
        }
        .btn-submit:hover { background:#1d4ed8; }

        .back-link { text-align:center; margin-top:16px; }
        .back-link a { font-size:13px; color:#6b7280; text-decoration:none; }
        .back-link a:hover { color:#2563eb; }
    </style>
</head>
<body>
<div class="card">

    <div class="logo-wrap">
        <img src="{{ asset('images/cmc-logo.png') }}" alt="CMC Logo">
        <div class="logo-title">Carmen Municipal College</div>
        <div class="logo-sub">School Clinic System</div>
    </div>

    <p class="card-desc">
        Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
    </p>

    @if(session('status'))
        <div class="alert alert-success">✅ {{ session('status') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="field">
            <label>Email Address</label>
            <div class="input-wrap">
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="Enter your email"
                       class="{{ $errors->has('email') ? 'err' : '' }}"
                       autofocus required>
            </div>
            @error('email')<div class="err-msg">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn-submit">Send Password Reset Link</button>
    </form>

    <div class="back-link">
        <a href="{{ route('login') }}">← Back to Login</a>
    </div>

</div>
</body>
</html>