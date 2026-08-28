<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Models\PendingRegistration;
use App\Utilities\PasswordGenerator;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagementList extends Component
{
    use WithPagination;

    public $search = '';

    public $statusFilter = '';

    public $flashMessage = '';

    public $flashType = 'success';

    // ============ RESET PASSWORD RESULT DISPLAY ============
    public $showPasswordResult = false;

    public $resultUsername = '';

    public $resultPassword = '';

    public $resultEmail = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function dismissFlash()
    {
        $this->flashMessage = '';
        $this->showPasswordResult = false;
    }

    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['approval_status' => 'approved', 'is_active' => true]);

        if ($user->role === 'clinic_staff' && ! $user->hasVerifiedEmail()) {
            $user->update(['email_verified_at' => now()]);
        }

        $this->flashType = 'success';
        $this->flashMessage = "User '{$user->username}' has been approved!";
    }

    public function rejectUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['approval_status' => 'rejected', 'is_active' => false]);

        $this->flashType = 'success';
        $this->flashMessage = "User '{$user->username}' has been rejected!";
    }

    public function denyPendingRegistration($id)
    {
        $pending = PendingRegistration::findOrFail($id);
        $name = $pending->name;
        $pending->delete();

        $this->flashType = 'success';
        $this->flashMessage = "Registration for '{$name}' has been denied!";
    }

    public function disableUser($id)
    {
        if ($id == auth()->id()) {
            $this->flashType = 'danger';
            $this->flashMessage = 'You cannot disable your own account!';

            return;
        }

        $user = User::findOrFail($id);
        $user->update(['approval_status' => 'disabled', 'is_active' => false]);

        $this->flashType = 'success';
        $this->flashMessage = "User '{$user->username}' has been disabled!";
    }

    public function resetPasswordFor($id)
    {
        $user = User::findOrFail($id);
        $newPassword = PasswordGenerator::generate();

        $user->update([
            'password' => Hash::make($newPassword),
            'must_change_password' => true,
        ]);

        $this->showPasswordResult = true;
        $this->resultUsername = $user->username;
        $this->resultPassword = $newPassword;
        $this->resultEmail = $user->email;

        $this->flashType = 'success';
        $this->flashMessage = "Password reset for '{$user->username}'";
    }

    public function deleteUser($id)
    {
        if ($id == auth()->id()) {
            $this->flashType = 'danger';
            $this->flashMessage = 'You cannot delete your own account!';

            return;
        }

        $user = User::findOrFail($id);
        $username = $user->username;
        $user->delete();

        $this->flashType = 'success';
        $this->flashMessage = "User '{$username}' has been deleted!";
        $this->resetPage();
    }

    public function render()
    {
        $query = User::query();

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($this->statusFilter) {
            $query->where('approval_status', $this->statusFilter);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        $pendingRegistrations = PendingRegistration::query()
            ->when($this->search, function ($pendingQuery) {
                $pendingQuery->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('username', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->get();

        return view('livewire.users.user-management-list', [
            'users' => $users,
            'pendingRegistrations' => $pendingRegistrations,
            'stats' => [
                'total' => User::count() + PendingRegistration::count(),
                'pending' => User::where('approval_status', 'pending')->count() + PendingRegistration::count(),
                'approved' => User::where('approval_status', 'approved')->count(),
                'disabled' => User::where('approval_status', 'disabled')->count(),
                'students' => User::where('role', 'student')->count(),
                'faculty' => User::where('role', 'faculty')->count(),
                'staff' => User::where('role', 'staff')->count(),
                'clinic_nurse' => User::where('role', 'clinic_nurse')->count(),
                'clinic_staff' => User::where('role', 'clinic_staff')->count(),
            ],
        ]);
    }
}
