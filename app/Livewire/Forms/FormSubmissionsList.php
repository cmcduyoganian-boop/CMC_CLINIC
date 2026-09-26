<?php

namespace App\Livewire\Forms;

use App\Models\FormSubmission;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class FormSubmissionsList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterType = '';

    protected $listeners = ['refreshList' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function deleteSubmission($id)
    {
        FormSubmission::where('id', $id)->where('user_id', auth()->id())->delete();
        session()->flash('success', 'Form submission deleted.');
    }

    public function render()
    {
        $records = $this->getSubmissions();
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

        return view('livewire.forms.form-submissions-list', [
            'records' => $records,
            'types' => [
                'student_medical_history' => 'Student Health Record',
                'clinic_visit_log' => 'Clinic Visit Log',
                'client_research_consent' => 'Client Consent Form',
                'research_data_consent' => 'Research Data Consent',
            ],
        ]);
    }

    protected function getSubmissions(): Collection
    {
        $query = FormSubmission::query()
            ->where('user_id', auth()->id())
            ->latest('submitted_at');

        if ($this->filterType) {
            $query->where('form_type', $this->filterType);
        }

        $submissions = $query->get()->map(function ($submission) {
            $data = $submission->data ?? [];
            $type = $submission->form_type;

            $title = match ($type) {
                'student_medical_history' => trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '')) ?: 'Student Health Record',
                'clinic_visit_log' => 'Visit: ' . ($data['visit_date'] ?? 'N/A'),
                'client_research_consent' => 'Consent: ' . ($data['full_name'] ?? 'N/A'),
                'research_data_consent' => 'Research: ' . ($data['personnel_name'] ?? 'N/A'),
                default => 'Form Submission',
            };

            $subtitle = match ($type) {
                'student_medical_history' => $data['course'] ?? $data['student_code'] ?? '',
                'clinic_visit_log' => $data['complaints'] ? Str::limit($data['complaints'], 50) : 'No complaints recorded',
                'client_research_consent' => 'Client consent form',
                'research_data_consent' => 'Research data consent form',
                default => '',
            };

            return [
                'id' => $submission->id,
                'form_type' => $type,
                'type_label' => $this->getTypeLabel($type),
                'title' => $title,
                'subtitle' => $subtitle,
                'saved_at' => $submission->submitted_at ?? $submission->updated_at,
            ];
        });

        if ($this->search !== '') {
            $term = strtolower(trim($this->search));
            $submissions = $submissions->filter(function ($record) use ($term) {
                $haystack = strtolower(implode(' ', [
                    $record['title'] ?? '',
                    $record['subtitle'] ?? '',
                    $record['type_label'] ?? '',
                ]));
                return str_contains($haystack, $term);
            });
        }

        return $submissions->sortByDesc('saved_at')->values();
    }

    protected function getTypeLabel(string $type): string
    {
        return match ($type) {
            'student_medical_history' => 'Student Health Record',
            'clinic_visit_log' => 'Clinic Visit Log',
            'client_research_consent' => 'Client Consent Form',
            'research_data_consent' => 'Research Data Consent',
            default => 'Unknown',
        };
    }

    protected function getFormRoute(string $type): string
    {
        return match ($type) {
            'student_medical_history' => 'student-info',
            'clinic_visit_log' => 'clinic-visit',
            'client_research_consent' => 'consent',
            'research_data_consent' => 'research-consent',
            default => 'index',
        };
    }
}