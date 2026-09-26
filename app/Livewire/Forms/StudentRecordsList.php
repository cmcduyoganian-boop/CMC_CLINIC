<?php

namespace App\Livewire\Forms;

use App\Models\FormSubmission;
use App\Models\StudentHealthRecord;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
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
        $records = $this->getStudentRecords();
        $page = (int) request()->query('page', 1);
        $perPage = 10;
        $paged = $records->slice(($page - 1) * $perPage, $perPage)->values();

        $records = new LengthAwarePaginator(
            $paged,
            $records->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('livewire.forms.student-records-list', [
            'records' => $records,
        ]);
    }

    protected function getStudentRecords(): Collection
    {
        $studentRecords = StudentHealthRecord::query()
            ->select([
                'id',
                'student_code',
                'last_name',
                'first_name',
                'middle_name',
                'course',
                'year_section',
                'contact_number',
                'updated_at',
            ])
            ->get()
            ->map(function ($record) {
                return [
                    'id' => 'student-health-' . $record->id,
                    'student_code' => $record->student_code,
                    'last_name' => $record->last_name,
                    'first_name' => $record->first_name,
                    'middle_name' => $record->middle_name,
                    'course' => $record->course,
                    'year_section' => $record->year_section,
                    'contact_number' => $record->contact_number,
                    'saved_at' => $record->updated_at,
                ];
            });

        $formSubmissions = FormSubmission::query()
            ->where('form_type', 'student_medical_history')
            ->get()
            ->map(function ($submission) {
                $data = $submission->data ?? [];

                return [
                    'id' => 'form-submission-' . $submission->id,
                    'student_code' => $data['student_code'] ?? null,
                    'last_name' => $data['last_name'] ?? null,
                    'first_name' => $data['first_name'] ?? null,
                    'middle_name' => $data['middle_name'] ?? null,
                    'course' => $data['course'] ?? null,
                    'year_section' => $data['year_section'] ?? null,
                    'contact_number' => $data['contact_number'] ?? null,
                    'saved_at' => $submission->submitted_at ?? $submission->updated_at,
                ];
            });

        $records = $studentRecords->merge($formSubmissions);

        if ($this->search !== '') {
            $term = strtolower(trim($this->search));
            $records = $records->filter(function ($record) use ($term) {
                $haystack = strtolower(implode(' ', [
                    $record['student_code'] ?? '',
                    $record['last_name'] ?? '',
                    $record['first_name'] ?? '',
                    $record['middle_name'] ?? '',
                    $record['course'] ?? '',
                    $record['year_section'] ?? '',
                    $record['contact_number'] ?? '',
                ]));

                return str_contains($haystack, $term);
            });
        }

        return $records->sortByDesc('saved_at')->values();
    }
}