<x-app-with-sidebar>
    <div class="form-container">
        <div class="form-card">
            <div class="form-header">
                <h2>Add New User</h2>
                <p>Create a new user account for the clinic system</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Please fix the following errors:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('users.store') }}" class="user-form">
                @csrf

                <div class="form-row">
                    <!-- Full Name - ✅ SERVES AS IDENTIFIER -->
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            class="form-input @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="Enter full name"
                            required
                        >
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div class="form-group">
                        <label for="username">Username *</label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            class="form-input @error('username') is-invalid @enderror"
                            value="{{ old('username') }}"
                            placeholder="Enter username (no spaces)"
                            required
                        >
                        <small class="form-hint">Used for login - must be unique</small>
                        @error('username')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-input @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            placeholder="Enter email address"
                            required
                        >
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input 
                            type="text" 
                            id="phone" 
                            name="phone" 
                            class="form-input @error('phone') is-invalid @enderror"
                            value="{{ old('phone') }}"
                            placeholder="09123456789"
                            required
                        >
                        @error('phone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <!-- Role -->
                    <div class="form-group">
                        <label for="role">Role *</label>
                        <select 
                            id="role" 
                            name="role" 
                            class="form-input @error('role') is-invalid @enderror"
                            required
                            onchange="updateYearSectionVisibility()"
                        >
                            <option value="">-- Select Role --</option>
                            <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                            <option value="faculty" {{ old('role') === 'faculty' ? 'selected' : '' }}>Faculty</option>
                            <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="clinic_nurse" {{ old('role') === 'clinic_nurse' ? 'selected' : '' }}>Clinic Nurse</option>
                            <option value="clinic_staff" {{ old('role') === 'clinic_staff' ? 'selected' : '' }}>Clinic Staff</option>
                        </select>
                        @error('role')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Year & Section (Only for Students) -->
                    <div class="form-group" id="yearSectionGroup" style="display: none;">
                        <label for="year_section">Year & Section</label>
                        <input 
                            type="text" 
                            id="year_section" 
                            name="year_section" 
                            class="form-input"
                            value="{{ old('year_section') }}"
                            placeholder="e.g., BSIS-4C"
                        >
                    </div>
                </div>

                <!-- PASSWORD SECTION -->
                <div class="form-divider">
                    <h3>Password Setup</h3>
                </div>

                <!-- Auto-Generate Checkbox -->
                <div class="form-group">
                    <label class="checkbox-label">
                        <input 
                            type="checkbox" 
                            id="auto_generate_password"
                            name="auto_generate_password"
                            value="1"
                            checked
                            onchange="togglePasswordMode()"
                        >
                        <span>Auto-generate password (recommended)</span>
                    </label>
                    <small class="form-hint">Password will be: <code id="passwordPreview">random secure password</code></small>
                </div>

                <!-- Custom Password (Hidden by default) -->
                <div class="form-group" id="customPasswordGroup" style="display: none;">
                    <label for="password">Custom Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input @error('password') is-invalid @enderror"
                        placeholder="Enter custom password (6-8 characters)"
                    >
                    <small class="form-hint">Must include uppercase, lowercase, number, and special character</small>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Confirmation (Hidden by default) -->
                <div class="form-group" id="confirmPasswordGroup" style="display: none;">
                    <label for="password_confirmation">Confirm Password</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="form-input"
                        placeholder="Confirm password"
                    >
                </div>

                <!-- Info Box -->
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Default Account Creation:</strong><br>
                        For incoming freshmen without a Student ID, the system uses their Full Name as their identifier. A secure password will be generated automatically.
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
        }

        .form-card {
            background: white;
            border-radius: 10px;
            padding: 28px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .form-header {
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid #e8ecf1;
        }

        .form-header h2 {
            margin: 0 0 6px 0;
            font-size: 24px;
            font-weight: 700;
            color: #1a3a52;
        }

        .form-header p {
            margin: 0;
            font-size: 13px;
            color: #7f8c8d;
        }

        /* ALERTS */
        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
            font-size: 13px;
            line-height: 1.6;
        }

        .alert i {
            font-size: 18px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .alert-error {
            background: #fadbd8;
            color: #c0392b;
            border: 1px solid #f5b7b1;
        }

        .alert ul {
            margin: 8px 0 0 0;
            padding-left: 20px;
        }

        .alert li {
            margin-bottom: 4px;
        }

        /* FORM */
        .user-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
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

        .form-input.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
        }

        .form-hint {
            font-size: 11px;
            color: #95a5a6;
            margin-top: 2px;
        }

        .form-hint code {
            background: #ecf0f1;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            color: #27ae60;
            font-weight: 600;
        }

        .error-message {
            font-size: 11px;
            color: #c0392b;
            margin-top: 2px;
        }

        /* CHECKBOX */
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            cursor: pointer;
            user-select: none;
            font-weight: 500;
        }

        .checkbox-label input[type="checkbox"] {
            cursor: pointer;
            accent-color: #3498db;
            width: 18px;
            height: 18px;
        }

        /* DIVIDER */
        .form-divider {
            padding-top: 12px;
            margin-top: 8px;
            border-top: 2px solid #e8ecf1;
        }

        .form-divider h3 {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
            color: #1a3a52;
        }

        /* INFO BOX */
        .info-box {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            color: #0066cc;
            padding: 14px;
            border-radius: 8px;
            display: flex;
            gap: 12px;
            font-size: 12px;
            line-height: 1.6;
        }

        .info-box i {
            font-size: 16px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .info-box strong {
            display: block;
            margin-bottom: 4px;
        }

        /* ACTIONS */
        .form-actions {
            display: flex;
            gap: 12px;
            padding-top: 12px;
            border-top: 1px solid #e8ecf1;
        }

        .btn {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }

        .btn-secondary {
            background: #ecf0f1;
            color: #2d3e50;
        }

        .btn-secondary:hover {
            background: #d5dbdb;
            transform: translateY(-2px);
        }

        /* RESPONSIVE */
        @media (max-width: 600px) {
            .form-card {
                padding: 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>

    <script>
        // Update year section visibility based on role
        function updateYearSectionVisibility() {
            const role = document.getElementById('role').value;
            const yearSectionGroup = document.getElementById('yearSectionGroup');
            
            if (role === 'student') {
                yearSectionGroup.style.display = 'block';
            } else {
                yearSectionGroup.style.display = 'none';
            }
        }

        // Toggle between auto-generate and custom password
        function togglePasswordMode() {
            const autoGenerate = document.getElementById('auto_generate_password').checked;
            const customPasswordGroup = document.getElementById('customPasswordGroup');
            const confirmPasswordGroup = document.getElementById('confirmPasswordGroup');
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');

            if (autoGenerate) {
                customPasswordGroup.style.display = 'none';
                confirmPasswordGroup.style.display = 'none';
                passwordInput.value = '';
                confirmInput.value = '';
            } else {
                customPasswordGroup.style.display = 'block';
                confirmPasswordGroup.style.display = 'block';
            }
        }

        // Initialize visibility on page load
        window.addEventListener('load', function() {
            updateYearSectionVisibility();
            togglePasswordMode();
        });
    </script>

    <!-- ✅ ADD THIS BEFORE CLOSING TAG -->

    <!-- CREDENTIALS MODAL (shown via session flash data) -->
    @if (session('show_password') && session('show_password') === true)
        <div class="credentials-modal-backdrop" id="credentialsModal">
            <div class="credentials-modal">
                <button class="close-modal" onclick="closeCredentialsModal()">
                    <i class="fas fa-times"></i>
                </button>

                <div class="modal-header">
                    <i class="fas fa-check-circle"></i>
                    <h2>Default Account Created Successfully</h2>
                    <p class="modal-countdown">This password will disappear in <strong id="credentialsCountdown">60</strong> seconds.</p>
                </div>

                <div class="modal-content">
                    <div class="credential-item">
                        <label>Full Name</label>
                        <div class="credential-value">
                            {{ session('default_name') }}
                        </div>
                    </div>

                    <div class="credential-item">
                        <label>Username</label>
                        <div class="credential-value copy-group">
                            <span id="usernameValue">{{ session('default_username') }}</span>
                            <button type="button" class="copy-btn" onclick="copyToClipboard('usernameValue', this)">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                        </div>
                    </div>

                    <div class="credential-item">
                        <label>Temporary Password</label>
                        <div class="credential-value copy-group">
                            <span id="passwordValue" class="password-text">{{ session('default_password') }}</span>
                            <button type="button" class="copy-btn" onclick="copyToClipboard('passwordValue', this)">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                            <button type="button" class="toggle-visibility-btn" onclick="togglePasswordField()">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="credential-item">
                        <label>Role</label>
                        <div class="credential-value">
                            {{ session('default_role') }}
                        </div>
                    </div>

                    <div class="credential-item">
                        <label>Status</label>
                        <div class="credential-value">
                            <span class="badge badge-success">Active</span>
                        </div>
                    </div>

                    <div class="important-notice">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <strong>Important:</strong>
                            <ul>
                                <li>Please provide these temporary credentials to the user.</li>
                                <li>The user will be required to change this password after their first login.</li>
                                <li>This password will not be displayed again for security reasons.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeCredentialsModal()">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button type="button" class="btn btn-primary" onclick="printCredentials()">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>
        </div>

        <style>
            .credentials-modal-backdrop {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 16px;
                z-index: 2000;
                animation: fadeIn 0.3s ease;
                overflow-y: auto;
            }

            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            .credentials-modal {
                background: white;
                border-radius: 12px;
                padding: 32px;
                max-width: 500px;
                width: min(500px, 100%);
                max-height: calc(100vh - 32px);
                overflow-y: auto;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                position: relative;
                animation: slideUp 0.3s ease;
            }

            @keyframes slideUp {
                from { 
                    opacity: 0;
                    transform: translateY(20px);
                }
                to { 
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .close-modal {
                position: absolute;
                top: 12px;
                right: 12px;
                background: none;
                border: none;
                font-size: 24px;
                color: #95a5a6;
                cursor: pointer;
                transition: all 0.2s;
            }

            .close-modal:hover {
                color: #e74c3c;
            }

            .modal-header {
                text-align: center;
                margin-bottom: 28px;
            }

            .modal-header i {
                font-size: 48px;
                color: #27ae60;
                margin-bottom: 12px;
                display: block;
            }

            .modal-header h2 {
                margin: 0;
                font-size: clamp(20px, 5vw, 24px);
                font-weight: 700;
                color: #1a3a52;
            }

            .modal-countdown {
                margin: 8px 0 0;
                color: #c0392b;
                font-size: 12px;
                line-height: 1.4;
            }

            .modal-content {
                margin-bottom: 24px;
            }

            .credential-item {
                margin-bottom: 18px;
            }

            .credential-item label {
                display: block;
                font-size: 11px;
                font-weight: 700;
                color: #2d3e50;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 6px;
            }

            .credential-value {
                background: #f9fafb;
                border: 1px solid #e8ecf1;
                border-radius: 8px;
                padding: 12px;
                font-family: 'Courier New', monospace;
                font-size: 14px;
                color: #1a3a52;
                font-weight: 600;
                word-break: break-all;
            }

            .copy-group {
                display: flex;
                align-items: center;
                flex-wrap: wrap;
                gap: 8px;
                padding: 8px;
            }

            .copy-group span {
                flex: 1;
                min-width: 0;
                overflow-wrap: anywhere;
            }

            .copy-btn,
            .toggle-visibility-btn {
                background: #3498db;
                color: white;
                border: none;
                padding: 6px 12px;
                border-radius: 6px;
                font-size: 11px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s;
                display: flex;
                align-items: center;
                gap: 4px;
            }

            .copy-btn:hover,
            .toggle-visibility-btn:hover {
                background: #2980b9;
            }

            .toggle-visibility-btn {
                background: #95a5a6;
                padding: 6px 10px;
            }

            .toggle-visibility-btn:hover {
                background: #7f8c8d;
            }

            .password-text {
                letter-spacing: 2px;
            }

            .password-text.hidden {
                letter-spacing: 6px;
            }

            .important-notice {
                background: #fee2e2;
                border-left: 4px solid #e74c3c;
                padding: 14px;
                border-radius: 6px;
                display: flex;
                gap: 12px;
                font-size: 12px;
                line-height: 1.6;
                color: #c0392b;
                overflow-wrap: anywhere;
            }

            .important-notice i {
                font-size: 18px;
                flex-shrink: 0;
                margin-top: 2px;
            }

            .important-notice ul {
                margin: 6px 0 0 0;
                padding-left: 18px;
            }

            .important-notice li {
                margin-bottom: 4px;
            }

            .badge {
                padding: 6px 12px;
                border-radius: 6px;
                font-size: 11px;
                font-weight: 700;
                display: inline-block;
            }

            .badge-success {
                background: #d1fae5;
                color: #27ae60;
            }

            .modal-actions {
                display: flex;
                gap: 12px;
            }

            .btn {
                flex: 1;
                padding: 12px;
                border: none;
                border-radius: 8px;
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
                background: #3498db;
                color: white;
            }

            .btn-primary:hover {
                background: #2980b9;
            }

            .btn-secondary {
                background: #ecf0f1;
                color: #2d3e50;
            }

            .btn-secondary:hover {
                background: #d5dbdb;
            }

            @media (max-width: 480px) {
                .credentials-modal-backdrop {
                    align-items: flex-start;
                    padding: 10px;
                }

                .credentials-modal {
                    padding: 22px 16px;
                    max-height: calc(100vh - 20px);
                }

                .modal-header h2 {
                    font-size: 20px;
                }

                .modal-header i {
                    font-size: 40px;
                }

                .credential-item {
                    margin-bottom: 14px;
                }

                .credential-value {
                    font-size: 13px;
                }

                .copy-group .copy-btn,
                .copy-group .toggle-visibility-btn {
                    flex: 1;
                    justify-content: center;
                    min-height: 36px;
                }

                .important-notice {
                    font-size: 11px;
                    padding: 12px;
                }

                .modal-actions {
                    flex-direction: column;
                }
            }

            @media print {
                .credentials-modal-backdrop {
                    background: transparent;
                }

                .credentials-modal {
                    box-shadow: none;
                    max-width: 100%;
                    width: 100%;
                }

                .close-modal,
                .modal-actions,
                .copy-btn,
                .toggle-visibility-btn {
                    display: none;
                }
            }
        </style>

        <script>
            function copyToClipboard(elementId, button) {
                const element = document.getElementById(elementId);
                const text = element.textContent;

                navigator.clipboard.writeText(text).then(() => {
                    const originalText = button.innerHTML;
                    button.innerHTML = '<i class="fas fa-check"></i> Copied!';
                    button.style.background = '#27ae60';

                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.style.background = '';
                    }, 2000);
                });
            }

            function togglePasswordField() {
                const passwordValue = document.getElementById('passwordValue');
                passwordValue.classList.toggle('hidden');
            }

            function closeCredentialsModal() {
                const modal = document.getElementById('credentialsModal');
                if (!modal) return;
                clearInterval(credentialsTimer);
                modal.style.animation = 'fadeOut 0.3s ease';
                setTimeout(() => {
                    modal.remove();
                }, 300);
            }

            function printCredentials() {
                window.print();
            }

            let credentialsSeconds = 60;
            const credentialsCountdown = document.getElementById('credentialsCountdown');
            let credentialsTimer = setInterval(() => {
                credentialsSeconds--;
                if (credentialsCountdown) {
                    credentialsCountdown.textContent = credentialsSeconds;
                }
                if (credentialsSeconds <= 0) {
                    clearInterval(credentialsTimer);
                    closeCredentialsModal();
                }
            }, 1000);

            // Close modal when clicking outside
            document.getElementById('credentialsModal')?.addEventListener('click', (e) => {
                if (e.target.id === 'credentialsModal') {
                    closeCredentialsModal();
                }
            });

            // Add fadeOut animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes fadeOut {
                    from { opacity: 1; }
                    to { opacity: 0; }
                }
            `;
            document.head.appendChild(style);
        </script>
    @endif
</x-app-with-sidebar>