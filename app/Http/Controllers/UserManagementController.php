<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Utilities\PasswordGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    // ============ INDEX - LIST ALL USERS ============
    public function index(Request $request)
    {
        return view('users.index');
    }

    // ============ CREATE - SHOW FORM ============
    public function create()
    {
        return view('users.create');
    }

    // ============ STORE - SAVE NEW USER ============
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'role' => 'required|in:student,faculty,staff,clinic_nurse,clinic_staff',
            'year_section' => 'nullable|string|max:50',
            'auto_generate_password' => 'nullable',
            'password' => 'nullable|string|min:6|max:8',
        ]);

        try {
            $autoGenerate = $request->has('auto_generate_password');

            if ($autoGenerate) {
                $password = PasswordGenerator::generate();
                $showPassword = true;
            } else {
                if (!$request->password) {
                    return back()->withInput()->with('error', 'Please enter a password or select auto-generate.');
                }
                if (!PasswordGenerator::validate($request->password)) {
                    return back()->withInput()->with('error', 'Password must be 6-8 characters with uppercase, lowercase, number, and special character.');
                }
                $password = $request->password;
                $showPassword = false;
            }

            // ✅ CREATE USER WITH EMAIL VERIFIED (ADMIN-CREATED)
            $user = User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'role' => $validated['role'],
                'approval_status' => 'approved',
                'is_active' => true,
                'password' => Hash::make($password),
                'email_verified_at' => now(), // ✅ PRE-VERIFIED BY ADMIN
                'otp_verified' => true, // ✅ PRE-VERIFIED BY ADMIN
                'must_change_password' => false,
            ]);

            // Create patient record if student
            if ($validated['role'] === 'student') {
                Patient::firstOrCreate(
                    ['email' => $user->email],
                    [
                        'name' => $user->name,
                        'phone' => $user->phone,
                        'year_section' => $validated['year_section'] ?? null,
                        'category' => 'student',
                        'status' => 'active',
                    ]
                );
            }

            // ✅ SHOW PASSWORD TO ADMIN
            return redirect()->route('users.create')->with([
                'success' => "User '{$user->name}' created successfully!",
                'show_password' => $showPassword,
                'default_username' => $user->username,
                'default_password' => $password,
                'default_email' => $user->email,
                'default_name' => $user->name,
                'default_role' => $user->getRoleLabel(),
                'email_verification' => 'Pre-verified by Admin',
            ]);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    // ============ SHOW - VIEW USER DETAILS ============
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    // ============ EDIT - SHOW EDIT FORM ============
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // ============ UPDATE - SAVE CHANGES ============
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:20',
            'approval_status' => 'required|in:pending,approved,disabled,rejected',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'approval_status' => $validated['approval_status'],
            'is_active' => $validated['approval_status'] === 'approved',
        ]);

        return redirect()->route('users.show', $user->id)->with('success', "User '{$user->name}' updated successfully!");
    }

    // ============ APPROVE - APPROVE PENDING USER ============
    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->update(['approval_status' => 'approved', 'is_active' => true]);

        // ✅ VERIFY EMAIL WHEN APPROVING CLINIC STAFF
        if ($user->role === 'clinic_staff' && !$user->hasVerifiedEmail()) {
            $user->update(['email_verified_at' => now()]);
        }

        return back()->with('success', "User '{$user->username}' has been approved!");
    }

    // ============ REJECT - REJECT PENDING USER ============
    public function reject($id)
    {
        $user = User::findOrFail($id);
        $user->update(['approval_status' => 'rejected', 'is_active' => false]);

        return back()->with('success', "User '{$user->username}' has been rejected!");
    }

    // ============ DISABLE - DISABLE USER ACCOUNT ============
    public function disable($id)
    {
        if ($id == auth()->id()) {
            return back()->with('error', 'You cannot disable your own account!');
        }

        $user = User::findOrFail($id);
        $user->update(['approval_status' => 'disabled', 'is_active' => false]);

        return back()->with('success', "User '{$user->username}' has been disabled!");
    }

    // ============ RESET PASSWORD ============
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $newPassword = PasswordGenerator::generate();

        $user->update([
            'password' => Hash::make($newPassword),
            'must_change_password' => true, // ✅ MUST CHANGE AGAIN
        ]);

        return back()->with([
            'success' => "Password reset for '{$user->username}'",
            'show_password' => true,
            'default_username' => $user->username,
            'default_password' => $newPassword,
            'default_email' => $user->email,
        ]);
    }

    // ============ DESTROY - DELETE USER ============
    public function destroy($id)
    {
        if ($id == auth()->id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        $user = User::findOrFail($id);
        $username = $user->username;
        $user->delete();

        return redirect()->route('users.index')->with('success', "User '$username' has been deleted!");
    }
}