<x-app-with-sidebar>
    <x-slot name="header">My Profile</x-slot>

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
                <div class="profile-avatar">{{ strtoupper(substr($patient?->name ?? auth()->user()->name, 0, 1)) }}</div>
                <div>
                    <h2>{{ $patient?->name ?? auth()->user()->name }}</h2>
                    <p>{{ auth()->user()->getRoleLabel() }}</p>
                </div>
            </div>

            <form action="{{ route('patient.profile.update') }}" method="POST" class="profile-form">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $patient?->name ?? auth()->user()->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" value="{{ auth()->user()->email }}" disabled>
                    </div>

                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $patient?->phone ?? auth()->user()->phone) }}" placeholder="09123456789">
                    </div>

                    <div class="form-group">
                        <label>Age</label>
                        <input type="number" name="age" min="1" max="120" value="{{ old('age', $patient?->age) }}">
                    </div>

                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" required>
                            <option value="student" {{ old('category', $patient?->category ?? auth()->user()->role) == 'student' ? 'selected' : '' }}>Student</option>
                            <option value="faculty" {{ old('category', $patient?->category ?? auth()->user()->role) == 'faculty' ? 'selected' : '' }}>Faculty</option>
                            <option value="staff" {{ old('category', $patient?->category ?? auth()->user()->role) == 'staff' ? 'selected' : '' }}>Staff</option>
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
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
        }

        .page-title {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .page-subtitle {
            margin: 4px 0 0;
            font-size: 13px;
            color: var(--text-muted);
        }

        .btn-back, .btn-cancel, .btn-save {
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-back, .btn-cancel {
            background: var(--bg-input);
            color: var(--text-heading);
            padding: 10px 14px;
            border: 1px solid var(--border-input);
        }

        .btn-back:hover, .btn-cancel:hover {
            background: var(--border-input);
        }

        .profile-card {
            background: var(--bg-card);
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--border-inner);
        }

        .profile-avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: 700;
        }

        .profile-header h2 {
            margin: 0;
            font-size: 24px;
            color: var(--text-heading);
        }

        .profile-header p {
            margin: 4px 0 0;
            color: var(--text-muted);
            font-size: 12px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(220px, 1fr));
            gap: 20px;
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
            color: var(--text-label);
            text-transform: uppercase;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            border: 1px solid var(--border-input);
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 14px;
            color: var(--text-heading);
            background: var(--bg-input);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.12);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
        }

        .btn-save {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border: none;
            padding: 12px 18px;
            cursor: pointer;
        }

        .btn-save:hover {
            background: linear-gradient(135deg, #2980b9, #1f6ea5);
        }

        .alert {
            padding: 14px 16px;
            border-radius: 8px;
            display: flex;
            gap: 12px;
            align-items: flex-start;
            font-size: 13px;
        }

        .alert-success {
            background: var(--bg-success);
            border: 1px solid var(--border-success);
            color: var(--text-success);
        }

        .alert-error {
            background: var(--bg-danger);
            border: 1px solid var(--border-danger);
            color: var(--text-danger);
        }

        .alert ul {
            margin: 4px 0 0 18px;
            padding: 0;
        }

        @media (max-width: 768px) {
            .page-header,
            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</x-app-with-sidebar>
