<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Show all users for management
     */
    public function index()
    {
        // ✅ GET ALL USERS - INCLUDING PENDING!
        $allUsers = User::orderBy('created_at', 'desc')->get();
        
        // ✅ CALCULATE COUNTS - INCLUDING PENDING
        $totalUsers = User::count();
        $pendingCount = User::where('approval_status', 'pending')->count();
        $approvedCount = User::where('approval_status', 'approved')->count();
        $disabledCount = User::where('approval_status', 'disabled')->count();

        // ✅ COUNT BY ROLE
        $studentCount = User::where('role', 'student')->count();
        $facultyCount = User::where('role', 'faculty')->count();
        $staffCount = User::where('role', 'staff')->count();
        $clinicNurseCount = User::where('role', 'clinic_nurse')->count();
        $clinicStaffCount = User::where('role', 'clinic_staff')->count();

        // ✅ PASS ALL USERS TO VIEW - LET JAVASCRIPT/BLADE FILTER THEM
        $displayUsers = $allUsers;

        Log::info('User Management accessed', [
            'total' => $totalUsers,
            'pending' => $pendingCount,
            'approved' => $approvedCount,
            'disabled' => $disabledCount,
        ]);

        return view('admin.users.index', compact(
            'displayUsers',
            'totalUsers',
            'pendingCount',
            'approvedCount',
            'disabledCount',
            'studentCount',
            'facultyCount',
            'staffCount',
            'clinicNurseCount',
            'clinicStaffCount'
        ));
    }

    /**
     * Get user counts for AJAX updates
     */
    public function getCounts()
    {
        Log::info('Fetching user counts for AJAX');

        return response()->json([
            'total' => User::count(),
            'pending' => User::where('approval_status', 'pending')->count(),
            'approved' => User::where('approval_status', 'approved')->count(),
            'disabled' => User::where('approval_status', 'disabled')->count(),
        ]);
    }

    /**
     * Show user details
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Approve a user account
     */
    public function approve($id)
    {
        try {
            $user = User::findOrFail($id);
            
            Log::info('🟢 Admin approving user', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            if ($user->id === 1) {
                Log::warning('Attempted to modify admin account', ['user_id' => $user->id]);
                
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Cannot modify admin account.'
                    ], 403);
                }
                return back()->with('error', 'Cannot modify admin account.');
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

            // ✅ RETURN JSON FOR AJAX
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => '✅ ' . $user->name . ' has been approved!',
                    'user_id' => $user->id,
                    'status' => 'approved',
                ]);
            }

            // Return regular response for form submission
            return back()->with('success', '✅ ' . $user->name . ' has been approved!');

        } catch (\Exception $e) {
            Log::error('Error approving user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error approving user: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error approving user');
        }
    }

    /**
     * Reject a user account
     */
    public function reject($id)
    {
        try {
            $user = User::findOrFail($id);
            
            Log::info('🔴 Admin rejecting user', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            if ($user->id === 1) {
                Log::warning('Attempted to modify admin account', ['user_id' => $user->id]);
                
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot modify admin account.'
                    ], 403);
                }
                return back()->with('error', 'Cannot modify admin account.');
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

            // ✅ RETURN JSON FOR AJAX
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => '✅ ' . $user->name . ' has been rejected!',
                    'user_id' => $user->id,
                    'status' => 'rejected',
                ]);
            }

            // Return regular response for form submission
            return back()->with('success', '✅ ' . $user->name . ' has been rejected!');

        } catch (\Exception $e) {
            Log::error('Error rejecting user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error rejecting user: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error rejecting user');
        }
    }

    /**
     * Disable a user account
     */
    public function disable($id)
    {
        try {
            $user = User::findOrFail($id);
            
            Log::info('🚫 Admin disabling user', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            if ($user->id === 1) {
                Log::warning('Attempted to modify admin account', ['user_id' => $user->id]);
                
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot modify admin account.'
                    ], 403);
                }
                return back()->with('error', 'Cannot modify admin account.');
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

            // ✅ RETURN JSON FOR AJAX
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => '✅ ' . $user->name . ' has been disabled!',
                    'user_id' => $user->id,
                    'status' => 'disabled',
                ]);
            }

            // Return regular response for form submission
            return back()->with('success', '✅ ' . $user->name . ' has been disabled!');

        } catch (\Exception $e) {
            Log::error('Error disabling user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error disabling user: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error disabling user');
        }
    }

    /**
     * Reactivate a user account
     */
    public function reactivate($id)
    {
        try {
            $user = User::findOrFail($id);
            
            Log::info('🟢 Admin reactivating user', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            if ($user->id === 1) {
                Log::warning('Attempted to modify admin account', ['user_id' => $user->id]);
                
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot modify admin account.'
                    ], 403);
                }
                return back()->with('error', 'Cannot modify admin account.');
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

            // ✅ RETURN JSON FOR AJAX
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => '✅ ' . $user->name . ' has been reactivated!',
                    'user_id' => $user->id,
                    'status' => 'approved',
                ]);
            }

            // Return regular response for form submission
            return back()->with('success', '✅ ' . $user->name . ' has been reactivated!');

        } catch (\Exception $e) {
            Log::error('Error reactivating user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error reactivating user: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error reactivating user');
        }
    }

    /**
     * Delete a user
     */
    public function destroy(User $user)
    {
        Log::info('🗑️ Deleting user', ['user_id' => $user->id]);

        if ($user->id === 1) {
            return back()->with('error', 'Cannot delete admin account.');
        }

        $userName = $user->name;
        $user->delete();

        Log::info('✅ User deleted', ['name' => $userName]);

        return back()->with('success', '✅ ' . $userName . ' has been deleted!');
    }
}