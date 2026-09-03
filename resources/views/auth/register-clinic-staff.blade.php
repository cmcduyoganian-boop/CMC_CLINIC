<x-guest-layout>
    <div class="registration-container">
        <div class="registration-card">
            <div class="registration-header">
                <img src="{{ asset('images/cmc-logo.png') }}" alt="CMC Logo" class="header-logo">
                <h1>Clinic Staff Registration</h1>
                <p>Create your clinic staff account</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Registration Error:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('register.clinic-staff.store') }}" class="registration-form" id="clinicStaffRegisterForm">
                @csrf

                <!-- Full Name -->
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}"
                        class="form-input @error('name') is-invalid @enderror"
                        placeholder="Enter your full name"
                        required
                    >
                    @error('name')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Username -->
                <div class="form-group">
                    <label for="username">Username *</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        value="{{ old('username') }}"
                        class="form-input @error('username') is-invalid @enderror"
                        placeholder="Choose a username"
                        required
                    >
                    <small class="form-hint">No spaces, letters and numbers only</small>
                    @error('username')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        class="form-input @error('email') is-invalid @enderror"
                        placeholder="Enter your email"
                        required
                    >
                    @error('email')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input 
                        type="text" 
                        id="phone" 
                        name="phone" 
                        value="{{ old('phone') }}"
                        class="form-input @error('phone') is-invalid @enderror"
                        placeholder="09123456789"
                        required
                    >
                    @error('phone')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Clinic Name -->
                <div class="form-group">
                    <label for="clinic_name">Clinic Name *</label>
                    <input 
                        type="text" 
                        id="clinic_name" 
                        name="clinic_name" 
                        value="{{ old('clinic_name') }}"
                        class="form-input @error('clinic_name') is-invalid @enderror"
                        placeholder="Your clinic/facility name"
                        required
                    >
                    @error('clinic_name')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password *</label>
                    <div class="password-input-group">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            value="{{ old('password') }}"
                            class="form-input @error('password') is-invalid @enderror"
                            placeholder="Create a password"
                            required
                            maxlength="8"
                        >
                        <button type="button" class="toggle-password" onclick="togglePassword('password', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <small class="form-hint">6-8 characters, must include uppercase, lowercase, number, and special character</small>
                    @error('password')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password *</label>
                    <div class="password-input-group">
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            value="{{ old('password_confirmation') }}"
                            class="form-input @error('password_confirmation') is-invalid @enderror"
                            placeholder="Confirm your password"
                            required
                            maxlength="8"
                        >
                        <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Important:</strong> Your registration will be submitted for admin approval. You will receive a notification once your account is reviewed and approved. You can then log in with your credentials.
                    </div>
                </div>

                <!-- Register Button -->
                <button type="submit" class="btn btn-primary btn-block" id="clinicStaffRegisterBtn">
                    <span class="submit-label"><i class="fas fa-user-plus"></i> Submit Registration</span>
                    <span class="submit-loading" hidden><i class="fas fa-spinner fa-spin"></i> Sending...</span>
                </button>

                <!-- Login Link -->
                <p class="text-center form-footer">
                    Already have an account? <a href="{{ route('login') }}">Log in here</a>
                </p>
            </form>
        </div>
    </div>

    <style>
        .registration-container {
            max-width: 450px;
            margin: 40px auto;
            padding: 20px;
        }

        .registration-card {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .registration-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .header-logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            margin-bottom: 12px;
        }

        .registration-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1a3a52;
            margin: 0 0 6px 0;
        }

        .registration-header p {
            margin: 0;
            font-size: 13px;
            color: #7f8c8d;
        }

        .alert {
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
            font-size: 12px;
        }

        .alert i {
            font-size: 16px;
            flex-shrink: 0;
        }

        .alert-error {
            background: #fadbd8;
            color: #c0392b;
            border: 1px solid #f5b7b1;
        }

        .alert ul {
            margin: 6px 0 0 0;
            padding-left: 18px;
        }

        .registration-form {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group label {
            font-size: 12px;
            font-weight: 700;
            color: #2d3e50;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            padding: 12px 14px;
            border: 1px solid #e8ecf1;
            border-radius: 8px;
            font-size: 13px;
            color: #2d3e50;
            background: #f9fafb;
            transition: all 0.2s;
            font-family: inherit;
        }

        .form-input:focus {
            outline: none;
            background: white;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .form-input.is-invalid {
            border-color: #e74c3c;
            background: #fadbd8;
        }

        .form-hint {
            font-size: 11px;
            color: #95a5a6;
        }

        .password-input-group {
            position: relative;
        }

        .password-input-group .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #95a5a6;
            cursor: pointer;
            padding: 4px;
        }

        .password-input-group .toggle-password i {
            font-size: 14px;
        }

        .error-text {
            font-size: 11px;
            color: #c0392b;
        }

        .info-box {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            color: #0066cc;
            padding: 12px;
            border-radius: 8px;
            display: flex;
            gap: 12px;
            font-size: 12px;
            line-height: 1.5;
        }

        .info-box i {
            font-size: 14px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .info-box strong {
            display: block;
            margin-bottom: 4px;
        }

        .btn {
            padding: 12px;
            border-radius: 8px;
            border: none;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(52, 152, 219, 0.3);
        }

        .btn-block {
            width: 100%;
        }

        .form-footer {
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
            margin: 16px 0 0 0;
        }

        .form-footer a {
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .registration-card {
                padding: 24px;
            }

            .registration-header h1 {
                font-size: 22px;
            }
        }
    </style>
    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        document.getElementById('clinicStaffRegisterForm').addEventListener('submit', function () {
            var button = document.getElementById('clinicStaffRegisterBtn');
            button.disabled = true;
            button.querySelector('.submit-label').hidden = true;
            button.querySelector('.submit-loading').hidden = false;
        });
    </script>
</x-guest-layout>