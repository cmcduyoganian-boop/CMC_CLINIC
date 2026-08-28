<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ClinicVisitController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserManagementController;
use App\Http\Requests\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

// ============ PUBLIC LANDING PAGE ============
Route::get('/', function () {
    return view('landing');
})->name('landing');

// ============ AUTHENTICATION ROUTES ============
require __DIR__.'/auth.php';

// ============ OTP VERIFICATION ROUTES (FOR REGISTRATION) ============
Route::middleware('guest')->group(function () {
    // ✅ Show OTP verification page
    Route::get('/verify-email/{email}', [\App\Http\Controllers\OtpController::class, 'show'])
        ->name('otp.show');

    // ✅ Verify OTP and create account
    Route::post('/verify-email/{email}', [\App\Http\Controllers\OtpController::class, 'verify'])
        ->name('otp.verify');

    // ✅ Resend OTP
    Route::post('/resend-otp', [\App\Http\Controllers\OtpController::class, 'resend'])
        ->name('otp.resend');
});

// ============ EMAIL VERIFICATION ROUTES ============
Route::middleware(['auth', \App\Http\Middleware\CheckApprovalStatus::class])->group(function () {
    // ✅ Email verification notice (unverified users see this)
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // ✅ Email verification handler (user clicks link)
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect(route('dashboard'))->with('success', 'Email verified successfully!');
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    // ✅ Resend verification email
    Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('resent', true);
    })->middleware('throttle:6,1')->name('verification.send');
});

// ============ AUTHENTICATED ROUTES ============
Route::middleware(['auth', 'verified', \App\Http\Middleware\CheckApprovalStatus::class])->group(function () {
    // Dynamic dashboard by role
    Route::get('/dashboard', function () {
        $user = auth()->user();

        return match($user->role) {
            'student' => view('dashboard-patient'),
            'faculty' => view('dashboard-patient'),
            'staff' => view('dashboard-patient'),
            'clinic_nurse' => view('dashboard-nurse'),
            'clinic_staff' => view('dashboard-clinic-staff'),
            default => redirect('/login')->with('error', 'Invalid role'),
        };
    })->name('dashboard');

    // ✅ PATIENT ROUTES
    Route::resource('patients', PatientController::class)->middleware('clinic.role:clinic_nurse');

    // ✅ CLINIC VISIT ROUTES
    Route::resource('clinic-visit', ClinicVisitController::class)
        ->only(['index', 'create', 'store', 'show'])
        ->parameters(['clinic-visit' => 'id'])
        ->middleware('clinic.role');
    Route::middleware('clinic.role:clinic_nurse')->group(function () {
        Route::get('/clinic-visit/{id}/edit', [ClinicVisitController::class, 'edit'])->name('clinic-visit.edit');
        Route::put('/clinic-visit/{id}', [ClinicVisitController::class, 'update'])->name('clinic-visit.update');
        Route::patch('/clinic-visit/{id}', [ClinicVisitController::class, 'update']);
        Route::delete('/clinic-visit/{id}', [ClinicVisitController::class, 'destroy'])->name('clinic-visit.destroy');
    });
    Route::get('/api/patients/search', [ClinicVisitController::class, 'search'])
        ->middleware('clinic.role')
        ->name('patients.search');

    // ✅ MEDICINE ROUTES
    Route::get('/medicines', [MedicineController::class, 'index'])
        ->middleware('clinic.role')
        ->name('medicines.index');
    Route::get('/medicines/{id}/history', [MedicineController::class, 'history'])
        ->middleware('clinic.role')
        ->name('medicines.history');
    Route::middleware('clinic.role')->group(function () {
        Route::get('/medicines/create', [MedicineController::class, 'create'])->name('medicines.create');
        Route::post('/medicines', [MedicineController::class, 'store'])->name('medicines.store');
        Route::get('/medicines/{medicine}/edit', [MedicineController::class, 'edit'])->name('medicines.edit');
        Route::put('/medicines/{medicine}', [MedicineController::class, 'update'])->name('medicines.update');
        Route::patch('/medicines/{medicine}', [MedicineController::class, 'update']);
    });
    Route::delete('/medicines/{medicine}', [MedicineController::class, 'destroy'])
        ->middleware('clinic.role:clinic_nurse')
        ->name('medicines.destroy');
    Route::post('/medicines/{id}/use-stock', [MedicineController::class, 'useStock'])
        ->middleware('clinic.role')
        ->name('medicines.use-stock');
    Route::post('/medicines/{id}/add-stock', [MedicineController::class, 'addStock'])
        ->middleware('clinic.role')
        ->name('medicines.add-stock');

    // ✅ APPOINTMENT ROUTES
    Route::resource('appointments', AppointmentController::class)->except(['show'])->middleware('clinic.role:clinic_nurse');
    Route::get('/appointments/{id}', [AppointmentController::class, 'show'])->name('appointments.show');

    // ✅ REPORT ROUTES
    Route::middleware('clinic.role:clinic_nurse')->group(function () {
        Route::get('/reports', fn() => view('reports.index'))->name('reports.index');
        Route::get('/reports/patients', [ReportController::class, 'patients'])->name('reports.patients');
        Route::get('/reports/clinic-visits', [ReportController::class, 'clinicVisits'])->name('reports.clinic-visits');
        Route::get('/reports/diagnosis', [ReportController::class, 'diagnosis'])->name('reports.diagnosis');
        Route::get('/reports/medicines', [ReportController::class, 'medicines'])->name('reports.medicines');
        Route::get('/reports/appointments', [ReportController::class, 'appointments'])->name('reports.appointments');
        Route::get('/reports/vital-signs', [ReportController::class, 'vitalSigns'])->name('reports.vital-signs');
    });

    // ✅ SETTINGS ROUTES
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::post('/settings/avatar', [SettingsController::class, 'updateAvatar'])->name('settings.avatar.update');
    Route::delete('/settings/avatar', [SettingsController::class, 'deleteAvatar'])->name('settings.avatar.delete');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
    Route::post('/settings/password/request-otp', [SettingsController::class, 'requestPasswordOtp'])->name('settings.password.request-otp');
    Route::post('/settings/username', [SettingsController::class, 'updateUsername'])->name('settings.username.update');
    Route::post('/settings/clinic', [SettingsController::class, 'updateClinic'])->name('settings.clinic.update');

    // ✅ USER MANAGEMENT ROUTES
        Route::middleware('clinic.role:clinic_nurse')->group(function () {
            Route::resource('users', UserManagementController::class);
            Route::post('/users/{id}/approve', [UserManagementController::class, 'approve'])->name('users.approve');
            Route::post('/users/{id}/reject', [UserManagementController::class, 'reject'])->name('users.reject');
            Route::post('/users/{id}/disable', [UserManagementController::class, 'disable'])->name('users.disable');
            Route::post('/users/{id}/reset-password', [UserManagementController::class, 'resetPassword'])->name('users.reset-password');
            Route::get('/users/bulk/from-patients', function () {
                // Bulk create from patients
            })->name('users.create-from-patients');
            Route::post('/users/bulk/create', function () {
                // Bulk create users
            })->name('users.bulk-create');
        });

    // ✅ FORMS ROUTES
    Route::middleware('clinic.role:clinic_nurse')->group(function () {
        Route::get('/forms', fn() => view('forms.index'))->name('forms.index');
        Route::get('/forms/consent', fn() => view('forms.consent'))->name('forms.consent');
        Route::post('/forms/consent', function () {
            return back()->with('success', 'Consent form submitted successfully!');
        })->name('forms.consent.store');
        Route::get('/forms/student-info', fn() => view('forms.student-info'))->name('forms.student-info');
        Route::post('/forms/student-info', function () {
            return back()->with('success', 'Student information form submitted successfully!');
        })->name('forms.student-info.store');
    });

    // ✅ PROFILE ROUTES
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============ ADMIN ROUTES ============
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users');
    Route::get('/admin/users/counts', [\App\Http\Controllers\Admin\UserController::class, 'getCounts'])->name('users.counts');

    Route::post('/admin/users/{user}/approve', [\App\Http\Controllers\Admin\UserController::class, 'approve'])->name('users.approve');
    Route::post('/admin/users/{user}/reject', [\App\Http\Controllers\Admin\UserController::class, 'reject'])->name('users.reject');
    Route::post('/admin/users/{user}/disable', [\App\Http\Controllers\Admin\UserController::class, 'disable'])->name('users.disable');
    Route::post('/admin/users/{user}/reactivate', [\App\Http\Controllers\Admin\UserController::class, 'reactivate'])->name('users.reactivate');
});

// ============ CLINIC STAFF REGISTRATION ROUTE ============
Route::middleware('guest')->group(function () {
    Route::get('register/clinic-staff', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'createClinicStaff'])->name('register.clinic-staff');
    Route::post('register/clinic-staff', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'storeClinicStaff'])->name('register.clinic-staff.store');
});