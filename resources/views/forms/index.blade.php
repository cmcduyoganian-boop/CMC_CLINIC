<x-app-with-sidebar>
    <x-slot name="header">Forms</x-slot>

    <div class="forms-page">
        <div class="forms-search search-section">
            <i class="fas fa-search search-icon" aria-hidden="true"></i>
            <input type="search" class="search-input" placeholder="Search forms..." aria-label="Search forms">
        </div>

        <div class="forms-grid">
            <a href="{{ route('forms.clinic-visit') }}" class="form-card">
                <div class="form-card-icon"><i class="fas fa-notes-medical"></i></div>
                <div class="form-card-body"><h3>Clinic Visit Log</h3><p>Record date, vital signs, complaints, management, and diagnosis.</p></div>
                <i class="fas fa-chevron-right form-card-arrow"></i>
            </a>

            <a href="{{ route('forms.research-consent') }}" class="form-card">
                <div class="form-card-icon"><i class="fas fa-file-signature"></i></div>
                <div class="form-card-body"><h3>Research Data Consent</h3><p>Consent form for accessing clinic and personnel data for research.</p></div>
                <i class="fas fa-chevron-right form-card-arrow"></i>
            </a>

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

    <script>
        document.querySelector('.forms-search input')?.addEventListener('input', function () {
            const term = this.value.trim().toLowerCase();
            document.querySelectorAll('.forms-page .form-card').forEach((card) => {
                card.hidden = term !== '' && !card.textContent.toLowerCase().includes(term);
            });
        });
    </script>

    <style>
        .forms-page {
            padding: 4px;
        }

        .forms-search {
            display: flex;
            align-items: center;
            width: min(420px, 100%);
            min-height: 42px;
            margin-bottom: 24px;
            padding: 0 14px;
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .forms-search .search-icon {
            position: static;
            transform: none;
            flex-shrink: 0;
            color: var(--text-muted);
        }

        .forms-search .search-input {
            width: 100%;
            min-width: 0;
            border: 0;
            outline: 0;
            padding: 9px 0 9px 10px;
            background: transparent;
            color: var(--text-heading);
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
            .forms-search { width: 100%; }
            .forms-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</x-app-with-sidebar>
