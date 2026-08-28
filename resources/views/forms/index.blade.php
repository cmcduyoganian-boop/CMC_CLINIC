<x-app-with-sidebar>
    <x-slot name="header">Forms</x-slot>

    <div class="forms-page">
        <div class="page-header">
            <div class="header-left">
                <h1 class="page-title">Forms</h1>
                <p class="page-description">Printable clinic forms</p>
            </div>
        </div>

        <div class="forms-grid">
            <a href="{{ route('forms.consent') }}" class="form-card">
                <div class="form-card-icon">
                    <i class="fas fa-file-signature"></i>
                </div>
                <div class="form-card-body">
                    <h3>Client Consent Form</h3>
                    <p>Printable consent form for clinic visits and treatments.</p>
                </div>
                <i class="fas fa-chevron-right form-card-arrow"></i>
            </a>

            <a href="{{ route('forms.student-info') }}" class="form-card">
                <div class="form-card-icon">
                    <i class="fas fa-id-card"></i>
                </div>
                <div class="form-card-body">
                    <h3>Student Information Form</h3>
                    <p>Printable form for collecting student personal and medical details.</p>
                </div>
                <i class="fas fa-chevron-right form-card-arrow"></i>
            </a>
        </div>
    </div>

    <style>
        .forms-page {
            padding: 4px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .page-title {
            margin: 0 0 4px 0;
            font-size: 22px;
            font-weight: 700;
            color: #2d3e50;
        }

        .page-description {
            margin: 0;
            font-size: 13px;
            color: #95a5a6;
        }

        .forms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 16px;
        }

        .form-card {
            display: flex;
            align-items: center;
            gap: 16px;
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            text-decoration: none;
            color: inherit;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .form-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .form-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: #eaf4fd;
            color: #3498db;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .form-card-body {
            flex: 1;
        }

        .form-card-body h3 {
            margin: 0 0 4px 0;
            font-size: 15px;
            font-weight: 700;
            color: #2d3e50;
        }

        .form-card-body p {
            margin: 0;
            font-size: 12px;
            color: #95a5a6;
        }

        .form-card-arrow {
            color: #bdc3c7;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .forms-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</x-app-with-sidebar>
