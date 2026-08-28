<?php if (isset($component)) { $__componentOriginal5ebdfc507b19f550ccb8283aa8ef688c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5ebdfc507b19f550ccb8283aa8ef688c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'f4ac99e09542ff494432bc959d4fee61::app-with-sidebar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-with-sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

     <?php $__env->slot('header', null, []); ?> Clinic Visits <?php $__env->endSlot(); ?>

    <div class="clinic-visit-list-page">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-left">
                <h1 class="page-title">Clinic Visit Records</h1>
                <p class="page-description">View and manage all clinic visit records</p>
            </div>
            <a href="<?php echo e(route('clinic-visit.index')); ?>" class="btn-new-visit">
                <i class="fas fa-plus"></i> New Visit
            </a>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-group">
                <label class="filter-label">From Date</label>
                <input type="date" class="filter-input" value="2026-04-01">
            </div>
            <div class="filter-group">
                <label class="filter-label">To Date</label>
                <input type="date" class="filter-input" value="2026-04-30">
            </div>
            <div class="filter-group">
                <label class="filter-label">Search Patient</label>
                <input type="text" class="filter-input" placeholder="Enter patient name or ID...">
            </div>
            <button class="btn-filter">
                <i class="fas fa-search"></i> Filter
            </button>
        </div>

        <!-- Clinic Visits Table -->
        <div class="table-container">
            <table class="visits-table">
                <thead>
                    <tr>
                        <th>DATE</th>
                        <th>FULL NAME</th>
                        <th>YEAR & SECTION</th>
                        <th>AGE</th>
                        <th colspan="8">VITAL SIGNS</th>
                        <th>COMPLAINTS</th>
                        <th>DIAGNOSIS</th>
                        <th>MANAGEMENT</th>
                        <th>ACTIONS</th>
                    </tr>
                    <tr class="vital-signs-header">
                        <th colspan="4"></th>
                        <th>T°</th>
                        <th>PR</th>
                        <th>RR</th>
                        <th>BP</th>
                        <th>HT</th>
                        <th>WT</th>
                        <th>BMI</th>
                        <th>SpO2</th>
                        <th colspan="4"></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Visit 1 -->
                    <tr class="visit-row">
                        <td>04/30/2026</td>
                        <td class="patient-name">Juan Dela Cruz</td>
                        <td class="year-section">BSCS-2A</td>
                        <td class="center">20</td>
                        <td class="vital-sign">37.5°C</td>
                        <td class="vital-sign">82</td>
                        <td class="vital-sign">18</td>
                        <td class="vital-sign">120/80</td>
                        <td class="vital-sign">170</td>
                        <td class="vital-sign">65</td>
                        <td class="vital-sign">22.5</td>
                        <td class="vital-sign">98%</td>
                        <td class="text-small">Fever, Cough</td>
                        <td class="text-small">Influenza</td>
                        <td class="text-small">Rest, Fluids, Paracetamol</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-view" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-delete" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Visit 2 -->
                    <tr class="visit-row">
                        <td>04/30/2026</td>
                        <td class="patient-name">Maria Santos</td>
                        <td class="year-section">BSED-1B</td>
                        <td class="center">19</td>
                        <td class="vital-sign">36.9°C</td>
                        <td class="vital-sign">76</td>
                        <td class="vital-sign">16</td>
                        <td class="vital-sign">118/76</td>
                        <td class="vital-sign">160</td>
                        <td class="vital-sign">58</td>
                        <td class="vital-sign">22.6</td>
                        <td class="vital-sign">99%</td>
                        <td class="text-small">Headache</td>
                        <td class="text-small">Migraine</td>
                        <td class="text-small">Rest, Ibuprofen</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-view" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-delete" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Visit 3 -->
                    <tr class="visit-row">
                        <td>04/29/2026</td>
                        <td class="patient-name">Pedro Reyes</td>
                        <td class="year-section">BSHM-3A</td>
                        <td class="center">21</td>
                        <td class="vital-sign">36.8°C</td>
                        <td class="vital-sign">80</td>
                        <td class="vital-sign">17</td>
                        <td class="vital-sign">122/82</td>
                        <td class="vital-sign">175</td>
                        <td class="vital-sign">72</td>
                        <td class="vital-sign">23.4</td>
                        <td class="vital-sign">98%</td>
                        <td class="text-small">Allergy, Itching</td>
                        <td class="text-small">Allergic Reaction</td>
                        <td class="text-small">Antihistamine, Hydration</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-view" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-delete" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Visit 4 -->
                    <tr class="visit-row">
                        <td>04/29/2026</td>
                        <td class="patient-name">Sarah Johnson</td>
                        <td class="year-section">BSAS-2C</td>
                        <td class="center">20</td>
                        <td class="vital-sign">36.7°C</td>
                        <td class="vital-sign">72</td>
                        <td class="vital-sign">16</td>
                        <td class="vital-sign">116/74</td>
                        <td class="vital-sign">168</td>
                        <td class="vital-sign">62</td>
                        <td class="vital-sign">21.9</td>
                        <td class="vital-sign">99%</td>
                        <td class="text-small">Routine Checkup</td>
                        <td class="text-small">Healthy</td>
                        <td class="text-small">Vaccination</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-view" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-delete" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Visit 5 -->
                    <tr class="visit-row">
                        <td>04/28/2026</td>
                        <td class="patient-name">Robert Lopez</td>
                        <td class="year-section">BSCPE-1A</td>
                        <td class="center">19</td>
                        <td class="vital-sign">37.2°C</td>
                        <td class="vital-sign">88</td>
                        <td class="vital-sign">20</td>
                        <td class="vital-sign">124/84</td>
                        <td class="vital-sign">178</td>
                        <td class="vital-sign">74</td>
                        <td class="vital-sign">23.3</td>
                        <td class="vital-sign">97%</td>
                        <td class="text-small">Sore Throat</td>
                        <td class="text-small">Pharyngitis</td>
                        <td class="text-small">Antibiotics, Warm Water Gargle</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-view" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-delete" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination-section">
            <p class="pagination-info">Showing 1 to 5 of 23 visits</p>
            <div class="pagination">
                <button class="btn-page" disabled><i class="fas fa-chevron-left"></i></button>
                <button class="btn-page active">1</button>
                <button class="btn-page">2</button>
                <button class="btn-page">3</button>
                <button class="btn-page">4</button>
                <button class="btn-page">5</button>
                <button class="btn-page"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </div>

    <style>
        .clinic-visit-list-page {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* ════════════════ HEADER ════════════════ */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .header-left {
            flex: 1;
        }

        .page-title {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #2d3e50;
        }

        .page-description {
            margin: 4px 0 0 0;
            font-size: 13px;
            color: #95a5a6;
        }

        .btn-new-visit {
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-new-visit:hover {
            background: #229954;
            transform: translateY(-2px);
        }

        /* ════════════════ FILTER ════════════════ */
        .filter-section {
            background: white;
            border-radius: 10px;
            padding: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            display: flex;
            gap: 16px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-label {
            font-size: 11px;
            font-weight: 700;
            color: #2d3e50;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .filter-input {
            border: 1px solid #e8ecf1;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 12px;
            font-family: 'Figtree', sans-serif;
            background: #f5f7fa;
            min-width: 160px;
        }

        .filter-input:focus {
            outline: none;
            border-color: #3498db;
            background: white;
        }

        .btn-filter {
            background: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            font-family: 'Figtree', sans-serif;
        }

        .btn-filter:hover {
            background: #2980b9;
        }

        /* ════════════════ TABLE ════════════════ */
        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            overflow-x: auto;
        }

        .visits-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .visits-table thead tr:first-child th {
            padding: 14px 10px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            border: 1px solid #2980b9;
        }

        .vital-signs-header th {
            background: #34495e !important;
            padding: 10px 6px !important;
            font-size: 10px !important;
        }

        .visits-table tbody tr {
            border-bottom: 1px solid #e8ecf1;
            transition: all 0.2s;
        }

        .visits-table tbody tr:hover {
            background: #f9fafb;
        }

        .visits-table td {
            padding: 12px 10px;
            color: #2d3e50;
            border-right: 1px solid #e8ecf1;
        }

        .visits-table td:last-child {
            border-right: none;
        }

        .patient-name {
            font-weight: 700;
            color: #3498db;
            min-width: 120px;
        }

        .year-section {
            font-weight: 600;
            color: #9b59b6;
            min-width: 100px;
        }

        .center {
            text-align: center;
        }

        .vital-sign {
            text-align: center;
            font-weight: 600;
            color: #2d3e50;
            min-width: 45px;
        }

        .text-small {
            font-size: 11px;
            color: #95a5a6;
            max-width: 80px;
            white-space: normal;
        }

        /* ════════════════ ACTION BUTTONS ════════════════ */
        .action-buttons {
            display: flex;
            gap: 4px;
            justify-content: center;
        }

        .btn-view,
        .btn-edit,
        .btn-delete {
            border: none;
            background: transparent;
            color: #3498db;
            cursor: pointer;
            padding: 6px;
            border-radius: 4px;
            transition: all 0.2s;
            font-size: 14px;
        }

        .btn-view:hover {
            background: rgba(52, 152, 219, 0.1);
            color: #2980b9;
        }

        .btn-edit:hover {
            background: rgba(241, 196, 15, 0.1);
            color: #f39c12;
        }

        .btn-delete:hover {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }

        /* ════════════════ PAGINATION ════════════════ */
        .pagination-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            border-radius: 10px;
            padding: 16px 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .pagination-info {
            margin: 0;
            font-size: 12px;
            color: #95a5a6;
            font-weight: 600;
        }

        .pagination {
            display: flex;
            gap: 8px;
        }

        .btn-page {
            border: 1px solid #e8ecf1;
            background: white;
            color: #7f8c8d;
            padding: 6px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s;
            font-family: 'Figtree', sans-serif;
        }

        .btn-page:hover:not(:disabled) {
            background: #3498db;
            color: white;
            border-color: #3498db;
        }

        .btn-page.active {
            background: #3498db;
            color: white;
            border-color: #3498db;
        }

        .btn-page:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* ════════════════ RESPONSIVE ════════════════ */
        @media (max-width: 1024px) {
            .visits-table {
                font-size: 11px;
            }

            .visits-table td,
            .visits-table th {
                padding: 10px 8px;
            }

            .text-small {
                max-width: 60px;
                font-size: 10px;
            }
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 16px;
            }

            .btn-new-visit {
                width: 100%;
                justify-content: center;
            }

            .filter-section {
                flex-direction: column;
            }

            .filter-group {
                width: 100%;
            }

            .filter-input {
                width: 100%;
            }

            .btn-filter {
                width: 100%;
                justify-content: center;
            }

            .table-container {
                overflow-x: scroll;
            }

            .visits-table {
                font-size: 10px;
                min-width: 1200px;
            }

            .pagination-section {
                flex-direction: column;
                gap: 12px;
            }

            .pagination {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 22px;
            }

            .visits-table {
                font-size: 9px;
                min-width: 1400px;
            }

            .visits-table th,
            .visits-table td {
                padding: 8px 4px;
            }

            .action-buttons {
                gap: 2px;
            }

            .btn-view,
            .btn-edit,
            .btn-delete {
                padding: 4px;
                font-size: 12px;
            }
        }
    </style>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5ebdfc507b19f550ccb8283aa8ef688c)): ?>
<?php $attributes = $__attributesOriginal5ebdfc507b19f550ccb8283aa8ef688c; ?>
<?php unset($__attributesOriginal5ebdfc507b19f550ccb8283aa8ef688c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5ebdfc507b19f550ccb8283aa8ef688c)): ?>
<?php $component = $__componentOriginal5ebdfc507b19f550ccb8283aa8ef688c; ?>
<?php unset($__componentOriginal5ebdfc507b19f550ccb8283aa8ef688c); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\cmc_clinic\resources\views\clinic-visit\list.blade.php ENDPATH**/ ?>