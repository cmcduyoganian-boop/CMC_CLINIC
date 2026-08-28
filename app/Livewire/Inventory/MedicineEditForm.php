<?php

namespace App\Livewire\Inventory;

use App\Models\Medicine;
use Livewire\Component;

class MedicineEditForm extends Component
{
    public $medicineId;
    public $medicine;

    public $name = '';
    public $category = 'medicine_inventory';
    public $conditionStatus = 'functional';
    public $description = '';
    public $unit = '';
    public $minimumStock = 0;
    public $expirationDate = '';
    public $storageLocation = '';

    public function mount($medicineId)
    {
        if (!in_array(auth()->user()?->role, ['clinic_nurse', 'clinic_staff'], true)) {
            abort(403, 'You are not authorized to edit medicines.');
        }

        $this->medicineId = $medicineId;
        $this->medicine = Medicine::findOrFail($medicineId);

        $this->name = $this->medicine->name;
        $this->category = $this->medicine->category;
        $this->conditionStatus = $this->medicine->condition_status;
        $this->description = $this->medicine->description;
        $this->unit = $this->medicine->unit;
        $this->minimumStock = $this->medicine->minimum_stock;
        $this->expirationDate = $this->medicine->expiration_date?->format('Y-m-d');
        $this->storageLocation = $this->medicine->storage_location;
    }

    public function save()
    {
        if (!in_array(auth()->user()?->role, ['clinic_nurse', 'clinic_staff'], true)) {
            abort(403, 'You are not authorized to update medicines.');
        }

        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:medicine_inventory,medicine_supply',
            'conditionStatus' => 'required|in:functional,non_functional',
            'description' => 'nullable|string',
            'unit' => 'required|string',
            'minimumStock' => 'required|integer|min:0',
            'expirationDate' => 'nullable|date',
            'storageLocation' => 'nullable|string',
        ]);

        $this->medicine->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'condition_status' => $validated['conditionStatus'],
            'description' => $validated['description'],
            'unit' => $validated['unit'],
            'minimum_stock' => $validated['minimumStock'],
            'expiration_date' => $validated['expirationDate'] ?: null,
            'storage_location' => $validated['storageLocation'],
        ]);

        session()->flash('success', 'Medicine updated successfully!');

        return redirect()->route('medicines.index');
    }

    public function render()
    {
        return view('livewire.inventory.medicine-edit-form');
    }
}