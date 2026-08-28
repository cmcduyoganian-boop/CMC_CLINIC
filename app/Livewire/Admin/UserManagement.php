<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\PendingRegistration;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class UserManagement extends Component
{
    use WithPagination;

    // ============ FILTERS ============
    public $search = '';
    public $filterRole = '';
    public $filterStatus = '';

    // ============ FLASH MESSAGE ============
    public $flashMessage = '';
    public $flashType = 'success';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterRole()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function dismissFlash()
    {
        $this->flashMessage = '';
    }

    // ============ APPROVE ============
    public function approveUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === 1) {
            Log::warning('Attempted to modify admin account', ['user_id' => $user->id]);
            $this->flashType = 'danger';
            $this->flashMessage = 'Cannot modify admin account.';
            return;
        }

        $user->update([
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        Log::info('✅ User approved successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'approval_status' => 'approved',
        ]);

        $this->flashType = 'success';
        $this->flashMessage = '✅ ' . $user->name . ' has been approved!';
    }

    // ============ REJECT ============
    public function rejectUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === 1) {
            Log::warning('Attempted to modify admin account', ['user_id' => $user->id]);
            $this->flashType = 'danger';
            $this->flashMessage = 'Cannot modify admin account.';
            return;
        }

        $user->update([
            'approval_status' => 'rejected',
            'is_active' => false,
        ]);

        Log::info('✅ User rejected successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'approval_status' => 'rejected',
        ]);

        $this->flashType = 'success';
        $this->flashMessage = '✅ ' . $user->name . ' has been rejected!';
    }

    // ============ DISABLE ============
    public function disableUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === 1) {
            Log::warning('Attempted to modify admin account', ['user_id' => $user->id]);
            $this->flashType = 'danger';
            $this->flashMessage = 'Cannot modify admin account.';
            return;
        }

        $user->update([
            'approval_status' => 'disabled',
            'is_active' => false,
        ]);

        Log::info('✅ User disabled successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'approval_status' => 'disabled',
        ]);

        $this->flashType = 'success';
        $this->flashMessage = '✅ ' . $user->name . ' has been disabled!';
    }

    // ============ REACTIVATE ============
    public function reactivateUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === 1) {
            Log::warning('Attempted to modify admin account', ['user_id' => $user->id]);
            $this->flashType = 'danger';
            $this->flashMessage = 'Cannot modify admin account.';
            return;
        }

        $user->update([
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        Log::info('✅ User reactivated successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'approval_status' => 'approved',
        ]);

        $this->flashType = 'success';
        $this->flashMessage = '✅ ' . $user->name . ' has been reactivated!';
    }

    public function denyPendingRegistration($id)
    {
        $pending = PendingRegistration::findOrFail($id);
        $name = $pending->name;
        $pending->delete();

        $this->flashType = 'success';
        $this->flashMessage = '✅ Registration for ' . $name . ' has been denied.';
    }

    public function render()
    {
        $query = User::query();

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($this->filterRole) {
            $query->where('role', $this->filterRole);
        }

        if ($this->filterStatus) {
            $query->where('approval_status', $this->filterStatus);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        $pendingQuery = PendingRegistration::query()
            ->when($this->search, function ($pendingQuery) {
                $pendingQuery->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('username', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterRole, fn ($pendingQuery) => $pendingQuery->where('role', $this->filterRole));

        $pendingRegistrations = !$this->filterStatus || $this->filterStatus === 'pending'
            ? $pendingQuery->latest()->get()
            : collect();

        return view('livewire.admin.user-management', [
            'users' => $users,
            'pendingRegistrations' => $pendingRegistrations,
            'totalUsers' => User::count(),
            'pendingCount' => User::where('approval_status', 'pending')->count() + PendingRegistration::count(),
            'approvedCount' => User::where('approval_status', 'approved')->count(),
            'disabledCount' => User::where('approval_status', 'disabled')->count(),
            'studentCount' => User::where('role', 'student')->count(),
            'facultyCount' => User::where('role', 'faculty')->count(),
            'staffCount' => User::where('role', 'staff')->count(),
            'clinicNurseCount' => User::where('role', 'clinic_nurse')->count(),
            'clinicStaffCount' => User::where('role', 'clinic_staff')->count(),
        ]);
    }
}