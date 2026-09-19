<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Medicine;
use App\Models\ClinicVisit;

class GlobalSearch extends Component
{
    public string $query = '';
    public bool $open = false;

    public function updatedQuery(): void
    {
        $this->open = strlen(trim($this->query)) >= 2;
    }

    public function getResultsProperty(): array
    {
        $q = trim($this->query);
        if (strlen($q) < 2) {
            return [];
        }

        $results = [];

        // ── Patients ──────────────────────────────────
        $patients = Patient::where('name', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->orWhere('year_section', 'like', "%{$q}%")
            ->limit(5)
            ->get();

        foreach ($patients as $p) {
            $results[] = [
                'type'     => 'patient',
                'label'    => 'Patients',
                'icon'     => 'fas fa-user',
                'color'    => '#38bdf8',
                'title'    => $p->name,
                'meta'     => ucfirst($p->category ?? '') . ($p->year_section ? ' · ' . $p->year_section : ''),
                'url'      => route('patients.show', $p->id),
            ];
        }

        // ── Clinic Visits ─────────────────────────────
        $visits = ClinicVisit::with('patient')
            ->where(function ($query) use ($q) {
                $query->whereHas('patient', fn($q2) => $q2->where('name', 'like', "%{$q}%"))
                      ->orWhere('complaints', 'like', "%{$q}%")
                      ->orWhere('diagnosis', 'like', "%{$q}%");
            })
            ->limit(4)
            ->get();

        foreach ($visits as $v) {
            $results[] = [
                'type'  => 'visit',
                'label' => 'Clinical Records',
                'icon'  => 'fas fa-file-medical',
                'color' => '#8b5cf6',
                'title' => $v->patient->name ?? 'Visit #' . $v->id,
                'meta'     => ($v->visit_date ? \Carbon\Carbon::parse($v->visit_date)->format('M d, Y') : '') .
                           ($v->complaints ? ' · ' . \Str::limit($v->complaints, 40) : ''),
                'url'   => route('clinic-visit.show', $v->id),
            ];
        }

        // ── Appointments ──────────────────────────────
        $appointments = Appointment::with('patient')
            ->where(function ($query) use ($q) {
                $query->whereHas('patient', fn($q2) => $q2->where('name', 'like', "%{$q}%"))
                      ->orWhere('reason', 'like', "%{$q}%");
            })
            ->limit(4)
            ->get();

        foreach ($appointments as $a) {
            $results[] = [
                'type'  => 'appointment',
                'label' => 'Appointments',
                'icon'  => 'fas fa-calendar-check',
                'color' => '#27ae60',
                'title' => $a->patient->name ?? 'Appointment #' . $a->id,
                'meta'  => ($a->appointment_date ? \Carbon\Carbon::parse($a->appointment_date)->format('M d, Y') : '') .
                           ' · ' . ucfirst($a->status ?? ''),
                'url'   => route('appointments.index'),
            ];
        }

        // ── Medicines ─────────────────────────────────
        $medicines = Medicine::where('name', 'like', "%{$q}%")
            ->orWhere('description', 'like', "%{$q}%")
            ->limit(4)
            ->get();

        foreach ($medicines as $m) {
            $results[] = [
                'type'  => 'medicine',
                'label' => 'Inventory',
                'icon'  => 'fas fa-pills',
                'color' => '#f39c12',
                'title' => $m->name,
                'meta'  => 'Qty: ' . $m->quantity . ' ' . ($m->unit ?? '') . ' · ' . $m->getStatusLabel(),
                'url'   => route('medicines.index'),
            ];
        }

        return $results;
    }

    public function close(): void
    {
        $this->open = false;
        $this->query = '';
    }

    public function render()
    {
        return view('livewire.global-search', [
            'results' => $this->results,
        ]);
    }
}
