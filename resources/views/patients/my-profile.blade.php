<x-app-with-sidebar>
    <x-slot name="header">My Profile</x-slot>

    @php
        $profileUser = auth()->user();
        $profileName = $patient?->name ?? $profileUser->name;
        $profileAvatar = $profileUser?->getAvatarUrl();
        $profileInitial = strtoupper(substr($profileName, 0, 1));
    @endphp

    <div class="patient-profile-page">
        <div class="page-header">
            <div>
                <h1 class="page-title">My Profile</h1>
                <p class="page-subtitle">View and update your personal medical information</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <strong>Please correct the following:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    @if ($profileAvatar)
                        <img src="{{ $profileAvatar }}" alt="{{ $profileName }}">
                    @else
                        <span>{{ $profileInitial }}</span>
                    @endif
                </div>

                <div class="profile-meta">
                    <h2>{{ $profileName }}</h2>
                    <p>{{ $profileUser->getRoleLabel() }}</p>
                </div>
            </div>

            <form action="{{ route('patient.profile.update') }}" method="POST" class="profile-form">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $patient?->name ?? $profileUser->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" value="{{ $profileUser->email }}" disabled>
                    </div>

                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $patient?->phone ?? $profileUser->phone) }}" placeholder="09123456789">
                    </div>

                    <div class="form-group">
                        <label>Age</label>
                        <input type="number" name="age" min="1" max="120" value="{{ old('age', $patient?->age) }}">
                    </div>

                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" required>
                            <option value="student" {{ old('category', $patient?->category ?? $profileUser->role) == 'student' ? 'selected' : '' }}>Student</option>
                            <option value="faculty" {{ old('category', $patient?->category ?? $profileUser->role) == 'faculty' ? 'selected' : '' }}>Faculty</option>
                            <option value="staff" {{ old('category', $patient?->category ?? $profileUser->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Program</label>
                        <input type="text" name="program" value="{{ old('program', $patient?->program) }}" placeholder="BSCS, BSOA, etc.">
                    </div>

                    <div class="form-group full-width">
                        <label>Year / Section</label>
                        <input type="text" name="year_section" value="{{ old('year_section', $patient?->year_section) }}" placeholder="2-A">
                    </div>

                    <div class="form-group full-width">
                        <label>Address</label>
                        <textarea name="address" rows="3" placeholder="Your home address">{{ old('address', $patient?->address) }}</textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i> Save Profile
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <style>
        .patient-profile-page {
            display: flex;
            flex-direction: column;
            gap: 24px;
            padding: 8px 0 28px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 20px;
            padding: 20px 24px;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06);
        }

        .page-title {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            color: var(--text-heading);
        }

        .page-subtitle {
            margin: 6px 0 0;
            font-size: 13px;
            color: var(--text-muted);
        }

        .btn-back, .btn-cancel, .btn-save {
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .btn-back, .btn-cancel {
            background: rgba(255, 255, 255, 0.7);
            color: var(--text-heading);
            padding: 10px 14px;
            border: 1px solid rgba(148, 163, 184, 0.28);
        }

        .btn-back:hover, .btn-cancel:hover {
            background: rgba(148, 163, 184, 0.12);
        }

        .profile-card {
            background: linear-gradient(180deg, rgba(42, 157, 245, 1) 0%, rgba(28, 126, 216, 1) 100%);
            border-radius: 28px;
            padding: 28px 24px 22px;
            box-shadow: 0 24px 50px rgba(30, 96, 170, 0.22);
            color: #ffffff;
            max-width: 640px;
            width: min(100%, 640px);
            margin: 0 auto;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .profile-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 14px;
            margin-bottom: 26px;
            padding-bottom: 18px;
            border-bottom: 1px solid rgba(255,255,255,0.22);
        }

        .profile-avatar {
            width: 112px;
            height: 112px;
            border-radius: 50%;
            background: rgba(255,255,255,0.14);
            border: 4px solid rgba(255,255,255,0.8);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: inset 0 0 0 2px rgba(255,255,255,0.08), 0 10px 20px rgba(9, 59, 105, 0.18);
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .profile-avatar span {
            font-size: 38px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .profile-meta h2 {
            margin: 0;
            font-size: clamp(1.8rem, 2vw, 2.5rem);
            line-height: 1.2;
            color: #ffffff;
            font-weight: 800;
        }

        .profile-meta p {
            margin: 6px 0 0;
            color: rgba(255,255,255,0.9);
            font-size: 14px;
            letter-spacing: 0.02em;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(210px, 1fr));
            gap: 18px 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-size: 12px;
            font-weight: 700;
            color: rgba(255,255,255,0.9);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            border: 1px solid rgba(255,255,255,0.35);
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 15px;
            color: #ffffff;
            background: rgba(15, 23, 42, 0.18);
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.04);
        }

        body[data-theme="light"] .form-group input,
        body[data-theme="light"] .form-group select,
        body[data-theme="light"] .form-group textarea {
            background: rgba(255,255,255,0.18);
            color: #ffffff;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: rgba(255,255,255,0.62);
        }

        .form-group input:disabled {
            opacity: 0.95;
            cursor: not-allowed;
            color: rgba(255,255,255,0.85);
            background: rgba(255,255,255,0.08);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: rgba(255,255,255,0.7);
            box-shadow: 0 0 0 4px rgba(255,255,255,0.12);
        }

        .form-group select {
            appearance: none;
            background-image: linear-gradient(45deg, transparent 50%, rgba(255,255,255,0.8) 50%), linear-gradient(135deg, rgba(255,255,255,0.8) 50%, transparent 50%);
            background-position: calc(100% - 18px) calc(50% - 2px), calc(100% - 13px) calc(50% - 2px);
            background-size: 5px 5px, 5px 5px;
            background-repeat: no-repeat;
            padding-right: 38px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
        }

        .btn-save {
            background: rgba(255,255,255,0.96);
            color: #1f7ed7;
            border: none;
            padding: 12px 18px;
            cursor: pointer;
            box-shadow: 0 12px 24px rgba(12, 76, 140, 0.2);
        }

        .btn-save:hover {
            background: #ffffff;
            transform: translateY(-1px);
        }

        .alert {
            padding: 14px 16px;
            border-radius: 12px;
            display: flex;
            gap: 12px;
            align-items: flex-start;
            font-size: 13px;
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.06);
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.12);
            border: 1px solid rgba(34, 197, 94, 0.2);
            color: var(--text-success);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.09);
            border: 1px solid rgba(239, 68, 68, 0.18);
            color: var(--text-danger);
        }

        .alert ul {
            margin: 4px 0 0 18px;
            padding: 0;
        }

        @media (max-width: 768px) {
            .patient-profile-page {
                gap: 18px;
                padding-top: 4px;
            }

            .page-header {
                padding: 16px 18px;
                border-radius: 16px;
            }

            .page-title {
                font-size: 24px;
            }

            .page-subtitle {
                font-size: 12px;
            }

            .profile-card {
                padding: 20px 14px 16px;
                border-radius: 20px;
            }

            .profile-header {
                gap: 12px;
                margin-bottom: 20px;
                padding-bottom: 14px;
            }

            .profile-avatar {
                width: 88px;
                height: 88px;
            }

            .profile-avatar span {
                font-size: 30px;
            }

            .profile-meta h2 {
                font-size: 1.6rem;
            }

            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-save,
            .btn-cancel {
                width: 100%;
                text-align: center;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .form-group input,
            .form-group select,
            .form-group textarea {
                font-size: 14px;
                padding: 11px 12px;
            }
        }

        @media (max-width: 420px) {
            .page-header {
                padding: 14px 16px;
            }

            .page-title {
                font-size: 22px;
            }

            .profile-card {
                padding-left: 12px;
                padding-right: 12px;
            }

            .profile-avatar {
                width: 76px;
                height: 76px;
            }

            .profile-avatar span {
                font-size: 26px;
            }

            .profile-meta h2 {
                font-size: 1.4rem;
            }
        }
    </style>
</x-app-with-sidebar>
