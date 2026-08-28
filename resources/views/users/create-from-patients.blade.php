<x-app-with-sidebar>
    <x-slot name="header">Create Users from Patients</x-slot>

    <div class="bulk-create-page">
        <div class="bulk-card">
            <h1 class="bulk-title">Create Users from Patient List</h1>
            <p class="bulk-subtitle">Automatically create accounts for patients without users</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($patients->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-check-circle"></i>
                    <p>All patients already have user accounts!</p>
                    <a href="{{ route('users.index') }}" class="btn btn-back">
                        Back to Users
                    </a>
                </div>
            @else
                <form action="{{ route('users.bulk-create') }}" method="POST" class="bulk-form">
                    @csrf

                    <div class="patient-list">
                        @foreach($patients as $patient)
                            <label class="patient-item">
                                <input type="checkbox" name="patient_ids[]" value="{{ $patient->id }}" checked>
                                <div class="patient-info">
                                    <div class="patient-name">{{ $patient->name }}</div>
                                    <div class="patient-meta">
                                        {{ $patient->email }} • {{ $patient->category }} • {{ $patient->year_section }}
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('users.index') }}" class="btn btn-cancel">Cancel</a>
                        <button type="submit" class="btn btn-create">
                            <i class="fas fa-users"></i> Create {{ $patients->count() }} Users
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <style>
        .bulk-create-page {
            display: flex;
            justify-content: center;
            padding: 24px;
        }

        .bulk-card {
            background: white;
            border-radius: 10px;
            padding: 32px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            max-width: 700px;
            width: 100%;
        }

        .bulk-title {
            margin: 0 0 8px 0;
            font-size: 24px;
            font-weight: 700;
            color: #2d3e50;
        }

        .bulk-subtitle {
            margin: 0 0 24px 0;
            font-size: 13px;
            color: #95a5a6;
        }

        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            background: #fadbd8;
            border: 1px solid #f5b7b1;
            color: #c0392b;
        }

        .alert ul {
            margin: 0;
            padding-left: 20px;
        }

        .alert ul li {
            font-size: 13px;
            margin-bottom: 6px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #95a5a6;
        }

        .empty-state i {
            font-size: 48px;
            color: #27ae60;
            margin-bottom: 16px;
        }

        .empty-state p {
            margin: 0 0 20px 0;
            font-size: 14px;
        }

        .btn-back {
            background: #3498db;
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }

        .patient-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-height: 500px;
            overflow-y: auto;
            margin-bottom: 24px;
            border: 1px solid #e8ecf1;
            border-radius: 8px;
            padding: 12px;
        }

        .patient-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .patient-item:hover {
            background: #f9fafb;
        }

        .patient-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .patient-info {
            flex: 1;
        }

        .patient-name {
            font-weight: 700;
            color: #2d3e50;
            font-size: 13px;
        }

        .patient-meta {
            font-size: 11px;
            color: #95a5a6;
            margin-top: 4px;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            padding-top: 16px;
            border-top: 1px solid #e8ecf1;
        }

        .btn {
            border: none;
            border-radius: 8px;
            padding: 10px 24px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-cancel {
            background: #ecf0f1;
            color: #7f8c8d;
        }

        .btn-cancel:hover {
            background: #d4d9e0;
        }

        .btn-create {
            background: #27ae60;
            color: white;
        }

        .btn-create:hover {
            background: #229954;
        }

        @media (max-width: 768px) {
            .bulk-card {
                padding: 20px;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</x-app-with-sidebar>