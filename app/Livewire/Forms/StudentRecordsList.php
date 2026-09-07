<?php

namespace App\Livewire\Forms;

use App\Models\StudentHealthRecord;
use Livewire\Component;
use Livewire\WithPagination;

class StudentRecordsList extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $records = StudentHealthRecord::query()
            ->when($this->search !== '', function ($query) {
                $term = '%' . $this->search . '%';
                $query->where(function ($q) use ($term) {
                    $q->where('last_name', 'like', $term)
                        ->orWhere('first_name', 'like', $term)
                        ->orWhere('middle_name', 'like', $term)
                        ->orWhere('student_code', 'like', $term)
                        ->orWhere('course', 'like', $term);
                });
            })
            ->latest('updated_at')
            ->paginate(10);

        return view('livewire.forms.student-records-list', [
            'records' => $records,
        ]);
    }
}