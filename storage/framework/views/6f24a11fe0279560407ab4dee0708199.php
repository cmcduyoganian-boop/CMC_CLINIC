<div class="dashboard-wrapper" wire:poll-30000ms>
    <!-- EYEBROW LABEL -->
    <div class="eyebrow-label">
        <span> CMC CLINIC &nbsp;·&nbsp; DASHBOARD</span>
    </div>

    <!-- GREETING HEADER -->
    <div class="greeting-section">
        <div class="greeting-left">
            <h2 class="greeting-title">
                Good <?php echo e(now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening')); ?>, <?php echo e(auth()->user()->name); ?>!
            </h2>
            <p class="greeting-subtitle">Here's what's happening in your clinic today.</p>
        </div>
        <div class="greeting-right">
            <div class="date-selector">
                <i class="fas fa-calendar"></i>
                <span><?php echo e(now()->format('M d, Y')); ?></span>
            </div>
        </div>
    </div>

    <!-- KPI CARDS -->
    <div class="kpi-section">
        <!-- Visits Today -->
        <div class="kpi-card" onclick="navigateToVisits()">
            <div class="kpi-top">
                <div class="kpi-icon visits">
                    <i class="fas fa-user-injured"></i>
                </div>
                <div class="kpi-trend <?php echo e($visitsToday['trendType']); ?>">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visitsToday['trendType'] === 'up'): ?> ↑ <?php else: ?> ↓ <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php echo e(abs($visitsToday['trend'])); ?>%
                </div>
            </div>
            <div class="kpi-value"><?php echo e($visitsToday['total']); ?></div>
            <div class="kpi-label">
                VISITS ·
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($dateRange === 'today'): ?> TODAY
                <?php elseif($dateRange === 'yesterday'): ?> YESTERDAY
                <?php elseif($dateRange === 'last_7'): ?> LAST 7 DAYS
                <?php elseif($dateRange === 'last_30'): ?> LAST 30 DAYS
                <?php elseif($dateRange === 'this_month'): ?> THIS MONTH
                <?php else: ?> CUSTOM
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <svg class="kpi-sparkline" viewBox="0 0 80 24" preserveAspectRatio="none">
                <polyline points="0,20 13,15 26,18 40,10 53,14 67,6 80,9" fill="none" stroke="#38bdf8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

        <!-- Low Stock -->
        <div class="kpi-card" onclick="navigateToMedicines()">
            <div class="kpi-top">
                <div class="kpi-icon inventory">
                    <i class="fas fa-boxes"></i>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lowStockMedicines > 0): ?>
                    <div class="kpi-badge badge-orange"><?php echo e($lowStockMedicines); ?></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="kpi-value"><?php echo e($lowStockMedicines); ?></div>
            <div class="kpi-label">LOW STOCK</div>
            <svg class="kpi-sparkline" viewBox="0 0 80 24" preserveAspectRatio="none">
                <polyline points="0,8 13,12 26,9 40,16 53,11 67,18 80,14" fill="none" stroke="#f39c12" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

        <!-- Pending Appointments -->
        <div class="kpi-card" onclick="navigateToAppointments()">
            <div class="kpi-top">
                <div class="kpi-icon appointments">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
            <div class="kpi-value"><?php echo e($pendingAppointments); ?></div>
            <div class="kpi-label">PENDING APPOINTMENTS</div>
            <svg class="kpi-sparkline" viewBox="0 0 80 24" preserveAspectRatio="none">
                <polyline points="0,14 13,10 26,16 40,8 53,12 67,7 80,10" fill="none" stroke="#27ae60" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

        <!-- Abnormal Vitals -->
        <div class="kpi-card" onclick="navigateToVisits()">
            <div class="kpi-top">
                <div class="kpi-icon vitals">
                    <i class="fas fa-heartbeat"></i>
                </div>
            </div>
            <div class="kpi-value"><?php echo e($abnormalVitals); ?></div>
            <div class="kpi-label">ABNORMAL VITALS</div>
            <svg class="kpi-sparkline" viewBox="0 0 80 24" preserveAspectRatio="none">
                <polyline points="0,12 13,8 26,18 40,6 53,16 67,10 80,14" fill="none" stroke="#e74c3c" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

        <!-- Medicine Expiring Soon -->
        <div class="kpi-card" onclick="navigateToMedicines()">
            <div class="kpi-top">
                <div class="kpi-icon expiring">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($medicineInventory['expiringSoon'] > 0): ?>
                    <div class="kpi-badge badge-orange"><?php echo e($medicineInventory['expiringSoon']); ?></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="kpi-value"><?php echo e($medicineInventory['expiringSoon']); ?></div>
            <div class="kpi-label">MEDICINE EXPIRING</div>
            <svg class="kpi-sparkline" viewBox="0 0 80 24" preserveAspectRatio="none">
                <polyline points="0,6 13,10 26,8 40,14 53,12 67,18 80,20" fill="none" stroke="#e67e22" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>

        <!-- Pending User Approvals -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingUserApprovals > 0): ?>
            <div class="kpi-card" onclick="navigateToUserManagement()">
                <div class="kpi-top">
                    <div class="kpi-icon user-approval">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="kpi-badge badge-purple"><?php echo e($pendingUserApprovals); ?></div>
                </div>
                <div class="kpi-value"><?php echo e($pendingUserApprovals); ?></div>
                <div class="kpi-label">PENDING APPROVALS</div>
                <svg class="kpi-sparkline" viewBox="0 0 80 24" preserveAspectRatio="none">
                    <polyline points="0,18 13,14 26,20 40,10 53,16 67,8 80,12" fill="none" stroke="#8b5cf6" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- PATIENT LOCATION INSIGHTS -->
    <div class="location-insights-grid">
        <div class="location-kpi-card">
            <div class="location-kpi-icon"><i class="fas fa-location-dot"></i></div>
            <div>
                <div class="location-kpi-label">TOP PATIENT LOCATION</div>
                <div class="location-kpi-value"><?php echo e($patientLocationData['topLocation']); ?></div>
                <div class="location-kpi-count"><?php echo e($patientLocationData['topCount']); ?> clinic visit(s) in selected period</div>
            </div>
        </div>
        <div class="chart-card location-chart-card">
            <div class="chart-header">
                <div>
                    <h3>Clinic Visits by Patient Location</h3>
                    <p class="chart-subtitle">Top locations · selected period</p>
                </div>
            </div>
            <div class="chart-container location-chart-container">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($patientLocationData['labels']) > 0): ?>
                    <canvas id="patientLocationChart"></canvas>
                <?php else: ?>
                    <div class="empty-state"><i class="fas fa-map-marker-alt"></i><p>No patient location data found.</p></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    <!-- FILTERS -->
    <div class="filters-card">
        <div class="filters-header">
            <h3>Filters</h3>
            <button wire:click="resetFilters" class="reset-btn">
                <i class="fas fa-redo"></i> Reset
            </button>
        </div>
        <div class="filters-grid">
            <div class="filter-item">
                <label>Date Range</label>
                <select wire:model.live="dateRange" class="filter-select">
                    <option value="today">Today</option>
                    <option value="yesterday">Yesterday</option>
                    <option value="last_7">Last 7 Days</option>
                    <option value="last_30">Last 30 Days</option>
                    <option value="this_month">This Month</option>
                    <option value="custom">Custom</option>
                </select>
            </div>
            <div class="filter-item">
                <label>Patient Type</label>
                <select wire:model.live="patientType" class="filter-select">
                    <option value="all">All</option>
                    <option value="student">Students</option>
                    <option value="faculty">Faculty</option>
                    <option value="staff">Staff</option>
                </select>
            </div>
            <div class="filter-item">
                <label>Visit Type</label>
                <select wire:model.live="visitType" class="filter-select">
                    <option value="all">All</option>
                    <option value="walk_in">Walk-in</option>
                    <option value="appointment">Appointment</option>
                    <option value="follow_up">Follow-up</option>
                </select>
            </div>
        </div>
    </div>

    <!-- MAIN GRID: CHARTS + ACTIVITIES -->
    <div class="main-grid">
        <!-- LEFT: Charts -->
        <div class="chart-section">
            <div class="chart-card">
                <div class="chart-header">
                    <div>
                        <h3>Visits — <?php echo e(ucfirst(str_replace('_', ' ', $dateRange))); ?></h3>
                        <div class="chart-legend">
                            <span class="legend-item"><span class="dot"></span> Visits</span>
                        </div>
                    </div>
                </div>
                <div class="chart-container">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($last7DaysChart['labels']) > 0): ?>
                        <canvas id="visitsChart"></canvas>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>No clinic visits found for the selected period.</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <div>
                        <h3>Visits Trend</h3>
                        <p class="chart-subtitle">Rolling momentum · filtered period</p>
                    </div>
                </div>
                <div class="chart-container">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($visitsTrendData['labels']) > 0): ?>
                        <canvas id="visitsTrendChart"></canvas>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>No data for the selected period.</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        <!-- RIGHT: Activities -->
        <div class="activities-section">
            <div class="activities-card">
                <div class="activities-header">
                    <i class="fas fa-history"></i>
                    <h3>Recent Activities</h3>
                </div>
                <div class="activities-list">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($recentActivities) > 0): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <div class="activity-item">
                                <div class="activity-icon <?php echo e($activity['color']); ?>">
                                    <i class="fas <?php echo e($activity['icon']); ?>"></i>
                                </div>
                                <div class="activity-text">
                                    <p class="activity-name"><?php echo e(Str::limit($activity['message'], 40)); ?></p>
                                    <span class="activity-time"><?php echo e($activity['timestamp']->diffForHumans()); ?></span>
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <?php else: ?>
                        <div class="empty-activities">
                            <p>No recent activities.</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($recentActivities) > 0): ?>
                    <a href="#" class="view-all">View All Activities →</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    <!-- OVERVIEW DONUTS -->
    <div class="overview-grid">
        <div class="overview-card">
            <div class="overview-header">
                <i class="fas fa-heartbeat overview-icon vitals"></i>
                <h3>Vital Signs Overview</h3>
            </div>
            <div class="overview-body">
                <div class="donut-wrapper">
                    <canvas id="vitalsDonut"></canvas>
                    <div class="donut-center">
                        <span class="donut-value"><?php echo e($vitalSignsOverview['total']); ?></span>
                        <span class="donut-label">RECORDS</span>
                    </div>
                </div>
                <div class="overview-legend">
                    <?php
                        $vTotal = max($vitalSignsOverview['total'], 1);
                        $vNormPct = round(($vitalSignsOverview['normal'] / $vTotal) * 100);
                        $vElevPct = round(($vitalSignsOverview['elevated'] / $vTotal) * 100);
                        $vAbnPct  = round(($vitalSignsOverview['abnormal'] / $vTotal) * 100);
                    ?>
                    <div class="legend-row">
                        <span class="legend-dot" style="background:#27ae60;"></span>
                        <span class="legend-label-text">Normal</span>
                        <div class="legend-bar-wrap"><div class="legend-bar" style="width:<?php echo e($vNormPct); ?>%;background:#27ae60;"></div></div>
                        <strong><?php echo e($vNormPct); ?>%</strong>
                    </div>
                    <div class="legend-row">
                        <span class="legend-dot" style="background:#f39c12;"></span>
                        <span class="legend-label-text">Elevated</span>
                        <div class="legend-bar-wrap"><div class="legend-bar" style="width:<?php echo e($vElevPct); ?>%;background:#f39c12;"></div></div>
                        <strong><?php echo e($vElevPct); ?>%</strong>
                    </div>
                    <div class="legend-row">
                        <span class="legend-dot" style="background:#e74c3c;"></span>
                        <span class="legend-label-text">Abnormal</span>
                        <div class="legend-bar-wrap"><div class="legend-bar" style="width:<?php echo e($vAbnPct); ?>%;background:#e74c3c;"></div></div>
                        <strong><?php echo e($vAbnPct); ?>%</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="overview-card">
            <div class="overview-header">
                <i class="fas fa-calendar-check overview-icon appointments"></i>
                <h3>Appointment Status</h3>
            </div>
            <div class="overview-body">
                <div class="donut-wrapper">
                    <canvas id="appointmentsDonut"></canvas>
                    <div class="donut-center">
                        <span class="donut-value"><?php echo e($appointmentStats['total']); ?></span>
                        <span class="donut-label">TOTAL</span>
                    </div>
                </div>
                <div class="overview-legend">
                    <?php
                        $aTotal = max($appointmentStats['total'], 1);
                        $aSchPct  = round(($appointmentStats['scheduled']  / $aTotal) * 100);
                        $aCompPct = round(($appointmentStats['completed']  / $aTotal) * 100);
                        $aNsPct   = round(($appointmentStats['noShow']     / $aTotal) * 100);
                        $aCanPct  = round(($appointmentStats['cancelled']  / $aTotal) * 100);
                    ?>
                    <div class="legend-row">
                        <span class="legend-dot" style="background:#3498db;"></span>
                        <span class="legend-label-text">Scheduled</span>
                        <div class="legend-bar-wrap"><div class="legend-bar" style="width:<?php echo e($aSchPct); ?>%;background:#3498db;"></div></div>
                        <strong><?php echo e($aSchPct); ?>%</strong>
                    </div>
                    <div class="legend-row">
                        <span class="legend-dot" style="background:#27ae60;"></span>
                        <span class="legend-label-text">Completed</span>
                        <div class="legend-bar-wrap"><div class="legend-bar" style="width:<?php echo e($aCompPct); ?>%;background:#27ae60;"></div></div>
                        <strong><?php echo e($aCompPct); ?>%</strong>
                    </div>
                    <div class="legend-row">
                        <span class="legend-dot" style="background:#f39c12;"></span>
                        <span class="legend-label-text">No-show</span>
                        <div class="legend-bar-wrap"><div class="legend-bar" style="width:<?php echo e($aNsPct); ?>%;background:#f39c12;"></div></div>
                        <strong><?php echo e($aNsPct); ?>%</strong>
                    </div>
                    <div class="legend-row">
                        <span class="legend-dot" style="background:#7f8c8d;"></span>
                        <span class="legend-label-text">Cancelled</span>
                        <div class="legend-bar-wrap"><div class="legend-bar" style="width:<?php echo e($aCanPct); ?>%;background:#7f8c8d;"></div></div>
                        <strong><?php echo e($aCanPct); ?>%</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="overview-card">
            <div class="overview-header">
                <i class="fas fa-flask overview-icon medicine"></i>
                <h3>Medicine Stock Health</h3>
            </div>
            <div class="overview-body">
                <div class="donut-wrapper">
                    <canvas id="medicineDonut"></canvas>
                    <div class="donut-center">
                        <span class="donut-value"><?php echo e($medicineInventory['total']); ?></span>
                        <span class="donut-label">MEDICINES</span>
                    </div>
                </div>
                <div class="overview-legend">
                    <?php
                        $mTotal = max($medicineInventory['total'], 1);
                        $mHlthPct = round(($medicineInventory['available']     / $mTotal) * 100);
                        $mLowPct  = round(($medicineInventory['lowStock']      / $mTotal) * 100);
                        $mExpPct  = round(($medicineInventory['expiringSoon']  / $mTotal) * 100);
                    ?>
                    <div class="legend-row">
                        <span class="legend-dot" style="background:#27ae60;"></span>
                        <span class="legend-label-text">Healthy</span>
                        <div class="legend-bar-wrap"><div class="legend-bar" style="width:<?php echo e($mHlthPct); ?>%;background:#27ae60;"></div></div>
                        <strong><?php echo e($mHlthPct); ?>%</strong>
                    </div>
                    <div class="legend-row">
                        <span class="legend-dot" style="background:#e74c3c;"></span>
                        <span class="legend-label-text">Low Stock</span>
                        <div class="legend-bar-wrap"><div class="legend-bar" style="width:<?php echo e($mLowPct); ?>%;background:#e74c3c;"></div></div>
                        <strong><?php echo e($mLowPct); ?>%</strong>
                    </div>
                    <div class="legend-row">
                        <span class="legend-dot" style="background:#f39c12;"></span>
                        <span class="legend-label-text">Expiring</span>
                        <div class="legend-bar-wrap"><div class="legend-bar" style="width:<?php echo e($mExpPct); ?>%;background:#f39c12;"></div></div>
                        <strong><?php echo e($mExpPct); ?>%</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="quick-actions-section">
        <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
        <div class="quick-actions-grid">
            <a href="<?php echo e(route('clinic-visit.create')); ?>" class="action-btn primary">
                <i class="fas fa-plus"></i> New Visit
            </a>
            <a href="<?php echo e(route('medicines.create')); ?>" class="action-btn">
                <i class="fas fa-pills"></i> Add Medicine
            </a>
            <a href="<?php echo e(route('appointments.create')); ?>" class="action-btn">
                <i class="fas fa-calendar-plus"></i> Schedule
            </a>
            <a href="<?php echo e(route('reports.index')); ?>" class="action-btn">
                <i class="fas fa-file-pdf"></i> Generate Report
            </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingUserApprovals > 0): ?>
                <a href="<?php echo e(route('admin.users')); ?>" class="action-btn warning">
                    <i class="fas fa-user-check"></i> Approve Users
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <!-- STYLES -->
    <style>
        .dashboard-wrapper {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* EYEBROW LABEL */
        .eyebrow-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #38bdf8;
            background: rgba(56, 189, 248, 0.08);
            border: 1px solid rgba(56, 189, 248, 0.2);
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: -8px;
            width: fit-content;
        }

        /* GREETING */
        .greeting-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .greeting-title {
            margin: 0 0 6px 0;
            font-size: 28px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .greeting-subtitle {
            margin: 0;
            font-size: 13px;
            color: var(--text-body);
        }

        .date-selector {
            background: var(--bg-card);
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            color: var(--text-heading);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid var(--border-card);
        }

        /* KPI SECTION */
        .kpi-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 8px;
        }

        .kpi-card {
            background: var(--bg-card);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid var(--border-card);
            position: relative;
        }

        .kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            border-color: #38bdf8;
        }

        .kpi-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .kpi-icon {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: white;
        }

        .kpi-icon.visits {
            background: rgba(56, 189, 248, 0.15);
            color: #38bdf8;
        }

        .kpi-icon.inventory {
            background: rgba(243, 156, 18, 0.15);
            color: #f39c12;
        }

        .kpi-icon.appointments {
            background: rgba(39, 174, 96, 0.15);
            color: #27ae60;
        }

        .kpi-icon.vitals {
            background: rgba(231, 76, 60, 0.15);
            color: #e74c3c;
        }

        .kpi-icon.user-approval {
            background: rgba(139, 92, 246, 0.15);
            color: #8b5cf6;
        }

        .kpi-icon.expiring {
            background: rgba(230, 126, 34, 0.15);
            color: #e67e22;
        }

        .kpi-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 700;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }

        .kpi-badge.badge-orange {
            background: #e67e22;
        }

        .kpi-badge.badge-purple {
            background: #8b5cf6;
        }

        /* SPARKLINE */
        .kpi-sparkline {
            display: block;
            width: 100%;
            height: 24px;
            margin-top: 10px;
            opacity: 0.7;
        }

        .kpi-trend {
            font-size: 12px;
            font-weight: 600;
            color: #27ae60;
        }

        .kpi-trend.down {
            color: #e74c3c;
        }

        .kpi-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-heading);
            line-height: 1;
            margin-bottom: 4px;
        }

        .kpi-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* FILTERS */
        .filters-card {
            order: 2;
            background: var(--bg-card);
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
            border: 1px solid var(--border-card);
        }

        .location-insights-grid {
            order: 3;
            display: grid;
            grid-template-columns: minmax(220px, 0.8fr) minmax(0, 2fr);
            gap: 16px;
        }

        .location-kpi-card {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 8px;
            padding: 20px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .location-kpi-icon {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #f97316;
            background: rgba(249, 115, 22, 0.14);
            font-size: 18px;
        }

        .location-kpi-label { color: var(--text-muted); font-size: 10px; font-weight: 700; letter-spacing: .5px; }
        .location-kpi-value { color: var(--text-heading); font-size: 18px; font-weight: 700; margin-top: 7px; overflow-wrap: anywhere; }
        .location-kpi-count { color: var(--text-body); font-size: 11px; margin-top: 7px; }
        .location-chart-card { min-width: 0; }
        .location-chart-container { height: 220px; }

        .filters-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border-inner);
        }

        .filters-header h3 {
            margin: 0;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .reset-btn {
            background: var(--bg-input);
            color: var(--text-body);
            border: 1px solid var(--border-input);
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .reset-btn:hover {
            border-color: #38bdf8;
            color: #38bdf8;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 12px;
        }

        .filter-item label {
            display: block;
            font-size: 10px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            margin-bottom: 6px;
            letter-spacing: 0.4px;
        }

        .filter-select {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid var(--border-input);
            border-radius: 6px;
            font-size: 12px;
            background: var(--bg-input);
            color: var(--text-heading);
            cursor: pointer;
        }

        .filter-select:focus {
            outline: none;
            border-color: #38bdf8;
        }

        /* MAIN GRID */
        .main-grid {
            order: 4;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 16px;
        }

        .chart-section {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .chart-card {
            background: var(--bg-card);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
            border: 1px solid var(--border-card);
        }

        .chart-header h3 {
            margin: 0 0 4px 0;
            font-size: 15px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .chart-subtitle {
            margin: 0;
            font-size: 11px;
            color: var(--text-muted);
        }

        .chart-legend {
            display: flex;
            gap: 16px;
            margin-top: 4px;
        }

        .legend-item {
            font-size: 11px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .legend-item .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #38bdf8;
            display: inline-block;
        }

        .chart-container {
            position: relative;
            height: 260px;
            margin-top: 16px;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: var(--text-muted);
            gap: 8px;
        }

        .empty-state i {
            font-size: 32px;
            opacity: 0.4;
        }

        .empty-state p {
            font-size: 12px;
        }

        /* ACTIVITIES */
        .activities-card {
            background: var(--bg-card);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
            border: 1px solid var(--border-card);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .activities-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            color: var(--text-heading);
        }

        .activities-header h3 {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
        }

        .activities-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            flex: 1;
        }

        .activity-item {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .activity-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            color: white;
            flex-shrink: 0;
        }

        .activity-icon.blue {
            background: #3498db;
        }

        .activity-icon.orange {
            background: #f39c12;
        }

        .activity-icon.red {
            background: #e74c3c;
        }

        .activity-icon.green {
            background: #27ae60;
        }

        .activity-icon.purple {
            background: #8b5cf6;
        }

        .activity-name {
            margin: 0;
            font-size: 12px;
            color: var(--text-heading);
            line-height: 1.4;
        }

        .activity-time {
            font-size: 10px;
            color: var(--text-muted);
        }

        .empty-activities {
            text-align: center;
            padding: 24px 0;
            color: var(--text-muted);
            font-size: 12px;
        }

        .view-all {
            display: block;
            text-align: center;
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px solid var(--border-inner);
            font-size: 12px;
            color: #38bdf8;
            text-decoration: none;
            font-weight: 600;
        }

        .view-all:hover {
            text-decoration: underline;
        }

        /* OVERVIEW DONUTS */
        .overview-grid {
            order: 5;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
        }

        .overview-card {
            background: var(--bg-card);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
            border: 1px solid var(--border-card);
        }

        .overview-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border-inner);
        }

        .overview-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
        }

        .overview-icon.vitals {
            background: rgba(231, 76, 60, 0.15);
            color: #e74c3c;
        }

        .overview-icon.appointments {
            background: rgba(52, 152, 219, 0.15);
            color: #3498db;
        }

        .overview-icon.medicine {
            background: rgba(39, 174, 96, 0.15);
            color: #27ae60;
        }

        .overview-header h3 {
            margin: 0;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-heading);
        }

        .overview-body {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .donut-wrapper {
            position: relative;
            width: 120px;
            height: 120px;
            flex-shrink: 0;
        }

        .donut-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            pointer-events: none;
        }

        .donut-value {
            display: block;
            font-size: 22px;
            font-weight: 700;
            color: var(--text-heading);
            line-height: 1;
        }

        .donut-label {
            display: block;
            font-size: 8px;
            color: var(--text-muted);
            text-transform: uppercase;
            margin-top: 2px;
            letter-spacing: 0.3px;
        }

        .overview-legend {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .legend-row {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            color: var(--text-body);
        }

        .legend-label-text {
            min-width: 52px;
            flex-shrink: 0;
        }

        .legend-bar-wrap {
            flex: 1;
            height: 5px;
            background: var(--border-inner, rgba(255,255,255,0.08));
            border-radius: 3px;
            overflow: hidden;
        }

        .legend-bar {
            height: 100%;
            border-radius: 3px;
            transition: width 0.6s ease;
            min-width: 3px;
        }

        .legend-row strong {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-heading);
            min-width: 34px;
            text-align: right;
            flex-shrink: 0;
        }

        .legend-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* QUICK ACTIONS */
        .quick-actions-section h3 {
            margin: 0 0 12px 0;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-heading);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .quick-actions-section { order: 6; }

        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 12px;
        }

        .action-btn {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 8px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: var(--text-body);
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .action-btn i {
            font-size: 20px;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            border-color: #38bdf8;
            color: #38bdf8;
        }

        .action-btn.primary {
            background: linear-gradient(135deg, #38bdf8, #2563eb);
            border-color: transparent;
            color: white;
        }

        .action-btn.primary:hover {
            color: white;
            opacity: 0.9;
        }

        .action-btn.warning {
            background: rgba(231, 76, 60, 0.1);
            border-color: rgba(231, 76, 60, 0.3);
            color: #e74c3c;
        }

        .action-btn.warning:hover {
            color: #e74c3c;
            border-color: #e74c3c;
        }

        @media (max-width: 1024px) {
            .main-grid {
                grid-template-columns: 1fr;
            }

            .location-insights-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            .greeting-section {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .overview-body {
                flex-direction: column;
                text-align: center;
            }

            .overview-legend {
                width: 100%;
            }
        }
    </style>

    <script>
        let clinicNurseCharts = {};

        function renderClinicNurseCharts() {
            const isDark = document.body.getAttribute('data-theme') !== 'light';
            const gridColor = isDark ? '#111f35' : '#e2e8f0';
            const tickColor = isDark ? '#374e6b' : '#94a3b8';
            const cardBg = isDark ? '#0b1629' : '#ffffff';

            Object.values(clinicNurseCharts).forEach(chart => chart && chart.destroy());
            clinicNurseCharts = {};

            <?php if(count($last7DaysChart['labels']) > 0): ?>
                const visitsCtx = document.getElementById('visitsChart');
                if (visitsCtx) {
                    clinicNurseCharts.visits = new Chart(visitsCtx, {
                        type: 'bar',
                        data: {
                            labels: <?php echo json_encode($last7DaysChart['labels'], 15, 512) ?>,
                            datasets: [{
                                label: 'Visits',
                                data: <?php echo json_encode($last7DaysChart['data'], 15, 512) ?>,
                                backgroundColor: '#38bdf8',
                                borderRadius: 6,
                                maxBarThickness: 36,
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    padding: 12,
                                    borderRadius: 8,
                                    titleFont: { size: 12 },
                                    bodyFont: { size: 12 },
                                },
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: gridColor },
                                    ticks: { font: { size: 11 }, color: tickColor },
                                },
                                x: {
                                    grid: { display: false },
                                    ticks: { font: { size: 11 }, color: tickColor },
                                },
                            },
                        },
                    });
                }
            <?php endif; ?>

            <?php if(count($visitsTrendData['labels']) > 0): ?>
                const trendCtx = document.getElementById('visitsTrendChart');
                if (trendCtx) {
                    clinicNurseCharts.trend = new Chart(trendCtx, {
                        type: 'line',
                        data: {
                            labels: <?php echo json_encode($visitsTrendData['labels'], 15, 512) ?>,
                            datasets: [{
                                label: 'Visits',
                                data: <?php echo json_encode($visitsTrendData['data'], 15, 512) ?>,
                                borderColor: '#2ecc71',
                                backgroundColor: 'rgba(46, 204, 113, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 3,
                                pointBackgroundColor: '#2ecc71',
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: gridColor },
                                    ticks: { font: { size: 11 }, color: tickColor },
                                },
                                x: {
                                    grid: { display: false },
                                    ticks: { font: { size: 11 }, color: tickColor },
                                },
                            },
                        },
                    });
                }
            <?php endif; ?>

            const locationCtx = document.getElementById('patientLocationChart');
            if (locationCtx) {
                clinicNurseCharts.location = new Chart(locationCtx, {
                    type: 'bar',
                    data: {
                        labels: <?php echo json_encode($patientLocationData['labels'], 15, 512) ?>,
                        datasets: [{
                            label: 'Clinic visits',
                            data: <?php echo json_encode($patientLocationData['data'], 15, 512) ?>,
                            backgroundColor: '#f97316',
                            borderRadius: 5,
                            maxBarThickness: 34,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        plugins: {
                            legend: { display: false },
                            tooltip: { callbacks: { label: context => ` ${context.raw} visit(s)` } },
                        },
                        scales: {
                            x: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: tickColor, precision: 0 } },
                            y: { grid: { display: false }, ticks: { color: tickColor, font: { size: 11 } } },
                        },
                    },
                });
            }

            const donutOptions = {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 10,
                        borderRadius: 8,
                    },
                },
            };

            const vitalsCtx = document.getElementById('vitalsDonut');
            if (vitalsCtx) {
                clinicNurseCharts.vitals = new Chart(vitalsCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Normal', 'Elevated', 'Abnormal'],
                        datasets: [{
                            data: [<?php echo e($vitalSignsOverview['normal']); ?>, <?php echo e($vitalSignsOverview['elevated']); ?>, <?php echo e($vitalSignsOverview['abnormal']); ?>],
                            backgroundColor: ['#27ae60', '#f39c12', '#e74c3c'],
                            borderWidth: 3,
                            borderColor: cardBg,
                        }],
                    },
                    options: donutOptions,
                });
            }

            const apptCtx = document.getElementById('appointmentsDonut');
            if (apptCtx) {
                clinicNurseCharts.appointments = new Chart(apptCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Scheduled', 'Completed', 'No-show', 'Cancelled'],
                        datasets: [{
                            data: [<?php echo e($appointmentStats['scheduled']); ?>, <?php echo e($appointmentStats['completed']); ?>, <?php echo e($appointmentStats['noShow']); ?>, <?php echo e($appointmentStats['cancelled']); ?>],
                            backgroundColor: ['#3498db', '#27ae60', '#f39c12', '#7f8c8d'],
                            borderWidth: 3,
                            borderColor: cardBg,
                        }],
                    },
                    options: donutOptions,
                });
            }

            const medCtx = document.getElementById('medicineDonut');
            if (medCtx) {
                clinicNurseCharts.medicine = new Chart(medCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Healthy', 'Low Stock', 'Expiring'],
                        datasets: [{
                            data: [<?php echo e($medicineInventory['available']); ?>, <?php echo e($medicineInventory['lowStock']); ?>, <?php echo e($medicineInventory['expiringSoon']); ?>],
                            backgroundColor: ['#27ae60', '#e74c3c', '#f39c12'],
                            borderWidth: 3,
                            borderColor: cardBg,
                        }],
                    },
                    options: donutOptions,
                });
            }
        }

        renderClinicNurseCharts();
        window.addEventListener('clinic-theme-changed', renderClinicNurseCharts);
        document.addEventListener('livewire:navigated', renderClinicNurseCharts);
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('morph.updated', ({ component }) => {
                if (component?.el?.querySelector('.dashboard-wrapper')) {
                    renderClinicNurseCharts();
                }
            });
        }

        function navigateToVisits() {
            window.location.href = '<?php echo e(route("clinic-visit.index")); ?>';
        }

        function navigateToMedicines() {
            window.location.href = '<?php echo e(route("medicines.index")); ?>';
        }

        function navigateToAppointments() {
            window.location.href = '<?php echo e(route("appointments.index")); ?>';
        }

        function navigateToUserManagement() {
            window.location.href = '<?php echo e(route("users.index")); ?>';
        }
    </script>
</div><?php /**PATH C:\laragon\www\cmc_clinic\resources\views/livewire/dashboard/nurse-dashboard.blade.php ENDPATH**/ ?>