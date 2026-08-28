<?php

namespace App\Livewire\Inventory;

use App\Models\Medicine;
use App\Models\MedicineInventoryLog;
use Livewire\Component;

class MedicineCreateForm extends Component
{
    public $name = '';
    public $category = 'medicine_inventory';
    public $conditionStatus = 'functional';
    public $description = '';
    public $unit = '';
    public $quantity = 0;
    public $minimumStock = 0;
    public $expirationDate = '';
    public $storageLocation = '';

    public function mount()
    {
        if (!in_array(auth()->user()?->role, ['clinic_nurse', 'clinic_staff'], true)) {
            abort(403, 'You are not authorized to create medicines.');
        }
    }

    public function save()
    {
        if (!in_array(auth()->user()?->role, ['clinic_nurse', 'clinic_staff'], true)) {
            abort(403, 'You are not authorized to create medicines.');
        }

        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:medicine_inventory,medicine_supply',
            'conditionStatus' => 'required|in:functional,non_functional',
            'description' => 'nullable|string',
            'unit' => 'required|string',
            'quantity' => 'required|integer|min:0',
            'minimumStock' => 'required|integer|min:0',
            'expirationDate' => 'nullable|date',
            'storageLocation' => 'nullable|string',
        ]);

        $medicine = Medicine::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'condition_status' => $validated['conditionStatus'],
            'description' => $validated['description'],
            'unit' => $validated['unit'],
            'quantity' => $validated['quantity'],
            'minimum_stock' => $validated['minimumStock'],
            'expiration_date' => $validated['expirationDate'] ?: null,
            'storage_location' => $validated['storageLocation'],
            'status' => 'active',
        ]);

        MedicineInventoryLog::create([
            'medicine_id' => $medicine->id,
            'action' => 'received',
            'quantity' => $validated['quantity'],
            'notes' => 'Initial stock',
            'recorded_by' => auth()->id(),
        ]);

        session()->flash('success', 'Medicine added successfully!');

        return redirect()->route('medicines.index');
    }

    public function render()
    {
        return view('livewire.inventory.medicine-create-form');
    }
}