<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineInventoryLog;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        return view('inventory.medicines');
    }

    public function create()
    {
        if (!in_array(auth()->user()?->role, ['clinic_nurse', 'clinic_staff'], true)) {
            abort(403, 'You are not authorized to create medicines.');
        }

        return view('inventory.medicines-add');
    }

    public function useStock($id, Request $request)
    {
        if (!in_array(auth()->user()?->role, ['clinic_nurse', 'clinic_staff'], true)) {
            abort(403, 'You are not authorized to record medicine dispensed.');
        }

        try {
            $medicine = Medicine::findOrFail($id);
            $quantity = $request->input('quantity', 1);

            if ($quantity > $medicine->quantity) {
                return back()->with('error', 'Not enough stock available!');
            }

            $medicine->update([
                'quantity' => $medicine->quantity - $quantity,
            ]);

            MedicineInventoryLog::create([
                'medicine_id' => $medicine->id,
                'action' => 'used',
                'quantity' => $quantity,
                'notes' => $request->input('notes'),
                'recorded_by' => auth()->id(),
            ]);

            return back()->with('success', $quantity . ' units of ' . $medicine->name . ' used successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update stock. Please try again.');
        }
    }

    public function addStock($id, Request $request)
    {
        if (!in_array(auth()->user()?->role, ['clinic_nurse', 'clinic_staff'], true)) {
            abort(403, 'You are not authorized to modify stock.');
        }

        try {
            $medicine = Medicine::findOrFail($id);
            $quantity = $request->input('quantity', 0);

            $medicine->update([
                'quantity' => $medicine->quantity + $quantity,
            ]);

            MedicineInventoryLog::create([
                'medicine_id' => $medicine->id,
                'action' => 'received',
                'quantity' => $quantity,
                'notes' => $request->input('notes'),
                'recorded_by' => auth()->id(),
            ]);

            return back()->with('success', $quantity . ' units of ' . $medicine->name . ' added successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to add stock. Please try again.');
        }
    }

    public function history($id)
    {
        $medicine = Medicine::findOrFail($id);
        $logs = $medicine->inventoryLogs()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('inventory.medicines-history', compact('medicine', 'logs'));
    }

    public function edit($id)
    {
        if (!in_array(auth()->user()?->role, ['clinic_nurse', 'clinic_staff'], true)) {
            abort(403, 'You are not authorized to edit medicines.');
        }

        return view('inventory.medicines-edit', ['medicineId' => $id]);
    }
}