<x-app-with-sidebar>
    <x-slot name="header">Forms</x-slot>

    <div class="forms-page">
        <div class="forms-header">
            <div class="forms-header-text">
                <h1 class="forms-title">Forms &amp; Documents</h1>
                <p class="forms-subtitle">Access and complete clinic forms, consent documents, and health records</p>
            </div>
        </div>

        <div class="forms-search search-section">
            <i class="fas fa-search search-icon" aria-hidden="true"></i>
            <input type="search" class="search-input" placeholder="Search forms..." aria-label="Search forms">
        </div>

        <div class="forms-grid">
            <a href="{{ route('forms.clinic-visit') }}" class="form-card">
                <div class="form-card-icon"><i class="fas fa-notes-medical"></i></div>
                <div class="form-card-body">
                    <h3>Clinic Visit Log</h3>
                    <p>Record date, vital signs, complaints, management, and diagnosis.</p>
                </div>
                <div class="form-card-action"><i class="fas fa-arrow-right"></i></div>
            </a>

            <a href="{{ route('forms.research-consent') }}" class="form-card">
                <div class="form-card-icon"><i class="fas fa-file-signature"></i></div>
                <div class="form-card-body">
                    <h3>Research Data Consent</h3>
                    <p>Consent form for accessing clinic and personnel data for research.</p>
                </div>
                <div class="form-card-action"><i class="fas fa-arrow-right"></i></div>
            </a>

            <a href="{{ route('forms.consent') }}" class="form-card">
                <div class="form-card-icon"><i class="fas fa-file-medical"></i></div>
                <div class="form-card-body">
                    <h3>Client Consent Form</h3>
                    <p>Printable consent form for clinic visits and treatments.</p>
                </div>
                <div class="form-card-action"><i class="fas fa-arrow-right"></i></div>
            </a>

            <a href="{{ route('forms.student-info') }}" class="form-card">
                <div class="form-card-icon"><i class="fas fa-id-card"></i></div>
                <div class="form-card-body">
                    <h3>Student Health Record</h3>
                    <p>Complete medical history, past surgeries, and family health information.</p>
                </div>
                <div class="form-card-action"><i class="fas fa-arrow-right"></i></div>
            </a>
        </div>

        {{-- ===================== SAVED STUDENT HEALTH RECORDS ===================== --}}
        <livewire:forms.student-records-list />
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
            display: flex;
            flex-direction: column;
            gap: 28px;
        }

        .forms-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .forms-title {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            color: var(--text-heading);
            letter-spacing: -0.3px;
        }

        .forms-subtitle {
            margin: 6px 0 0 0;
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .forms-search {
            display: flex;
            align-items: center;
            width: min(420px, 100%);
            min-height: 48px;
            padding: 0 18px;
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            transition: all 0.2s;
        }

        .forms-search:focus-within {
            border-color: #38bdf8;
            box-shadow: 0 0 0 3px rgba(56,189,248,0.1);
        }

        .forms-search .search-icon {
            position: static;
            transform: none;
            flex-shrink: 0;
            color: var(--text-muted);
            font-size: 14px;
        }

        .forms-search .search-input {
            width: 100%;
            min-width: 0;
            border: 0;
            outline: 0;
            padding: 12px 0 12px 12px;
            background: transparent;
            color: var(--text-heading);
            font-size: 14px;
            font-family: 'Figtree', sans-serif;
        }

        .forms-search .search-input::placeholder {
            color: var(--text-muted);
        }

        .forms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 20px;
        }

        .form-card {
            display: flex;
            align-items: center;
            gap: 18px;
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 14px;
            padding: 22px 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            text-decoration: none;
            color: inherit;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #38bdf8, #2563eb);
            opacity: 0;
            transition: opacity 0.25s;
        }

        .form-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            border-color: rgba(56,189,248,0.2);
        }

        .form-card:hover::before {
            opacity: 1;
        }

        .form-card-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(56,189,248,0.1), rgba(37,99,235,0.1));
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
            transition: all 0.25s;
        }

        .form-card:hover .form-card-icon {
            background: linear-gradient(135deg, #38bdf8, #2563eb);
            color: white;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(56,189,248,0.3);
        }

        .form-card-body {
            flex: 1;
            min-width: 0;
        }

        .form-card-body h3 {
            margin: 0 0 6px 0;
            font-size: 15px;
            font-weight: 700;
            color: var(--text-heading);
            transition: color 0.2s;
        }

        .form-card:hover .form-card-body h3 {
            color: #2563eb;
        }

        .form-card-body p {
            margin: 0;
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .form-card-action {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--bg-input);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 13px;
            flex-shrink: 0;
            transition: all 0.25s;
        }

        .form-card:hover .form-card-action {
            background: #2563eb;
            color: white;
            transform: translateX(4px);
        }

        @media (max-width: 768px) {
            .forms-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .forms-title {
                font-size: 22px;
            }

            .forms-search { width: 100%; }

            .forms-grid {
                grid-template-columns: 1fr;
            }

            .form-card {
                padding: 18px 20px;
            }
        }
    </style>
</x-app-with-sidebar>
