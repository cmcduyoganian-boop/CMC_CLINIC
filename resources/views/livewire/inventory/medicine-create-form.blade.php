<div class="clinic-visit-form-page">
    <!-- Form Header -->
    <div class="form-header">
        <h1 class="form-title">Add New Medicine</h1>
        <p class="form-description">Add a new medicine to the inventory</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin:0;padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit="save" class="clinic-visit-form">
        <div class="form-section">
            <h2 class="section-title">Medicine Details</h2>

            <div class="form-group">
                <label class="form-label">Medicine Name *</label>
                <input type="text" wire:model="name" class="form-control" placeholder="e.g., Paracetamol" required>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label class="form-label">Category *</label>
                    <select wire:model="category" class="form-control" required>
                        <option value="medicine_inventory">Medicine Inventory</option>
                        <option value="medicine_supply">Medicine Supply</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Condition *</label>
                    <select wire:model="conditionStatus" class="form-control" required>
                        <option value="functional">Functional</option>
                        <option value="non_functional">Non-functional</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea wire:model="description" class="form-control" rows="2" placeholder="Short description..."></textarea>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label class="form-label">Unit *</label>
                    <input type="text" wire:model="unit" class="form-control" placeholder="e.g., tablets, bottles, boxes" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Initial Quantity *</label>
                    <input type="number" wire:model="quantity" class="form-control" min="0" required>
                </div>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label class="form-label">Minimum Stock Level *</label>
                    <input type="number" wire:model="minimumStock" class="form-control" min="0" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Expiration Date</label>
                    <input type="date" wire:model="expirationDate" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Storage Location</label>
                <input type="text" wire:model="storageLocation" class="form-control" placeholder="e.g., Cabinet A, Shelf 2">
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="form-actions">
            <a href="{{ route('medicines.index') }}" class="btn btn-cancel">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" class="btn btn-save" wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save"><i class="fas fa-plus"></i> Add Medicine</span>
                <span wire:loading wire:target="save"><i class="fas fa-spinner fa-spin"></i> Saving...</span>
            </button>
        </div>
    </form>
</div>

<style>
    .clinic-visit-form-page {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .form-header { margin-bottom: 8px; }

    .form-title {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: var(--text-heading);
    }

    .form-description {
        margin: 4px 0 0 0;
        font-size: 13px;
        color: var(--text-body);
    }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        display: flex;
        align-items: flex-start;
        gap: 8px;
        font-size: 13px;
    }

    .alert-danger {
        background: rgba(231, 76, 60, 0.1);
        border: 1px solid rgba(231, 76, 60, 0.2);
        color: #e74c3c;
    }

    .alert ul li { font-size: 13px; margin-bottom: 4px; }

    .clinic-visit-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-section {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 8px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    .section-title {
        margin: 0 0 20px 0;
        font-size: 14px;
        font-weight: 700;
        color: var(--text-heading);
        border-bottom: 1px solid var(--border-inner);
        padding-bottom: 12px;
    }

    .form-row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 16px;
    }

    .form-group:last-child { margin-bottom: 0; }

    .form-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .form-control {
        border: 1px solid var(--border-input);
        border-radius: 6px;
        padding: 10px 12px;
        font-size: 13px;
        background: var(--bg-input);
        color: var(--text-heading);
        transition: border-color 0.2s;
        width: 100%;
    }

    .form-control:focus {
        outline: none;
        border-color: #38bdf8;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.1);
    }

    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        padding-top: 20px;
        border-top: 1px solid var(--border-inner);
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
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-cancel {
        background: var(--bg-input);
        border: 1px solid var(--border-input);
        color: var(--text-body);
    }

    .btn-cancel:hover { border-color: #38bdf8; color: #38bdf8; }

    .btn-save {
        background: linear-gradient(135deg, #38bdf8, #2563eb);
        color: white;
        border: none;
    }

    .btn-save:hover:not(:disabled) { opacity: 0.9; transform: translateY(-2px); }
    .btn-save:disabled { opacity: 0.7; cursor: not-allowed; }

    @media (max-width: 768px) {
        .form-row-2 { grid-template-columns: 1fr; }
        .form-actions { flex-direction: column-reverse; }
        .btn { width: 100%; justify-content: center; }
    }

    @media (max-width: 480px) {
        .form-title { font-size: 22px; }
    }
</style>
