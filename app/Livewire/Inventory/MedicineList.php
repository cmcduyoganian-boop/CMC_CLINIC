<?php

namespace App\Livewire\Inventory;

use App\Models\Medicine;
use App\Models\MedicineInventoryLog;
use Livewire\Component;
use Livewire\WithPagination;

class MedicineList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $categoryFilter = '';

    // ============ USE STOCK MODAL ============
    public $showUseModal = false;
    public $useMedicineId;
    public $useMedicineName;
    public $useMedicineMax = 0;
    public $useQuantity = 1;
    public $useNotes = '';

    // ============ ADD STOCK MODAL ============
    public $showAddModal = false;
    public $addMedicineId;
    public $addMedicineName;
    public $addQuantity = 1;
    public $addNotes = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function getCanManageProperty()
    {
        // Full inventory management (add medicine, edit, add stock) is nurse-only.
        return in_array(auth()->user()?->role, ['clinic_nurse', 'clinic_staff'], true);
    }

    public function getCanDispenseProperty()
    {
        // Clinic Staff may only view inventory and record medicines given to
        // patients (use stock). Nurses retain this ability as well.
        return in_array(auth()->user()?->role, ['clinic_nurse', 'clinic_staff']);
    }

    // ============ USE STOCK (RECORD MEDICINE GIVEN TO PATIENT) ============
    public function openUseModal($id)
    {
        if (!$this->canDispense) {
            return;
        }

        $medicine = Medicine::findOrFail($id);
        $this->useMedicineId = $medicine->id;
        $this->useMedicineName = $medicine->name;
        $this->useMedicineMax = $medicine->quantity;
        $this->useQuantity = 1;
        $this->useNotes = '';
        $this->showUseModal = true;
    }

    public function confirmUseStock()
    {
        if (!$this->canDispense) {
            abort(403, 'You are not authorized to record medicine dispensed.');
        }

        $this->validate([
            'useQuantity' => 'required|integer|min:1|max:' . $this->useMedicineMax,
            'useNotes' => 'nullable|string',
        ]);

        $medicine = Medicine::findOrFail($this->useMedicineId);

        $medicine->update([
            'quantity' => $medicine->quantity - $this->useQuantity,
        ]);

        MedicineInventoryLog::create([
            'medicine_id' => $medicine->id,
            'action' => 'used',
            'quantity' => $this->useQuantity,
            'notes' => $this->useNotes,
            'recorded_by' => auth()->id(),
        ]);

        session()->flash('success', $this->useQuantity . ' units of ' . $medicine->name . ' used successfully!');
        $this->showUseModal = false;
    }

    // ============ ADD STOCK ============
    public function openAddModal($id)
    {
        if (!$this->canManage) {
            return;
        }

        $medicine = Medicine::findOrFail($id);
        $this->addMedicineId = $medicine->id;
        $this->addMedicineName = $medicine->name;
        $this->addQuantity = 1;
        $this->addNotes = '';
        $this->showAddModal = true;
    }

    public function confirmAddStock()
    {
        if (!$this->canManage) {
            abort(403, 'You are not authorized to modify stock.');
        }

        $this->validate([
            'addQuantity' => 'required|integer|min:1',
            'addNotes' => 'nullable|string',
        ]);

        $medicine = Medicine::findOrFail($this->addMedicineId);

        $medicine->update([
            'quantity' => $medicine->quantity + $this->addQuantity,
        ]);

        MedicineInventoryLog::create([
            'medicine_id' => $medicine->id,
            'action' => 'received',
            'quantity' => $this->addQuantity,
            'notes' => $this->addNotes,
            'recorded_by' => auth()->id(),
        ]);

        session()->flash('success', $this->addQuantity . ' units of ' . $medicine->name . ' added successfully!');
        $this->showAddModal = false;
    }

    public function closeModals()
    {
        $this->showUseModal = false;
        $this->showAddModal = false;
    }

    public function render()
    {
        $query = Medicine::with(['latestInventoryLog.user'])->where('status', 'active');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->statusFilter === 'low') {
            $query->whereRaw('quantity <= minimum_stock')->where('quantity', '>', 0);
        } elseif ($this->statusFilter === 'out') {
            $query->where('quantity', '<=', 0);
        } elseif ($this->statusFilter === 'expired') {
            $query->whereNotNull('expiration_date')->whereDate('expiration_date', '<', now());
        } elseif ($this->statusFilter === 'expiring_soon') {
            $query->whereNotNull('expiration_date')
                ->whereDate('expiration_date', '>=', now())
                ->whereDate('expiration_date', '<=', now()->addDays(30));
        }

        if ($this->categoryFilter) {
            $query->where('category', $this->categoryFilter);
        }

        $medicines = $query->orderBy('name')->paginate(15);

        return view('livewire.inventory.medicine-list', [
            'medicines' => $medicines,
            'totalMedicines' => Medicine::where('status', 'active')->count(),
            'lowStock' => Medicine::where('status', 'active')->whereRaw('quantity <= minimum_stock')->count(),
            'expiredCount' => Medicine::where('status', 'active')
                ->whereNotNull('expiration_date')
                ->whereDate('expiration_date', '<', now())
                ->count(),
            'expiringSoonCount' => Medicine::where('status', 'active')
                ->whereNotNull('expiration_date')
                ->whereDate('expiration_date', '>=', now())
                ->whereDate('expiration_date', '<=', now()->addDays(30))
                ->count(),
        ]);
    }
}