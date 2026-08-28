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

     <?php $__env->slot('header', null, []); ?> Reports & Analytics <?php $__env->endSlot(); ?>

    <div class="reports-page">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Reports & Analytics</h1>
                <p class="page-description">View comprehensive clinic statistics and insights</p>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-users icon"></i>
                <div class="stat-body">
                    <h3>Total Patients</h3>
                    <p class="stat-number"><?php echo e(\App\Models\Patient::count()); ?></p>
                </div>
            </div>

            <div class="stat-card">
                <i class="fas fa-stethoscope icon"></i>
                <div class="stat-body">
                    <h3>Clinic Visits</h3>
                    <p class="stat-number"><?php echo e(\App\Models\ClinicVisit::count()); ?></p>
                </div>
            </div>

            <div class="stat-card">
                <i class="fas fa-calendar-check icon"></i>
                <div class="stat-body">
                    <h3>Completed Appointments</h3>
                    <p class="stat-number"><?php echo e(\App\Models\Appointment::where('status', 'completed')->count()); ?></p>
                </div>
            </div>

            <div class="stat-card">
                <i class="fas fa-pills icon"></i>
                <div class="stat-body">
                    <h3>Medicine Items</h3>
                    <p class="stat-number"><?php echo e(\App\Models\Medicine::count()); ?></p>
                </div>
            </div>
        </div>

        <!-- Report Cards -->
        <div class="reports-grid">
            <!-- Patients Report -->
            <div class="report-card">
                <div class="report-header">
                    <i class="fas fa-users report-icon"></i>
                    <h3>Patient Reports</h3>
                </div>
                <p class="report-description">Patient demographics, categories, and distribution</p>
                <div class="report-stats">
                    <span class="stat-item">
                        <strong><?php echo e(\App\Models\Patient::where('category', 'student')->count()); ?></strong>
                        <small>Students</small>
                    </span>
                    <span class="stat-item">
                        <strong><?php echo e(\App\Models\Patient::where('category', 'faculty')->count()); ?></strong>
                        <small>Faculty</small>
                    </span>
                    <span class="stat-item">
                        <strong><?php echo e(\App\Models\Patient::where('category', 'staff')->count()); ?></strong>
                        <small>Staff</small>
                    </span>
                </div>
                <a href="<?php echo e(route('reports.patients')); ?>" class="btn-view-report">
                    View Report <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <!-- Clinic Visits Report -->
            <div class="report-card">
                <div class="report-header">
                    <i class="fas fa-stethoscope report-icon"></i>
                    <h3>Clinic Visits</h3>
                </div>
                <p class="report-description">Visit statistics, trends, and patterns</p>
                <div class="report-stats">
                    <span class="stat-item">
                        <strong><?php echo e(\App\Models\ClinicVisit::count()); ?></strong>
                        <small>Total Visits</small>
                    </span>
                    <span class="stat-item">
                        <strong><?php echo e(\App\Models\ClinicVisit::whereDate('visit_date', \Carbon\Carbon::today())->count()); ?></strong>
                        <small>Today</small>
                    </span>
                </div>
                <a href="<?php echo e(route('reports.clinic-visits')); ?>" class="btn-view-report">
                    View Report <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <!-- Diagnosis Report -->
            <div class="report-card">
                <div class="report-header">
                    <i class="fas fa-heartbeat report-icon"></i>
                    <h3>Diagnosis Report</h3>
                </div>
                <p class="report-description">Common diagnoses and health patterns</p>
                <div class="report-stats">
                    <span class="stat-item">
                        <strong><?php echo e(\App\Models\ClinicVisit::whereNotNull('diagnosis')->distinct('diagnosis')->count()); ?></strong>
                        <small>Unique Diagnoses</small>
                    </span>
                </div>
                <a href="<?php echo e(route('reports.diagnosis')); ?>" class="btn-view-report">
                    View Report <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <!-- Medicines Report -->
            <div class="report-card">
                <div class="report-header">
                    <i class="fas fa-pills report-icon"></i>
                    <h3>Medicine Inventory</h3>
                </div>
                <p class="report-description">Stock levels, usage, and inventory status</p>
                <div class="report-stats">
                    <span class="stat-item">
                        <strong><?php echo e(\App\Models\Medicine::where('quantity', '<=', \App\Models\Medicine::raw('minimum_stock'))->count()); ?></strong>
                        <small>Low Stock</small>
                    </span>
                </div>
                <a href="<?php echo e(route('reports.medicines')); ?>" class="btn-view-report">
                    View Report <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <!-- Appointments Report -->
            <div class="report-card">
                <div class="report-header">
                    <i class="fas fa-calendar-alt report-icon"></i>
                    <h3>Appointments</h3>
                </div>
                <p class="report-description">Appointment statistics and completion rates</p>
                <div class="report-stats">
                    <span class="stat-item">
                        <strong><?php echo e(\App\Models\Appointment::where('status', 'scheduled')->count()); ?></strong>
                        <small>Upcoming</small>
                    </span>
                </div>
                <a href="<?php echo e(route('reports.appointments')); ?>" class="btn-view-report">
                    View Report <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <!-- Vital Signs Report -->
            <div class="report-card">
                <div class="report-header">
                    <i class="fas fa-thermometer-half report-icon"></i>
                    <h3>Vital Signs</h3>
                </div>
                <p class="report-description">Abnormal readings and health alerts</p>
                <div class="report-stats">
                    <span class="stat-item">
                        <strong><?php echo e(\App\Models\ClinicVisit::where('temperature', '>', 38)->count()); ?></strong>
                        <small>High Fever Cases</small>
                    </span>
                </div>
                <a href="<?php echo e(route('reports.vital-signs')); ?>" class="btn-view-report">
                    View Report <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <style>
        .reports-page {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.2s;
        }

        .stat-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .stat-card .icon {
            font-size: 32px;
            color: #3498db;
            width: 50px;
            height: 50px;
            background: #dbeafe;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-body h3 {
            margin: 0;
            font-size: 12px;
            font-weight: 600;
            color: #95a5a6;
            text-transform: uppercase;
        }

        .stat-number {
            margin: 8px 0 0 0;
            font-size: 28px;
            font-weight: 700;
            color: #2d3e50;
        }

        .reports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .report-card {
            background: white;
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
            gap: 16px;
            transition: all 0.2s;
        }

        .report-card:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
            transform: translateY(-4px);
        }

        .report-header {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .report-icon {
            font-size: 24px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .report-header h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: #2d3e50;
        }

        .report-description {
            margin: 0;
            font-size: 12px;
            color: #95a5a6;
            line-height: 1.4;
        }

        .report-stats {
            display: flex;
            gap: 12px;
        }

        .stat-item {
            flex: 1;
            background: #f9fafb;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            border-left: 3px solid #3498db;
        }

        .stat-item strong {
            display: block;
            font-size: 20px;
            color: #3498db;
            font-weight: 700;
        }

        .stat-item small {
            display: block;
            font-size: 10px;
            color: #95a5a6;
            margin-top: 4px;
            text-transform: uppercase;
        }

        .btn-view-report {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 12px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-view-report:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .reports-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 22px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
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
<?php endif; ?><?php /**PATH C:\laragon\www\cmc_clinic\resources\views\reports\index.blade.php ENDPATH**/ ?>