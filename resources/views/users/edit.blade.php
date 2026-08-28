<x-app-with-sidebar>
    <div class="edit-container">
        <div class="edit-header">
            <div>
                <a href="{{ route('users.index') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i> Back to Users
                </a>
                <h1>Edit User</h1>
                <p>Update user account information</p>
            </div>
        </div>

        <div class="edit-card">
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

            <form method="POST" action="{{ route('users.update', $user->id) }}" class="edit-form">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <!-- Full Name -->
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            class="form-input @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}"
                            placeholder="Enter full name"
                            required
                        >
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Username (Read-only) -->
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input 
                            type="text" 
                            id="username" 
                            class="form-input"
                            value="{{ $user->username }}"
                            disabled
                        >
                        <small class="form-hint">Username cannot be changed</small>
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
                            value="{{ old('email', $user->email) }}"
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
                            value="{{ old('phone', $user->phone) }}"
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
                            disabled
                        >
                            <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Student</option>
                            <option value="faculty" {{ $user->role === 'faculty' ? 'selected' : '' }}>Faculty</option>
                            <option value="staff" {{ $user->role === 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="clinic_nurse" {{ $user->role === 'clinic_nurse' ? 'selected' : '' }}>Clinic Nurse</option>
                            <option value="clinic_staff" {{ $user->role === 'clinic_staff' ? 'selected' : '' }}>Clinic Staff</option>
                        </select>
                        <small class="form-hint">Role cannot be changed</small>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label for="approval_status">Account Status *</label>
                        <select 
                            id="approval_status" 
                            name="approval_status" 
                            class="form-input @error('approval_status') is-invalid @enderror"
                            required
                        >
                            <option value="approved" {{ $user->approval_status === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="pending" {{ $user->approval_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="disabled" {{ $user->approval_status === 'disabled' ? 'selected' : '' }}>Disabled</option>
                            <option value="rejected" {{ $user->approval_status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        @error('approval_status')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Clinic Information (if applicable) -->
                @if ($user->clinic_name)
                    <div class="form-divider">
                        <h3>Clinic Information</h3>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="clinic_name">Clinic Name</label>
                            <input 
                                type="text" 
                                id="clinic_name" 
                                name="clinic_name" 
                                class="form-input"
                                value="{{ old('clinic_name', $user->clinic_name) }}"
                                placeholder="Enter clinic name"
                            >
                        </div>

                        <div class="form-group">
                            <label for="clinic_phone">Clinic Phone</label>
                            <input 
                                type="text" 
                                id="clinic_phone" 
                                name="clinic_phone" 
                                class="form-input"
                                value="{{ old('clinic_phone', $user->clinic_phone) }}"
                                placeholder="Enter clinic phone"
                            >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="clinic_address">Clinic Address</label>
                            <input 
                                type="text" 
                                id="clinic_address" 
                                name="clinic_address" 
                                class="form-input"
                                value="{{ old('clinic_address', $user->clinic_address) }}"
                                placeholder="Enter clinic address"
                            >
                        </div>

                        <div class="form-group">
                            <label for="clinic_hours">Clinic Hours</label>
                            <input 
                                type="text" 
                                id="clinic_hours" 
                                name="clinic_hours" 
                                class="form-input"
                                value="{{ old('clinic_hours', $user->clinic_hours) }}"
                                placeholder="e.g., 8:00 AM - 5:00 PM"
                            >
                        </div>
                    </div>
                @endif

                <!-- Info Box -->
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Note:</strong> Username and Role cannot be changed. To reset the user's password, use the "Reset Password" action from the user details page.
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .edit-container {
            max-width: 700px;
            margin: 0 auto;
            padding: 24px;
        }

        .edit-header {
            margin-bottom: 32px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e8ecf1;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #3498db;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
            transition: all 0.2s;
        }

        .back-link:hover {
            gap: 10px;
        }

        .edit-header h1 {
            margin: 0 0 6px 0;
            font-size: 32px;
            font-weight: 700;
            color: #1a3a52;
        }

        .edit-header p {
            margin: 0;
            font-size: 13px;
            color: #7f8c8d;
        }

        .edit-card {
            background: white;
            border-radius: 10px;
            padding: 28px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
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
        .edit-form {
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

        .form-input:disabled {
            background: #ecf0f1;
            color: #95a5a6;
            cursor: not-allowed;
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

        .error-message {
            font-size: 11px;
            color: #c0392b;
            margin-top: 2px;
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
            .edit-container {
                padding: 16px;
            }

            .edit-card {
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
</x-app-with-sidebar>