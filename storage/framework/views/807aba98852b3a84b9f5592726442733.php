<div class="inventory-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-left">
            <h1 class="page-title">Medicine Inventory</h1>
            <p class="page-description">Track and manage clinic medicines</p>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->canManage): ?>
            <a href="<?php echo e(route('medicines.create')); ?>" class="btn-add-medicine">
                <i class="fas fa-plus"></i> Add Medicine
            </a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Success Message -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Status Cards -->
    <div class="status-cards">
        <div class="card good">
            <div class="card-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="card-info">
                <h3>Total Medicines</h3>
                <p class="card-value"><?php echo e($totalMedicines); ?></p>
            </div>
        </div>

        <div class="card warning">
            <div class="card-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="card-info">
                <h3>Low Stock</h3>
                <p class="card-value"><?php echo e($lowStock); ?></p>
            </div>
        </div>

        <div class="card danger">
            <div class="card-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="card-info">
                <h3>Expired</h3>
                <p class="card-value"><?php echo e($expiredCount); ?></p>
            </div>
        </div>

        <div class="card expiring">
            <div class="card-icon">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="card-info">
                <h3>Expiring Soon</h3>
                <p class="card-value"><?php echo e($expiringSoonCount); ?></p>
            </div>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($expiringSoonCount > 0): ?>
        <div class="alert alert-warning">
            <i class="fas fa-triangle-exclamation"></i>
            <?php echo e($expiringSoonCount); ?> medicine(s) will expire within 30 days. Please review stock and plan replacement.
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Search & Filter -->
    <div class="search-section">
        <div class="search-group">
            <input type="text" class="search-input" placeholder="Search medicines by name..." wire:model.live.debounce.300ms="search">
            <i class="fas fa-search search-icon"></i>
        </div>
        <select class="filter-select" wire:model.live="statusFilter" wire:change="resetPage">
            <option value="">All Status</option>
            <option value="low">Low Stock</option>
            <option value="out">Out of Stock</option>
            <option value="expired">Expired</option>
            <option value="expiring_soon">Expiring Soon</option>
        </select>
        <select class="filter-select" wire:model.live="categoryFilter" wire:change="resetPage">
            <option value="">All Categories</option>
            <option value="medicine_inventory">Medicine Inventory</option>
            <option value="medicine_supply">Medicine Supply</option>
        </select>
    </div>

    <!-- Medicines Table -->
    <div class="table-container" wire:loading.class="table-loading">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($medicines->isEmpty()): ?>
            <div class="empty-state">
                <i class="fas fa-pill"></i>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($search || $statusFilter || $categoryFilter): ?>
                    <p>No medicines found matching your search/filter.</p>
                <?php else: ?>
                    <p>No medicines in inventory</p>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->canManage): ?>
                        <a href="<?php echo e(route('medicines.create')); ?>" class="btn-empty">
                            Add First Medicine
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php else: ?>
            <table class="medicines-table">
                <thead>
                    <tr>
                        <th>MEDICINE NAME</th>
                        <th>CATEGORY</th>
                        <th>CONDITION</th>
                        <th>QUANTITY</th>
                        <th>UNIT</th>
                        <th>STATUS</th>
                        <th>MIN STOCK</th>
                        <th>EXPIRATION</th>
                        <th>LOCATION</th>
                        <th>LAST STOCK UPDATE</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $medicines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medicine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <tr class="medicine-row <?php echo e($medicine->isExpired() ? 'expired' : ($medicine->isExpiringSoon() ? 'expiring-soon' : '')); ?>" <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'medicine-'.e($medicine->id).''; ?>wire:key="medicine-<?php echo e($medicine->id); ?>">
                            <td class="medicine-name">
                                <?php echo e($medicine->name); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($medicine->description): ?>
                                    <p class="medicine-desc"><?php echo e(Str::limit($medicine->description, 30)); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td><?php echo e($medicine->category === 'medicine_supply' ? 'Medicine Supply' : 'Medicine Inventory'); ?></td>
                            <td>
                                <span class="status-badge <?php echo e($medicine->condition_status === 'functional' ? 'badge-good-stock' : 'badge-expired'); ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $medicine->condition_status))); ?>

                                </span>
                            </td>
                            <td class="quantity-cell">
                                <span class="quantity-badge"><?php echo e($medicine->quantity); ?></span>
                            </td>
                            <td><?php echo e($medicine->unit); ?></td>
                            <td>
                                <span class="status-badge <?php echo e($medicine->getStatusBadgeClass()); ?>">
                                    <?php echo e($medicine->getStatusLabel()); ?>

                                </span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($medicine->isExpired()): ?>
                                    <span class="status-badge badge-expired"><i class="fas fa-clock"></i> Expired</span>
                                <?php elseif($medicine->isExpiringSoon()): ?>
                                    <span class="status-badge badge-expiring-soon"><i class="fas fa-hourglass-half"></i> Expiring Soon</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="min-stock"><?php echo e($medicine->minimum_stock); ?></td>
                            <td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($medicine->expiration_date): ?>
                                    <span class="<?php echo e($medicine->isExpired() ? 'text-danger' : ($medicine->isExpiringSoon() ? 'text-warning' : 'text-muted')); ?>">
                                        <?php echo e($medicine->expiration_date->format('M d, Y')); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="location"><?php echo e($medicine->storage_location ?? '-'); ?></td>
                            <td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($medicine->latestInventoryLog): ?>
                                    <?php echo e($medicine->latestInventoryLog->created_at->format('M d, Y h:i A')); ?>

                                    <small class="text-muted d-block"><?php echo e($medicine->latestInventoryLog->user->name ?? 'System'); ?></small>
                                <?php else: ?>
                                    -
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->canDispense): ?>
                                        <button type="button" class="btn-use" wire:click="openUseModal(<?php echo e($medicine->id); ?>)" title="Use Stock">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->canManage): ?>
                                        <button type="button" class="btn-add-stock" wire:click="openAddModal(<?php echo e($medicine->id); ?>)" title="Add Stock">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <a href="<?php echo e(route('medicines.history', $medicine->id)); ?>" class="btn-history" title="History">
                                        <i class="fas fa-history"></i>
                                    </a>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->canManage): ?>
                                        <a href="<?php echo e(route('medicines.edit', $medicine->id)); ?>" class="btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                <?php echo e($medicines->links()); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Use Stock Modal -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showUseModal): ?>
        <div class="modal show" wire:click.self="closeModals">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Use Medicine</h2>
                    <button type="button" class="close-btn" wire:click="closeModals">&times;</button>
                </div>
                <form wire:submit="confirmUseStock">
                    <div class="modal-body">
                        <p class="modal-medicine-name"><?php echo e($useMedicineName); ?></p>

                        <div class="form-group">
                            <label class="form-label">Quantity to Use</label>
                            <input type="number" wire:model="useQuantity" class="form-control" min="1" max="<?php echo e($useMedicineMax); ?>" required>
                            <small class="form-hint">Available: <?php echo e($useMedicineMax); ?> units</small>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['useQuantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div style="color:#e74c3c;font-size:11px;margin-top:4px;"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Notes</label>
                            <textarea wire:model="useNotes" class="form-control" rows="2" placeholder="Reason for use..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-cancel" wire:click="closeModals">Cancel</button>
                        <button type="submit" class="btn btn-submit-use" wire:loading.attr="disabled" wire:target="confirmUseStock">
                            <span wire:loading.remove wire:target="confirmUseStock">Use Stock</span>
                            <span wire:loading wire:target="confirmUseStock">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Add Stock Modal -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showAddModal): ?>
        <div class="modal show" wire:click.self="closeModals">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Add Medicine Stock</h2>
                    <button type="button" class="close-btn" wire:click="closeModals">&times;</button>
                </div>
                <form wire:submit="confirmAddStock">
                    <div class="modal-body">
                        <p class="modal-medicine-name"><?php echo e($addMedicineName); ?></p>

                        <div class="form-group">
                            <label class="form-label">Quantity to Add</label>
                            <input type="number" wire:model="addQuantity" class="form-control" min="1" required>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['addQuantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div style="color:#e74c3c;font-size:11px;margin-top:4px;"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Notes</label>
                            <textarea wire:model="addNotes" class="form-control" rows="2" placeholder="Source or remarks..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-cancel" wire:click="closeModals">Cancel</button>
                        <button type="submit" class="btn btn-submit-add" wire:loading.attr="disabled" wire:target="confirmAddStock">
                            <span wire:loading.remove wire:target="confirmAddStock">Add Stock</span>
                            <span wire:loading wire:target="confirmAddStock">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

<style>
    .inventory-page {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .header-left { flex: 1; }

    .page-title {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: var(--text-heading);
    }

    .page-description {
        margin: 4px 0 0 0;
        font-size: 13px;
        color: var(--text-body);
    }

    .btn-add-medicine {
        background: linear-gradient(135deg, #38bdf8, #2563eb);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 18px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-add-medicine:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
    }

    .alert-success {
        background: rgba(39,174,96,0.1);
        color: #27ae60;
        border: 1px solid rgba(39,174,96,0.2);
    }

    .alert-warning {
        background: rgba(243,156,18,0.1);
        color: #f39c12;
        border: 1px solid rgba(243,156,18,0.2);
    }

    /* Status KPI Cards */
    .status-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
    }

    .card {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 8px;
        padding: 16px 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        display: flex;
        gap: 16px;
        align-items: center;
        transition: all 0.2s;
    }

    .card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }

    .card-icon {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        flex-shrink: 0;
    }

    .card.good .card-icon    { background: rgba(39,174,96,0.15); color: #27ae60; }
    .card.warning .card-icon { background: rgba(243,156,18,0.15); color: #f39c12; }
    .card.danger .card-icon  { background: rgba(231,76,60,0.15);  color: #e74c3c; }
    .card.expiring .card-icon{ background: rgba(243,156,18,0.15); color: #f39c12; }

    .card-info h3 {
        margin: 0;
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .card-value {
        margin: 6px 0 0 0;
        font-size: 26px;
        font-weight: 700;
        color: var(--text-heading);
    }

    /* Search / Filter Bar */
    .search-section {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 8px;
        padding: 16px;
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .search-group {
        flex: 1;
        position: relative;
    }

    .search-input {
        width: 100%;
        border: 1px solid var(--border-input);
        border-radius: 6px;
        padding: 8px 12px 8px 36px;
        font-size: 13px;
        font-family: 'Figtree', sans-serif;
        background: var(--bg-input);
        color: var(--text-heading);
        box-sizing: border-box;
    }

    .search-input:focus {
        outline: none;
        border-color: #38bdf8;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        pointer-events: none;
    }

    .filter-select {
        border: 1px solid var(--border-input);
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 13px;
        font-family: 'Figtree', sans-serif;
        background: var(--bg-input);
        color: var(--text-heading);
        cursor: pointer;
    }

    .filter-select:focus { outline: none; border-color: #38bdf8; }

    /* Table Container */
    .table-container {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        overflow-x: auto;
        transition: opacity 0.15s;
    }

    .table-container.table-loading { opacity: 0.6; }

    .empty-state {
        padding: 60px 24px;
        text-align: center;
        color: var(--text-muted);
    }

    .empty-state i {
        font-size: 48px;
        opacity: 0.3;
        display: block;
        margin-bottom: 16px;
    }

    .empty-state p {
        margin: 0 0 16px 0;
        font-size: 14px;
    }

    .btn-empty {
        background: linear-gradient(135deg, #38bdf8, #2563eb);
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        transition: all 0.2s;
    }

    .btn-empty:hover { opacity: 0.9; transform: translateY(-1px); }

    /* Table */
    .medicines-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .medicines-table th {
        padding: 12px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: transparent;
        border-bottom: 2px solid var(--border-inner);
    }

    .medicines-table tbody tr {
        border-bottom: 1px solid var(--border-inner);
        transition: all 0.2s;
    }

    .medicines-table tbody tr:hover { background: var(--bg-input); }

    .medicines-table tbody tr.expired {
        background: rgba(231,76,60,0.05);
        border-left: 3px solid #e74c3c;
    }

    .medicines-table tbody tr.expired:hover { background: rgba(231,76,60,0.1); }

    .medicines-table tbody tr.expiring-soon {
        background: rgba(243,156,18,0.05);
        border-left: 3px solid #f39c12;
    }

    .medicines-table tbody tr.expiring-soon:hover { background: rgba(243,156,18,0.1); }

    .medicines-table td {
        padding: 12px 16px;
        color: var(--text-body);
    }

    .medicine-name {
        font-weight: 700;
        color: #38bdf8;
    }

    .medicine-desc {
        margin: 4px 0 0 0;
        font-size: 11px;
        color: var(--text-muted);
    }

    .quantity-cell { text-align: center; }

    .quantity-badge {
        background: rgba(56,189,248,0.1);
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 700;
        color: #38bdf8;
        font-size: 11px;
        display: inline-block;
    }

    /* Status Badges */
    .status-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        margin-right: 4px;
        margin-bottom: 4px;
    }

    .badge-good-stock   { background: rgba(39,174,96,0.1);  color: #27ae60; }
    .badge-low-stock    { background: rgba(231,76,60,0.1);  color: #e74c3c; }
    .badge-out-of-stock { background: rgba(231,76,60,0.1);  color: #e74c3c; }
    .badge-expired      { background: rgba(231,76,60,0.1);  color: #e74c3c; }
    .badge-expiring-soon{ background: rgba(243,156,18,0.1); color: #f39c12; }

    .min-stock { text-align: center; font-weight: 600; }

    .location { font-size: 11px; color: var(--text-muted); }

    .text-danger  { color: #e74c3c; font-weight: 600; }
    .text-warning { color: #f39c12; font-weight: 600; }
    .text-muted   { color: var(--text-muted); }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 6px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-use, .btn-add-stock, .btn-history, .btn-edit {
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 6px 8px;
        border-radius: 6px;
        font-size: 13px;
        transition: all 0.15s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-use          { color: #e74c3c; }
    .btn-use:hover    { background: rgba(231,76,60,0.1); }
    .btn-add-stock    { color: #27ae60; }
    .btn-add-stock:hover { background: rgba(39,174,96,0.1); }
    .btn-history      { color: #38bdf8; }
    .btn-history:hover{ background: rgba(56,189,248,0.1); }
    .btn-edit         { color: #f39c12; }
    .btn-edit:hover   { background: rgba(243,156,18,0.1); }

    /* Pagination */
    .pagination-wrapper {
        padding: 16px 20px;
        border-top: 1px solid var(--border-inner);
        text-align: center;
    }

    /* Modals */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0; top: 0;
        width: 100%; height: 100%;
        background-color: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
    }

    .modal.show { display: flex; }

    .modal-content {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        max-width: 400px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid var(--border-inner);
    }

    .modal-title {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: var(--text-heading);
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 24px;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .close-btn:hover { color: var(--text-heading); }

    .modal-body { padding: 20px; }

    .modal-medicine-name {
        font-weight: 600;
        color: var(--text-heading);
        margin: 0 0 16px 0;
    }

    .modal-footer {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        padding: 16px 20px;
        border-top: 1px solid var(--border-inner);
    }

    .form-group { margin-bottom: 16px; }
    .form-group:last-child { margin-bottom: 0; }

    .form-label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .form-hint {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 4px;
        display: block;
    }

    .form-control {
        width: 100%;
        border: 1px solid var(--border-input);
        border-radius: 6px;
        padding: 10px 12px;
        font-size: 13px;
        font-family: 'Figtree', sans-serif;
        background: var(--bg-input);
        color: var(--text-heading);
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: #38bdf8;
        box-shadow: 0 0 0 3px rgba(56,189,248,0.15);
    }

    .btn {
        border: none;
        border-radius: 6px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Figtree', sans-serif;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-cancel {
        background: var(--bg-input);
        border: 1px solid var(--border-input);
        color: var(--text-body);
    }

    .btn-cancel:hover { opacity: 0.85; }

    .btn-submit-use {
        background: #e74c3c;
        color: white;
    }

    .btn-submit-use:hover:not(:disabled) { background: #c0392b; }

    .btn-submit-add {
        background: linear-gradient(135deg, #38bdf8, #2563eb);
        color: white;
    }

    .btn-submit-add:hover:not(:disabled) { opacity: 0.9; transform: translateY(-1px); }

    @media (max-width: 768px) {
        .medicines-table { font-size: 11px; }
        .medicines-table th, .medicines-table td { padding: 8px; }
        .action-buttons { flex-direction: column; gap: 4px; }
        .status-cards { grid-template-columns: repeat(2, 1fr); }
        .page-header { flex-direction: column; gap: 16px; }
        .btn-add-medicine { width: 100%; justify-content: center; }
        .search-section { flex-direction: column; }
    }

    @media (max-width: 480px) {
        .page-title { font-size: 22px; }
        .status-cards { grid-template-columns: 1fr; }
        .modal-content { width: 95%; max-width: 380px; }
    }
</style>
<?php /**PATH C:\laragon\www\cmc_clinic\resources\views\livewire\inventory\medicine-list.blade.php ENDPATH**/ ?>