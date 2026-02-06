<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\DarController;
use App\Http\Controllers\Web\AttendanceController;
use App\Http\Controllers\Web\LeaveController;
use App\Http\Controllers\Web\ClaimController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\LetterController;
use App\Http\Controllers\Web\LetterTemplateController;
use App\Http\Controllers\Web\HolidayController;
use App\Http\Controllers\Web\EmployeeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Language Switcher
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['id', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Redirect root to dashboard if logged in (for those hitting / directly)
    Route::get('/', function () {
        return redirect()->route('dashboard');
    })->name('home');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // DAR Management
    Route::resource('dars', DarController::class);
    
    // DAR Approvals
    Route::get('/dars-approvals', [DarController::class, 'approvals'])->name('dars.approvals');
    Route::post('/dars/{dar}/approve', [DarController::class, 'approve'])->name('dars.approve');
    Route::post('/dars/{dar}/reject', [DarController::class, 'reject'])->name('dars.reject');

    // Attendance
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/staffabsenluarkotainbeta', [AttendanceController::class, 'create'])->name('attendance.create'); // Legacy route
    Route::get('/attendance/checkout-view', [AttendanceController::class, 'checkoutView'])->name('attendance.checkout-view'); // New Check-out View
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check-in');
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.check-out');
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');

    // Leaves
    Route::resource('leaves', LeaveController::class);
    Route::get('/leaves-approvals', [LeaveController::class, 'approvals'])->name('leaves.approvals')->middleware('permission:can_approve');
    Route::post('/leaves/{leave}/process', [LeaveController::class, 'processApproval'])->name('leaves.process')->middleware('permission:can_approve');
    // Claims
    Route::resource('claims', ClaimController::class);
    Route::get('/claims-approvals', [ClaimController::class, 'approvals'])->name('claims.approvals');
    Route::post('/claims/{claim}/process', [ClaimController::class, 'processApproval'])->name('claims.process');

    // Reports
    Route::group(['prefix' => 'reports', 'as' => 'reports.', 'middleware' => ['permission:can_approve']], function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/employee-summary', [ReportController::class, 'employeeSummary'])->name('employee-summary');
        Route::get('/gap-analysis', [ReportController::class, 'gapAnalysis'])->name('gap-analysis');
        Route::get('/out-of-town', [ReportController::class, 'outOfTown'])->name('out-of-town');
        Route::get('/late-permission', [ReportController::class, 'latePermission'])->name('late-permission');
        Route::get('/leave-report', [ReportController::class, 'leaveReport'])->name('leave-report');
    });

    // Late Permissions
    Route::resource('late-permissions', App\Http\Controllers\Web\LatePermissionController::class);
    
    // Forgot Clocks
    Route::resource('forgot-clocks', App\Http\Controllers\Web\ForgotClockController::class);
    
    // Office Exit Permits
    Route::resource('office-exit-permits', App\Http\Controllers\Web\OfficeExitPermitController::class);
    
    // Overtimes
    Route::resource('overtimes', App\Http\Controllers\Web\OvertimeController::class);

    // Letter Management
    Route::resource('letters', LetterController::class);
    Route::post('/letters/{letter}/approve', [LetterController::class, 'approve'])->name('letters.approve');
    Route::post('/letters/{letter}/reject', [LetterController::class, 'reject'])->name('letters.reject');
    
    // Letter Templates
    Route::resource('letter-templates', LetterTemplateController::class)->only(['index', 'edit', 'update']);

    // Holiday Management
    Route::resource('holidays', HolidayController::class);

    // Placeholder Routes for Legacy Features (to be implemented)
    Route::get('/medical-plafons', function() { return redirect()->route('dashboard')->with('error', 'Feature "Master Plafon" is coming soon!'); })->name('medical-plafons.index');
    Route::get('/medical-plafons/monitoring', function() { return redirect()->route('dashboard')->with('error', 'Feature "Monitoring Sisa Plafon" is coming soon!'); })->name('medical-plafons.monitoring');
    Route::get('/claim-users', function() { return redirect()->route('dashboard')->with('error', 'Feature "Master User Klaim" is coming soon!'); })->name('claim-users.index');
    Route::get('/claim-groups', function() { return redirect()->route('dashboard')->with('error', 'Feature "Master Group Klaim" is coming soon!'); })->name('claim-groups.index');
    Route::get('/resignations/settings', function() { return redirect()->route('dashboard')->with('error', 'Feature "Pengaturan Resign" is coming soon!'); })->name('resignations.settings');
    Route::get('/distributors', function() { return redirect()->route('dashboard')->with('error', 'Feature "Pengaturan Distributor" is coming soon!'); })->name('distributors.index');
    Route::get('/system/reset', function() { return redirect()->route('dashboard')->with('error', 'Feature "Reset" is coming soon!'); })->name('system.reset');

    // Administration
    Route::resource('employees', EmployeeController::class)->middleware('permission:can_manage_users');
    Route::post('/employees/{employee}/impersonate', [EmployeeController::class, 'impersonate'])
        ->name('employees.impersonate')
        ->middleware('permission:can_manage_users');
    Route::post('/impersonate/leave', [EmployeeController::class, 'stopImpersonate'])->name('impersonate.leave');
    
    // Menu Permissions
    Route::get('/settings/menus', [App\Http\Controllers\Web\MenuPermissionController::class, 'index'])->name('settings.menus.index');
    Route::post('/settings/menus', [App\Http\Controllers\Web\MenuPermissionController::class, 'update'])->name('settings.menus.update');

    // Master Data (CRUD)
    Route::group(['prefix' => 'master-data', 'as' => 'master.', 'middleware' => ['permission:can_manage_users']], function () {
        Route::resource('roles', App\Http\Controllers\Web\RoleController::class);
        Route::resource('departments', App\Http\Controllers\Web\DepartmentController::class);
        Route::resource('access-areas', App\Http\Controllers\Web\AccessAreaController::class);
        Route::resource('business-units', App\Http\Controllers\Web\BusinessUnitController::class);
        Route::resource('locations', App\Http\Controllers\Web\LocationController::class);
    });

    // System Logs
    Route::get('/activity-logs', [App\Http\Controllers\Web\ActivityLogController::class, 'index'])
         ->name('activity-logs.index')
         ->middleware('permission:can_manage_users');
});
