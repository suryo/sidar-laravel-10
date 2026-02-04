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
    Route::get('/dars-approvals', [DarController::class, 'approvals'])->name('dars.approvals')->middleware('permission:can_approve');
    Route::post('/dars/{dar}/approve', [DarController::class, 'approve'])->name('dars.approve')->middleware('permission:can_approve');
    Route::post('/dars/{dar}/reject', [DarController::class, 'reject'])->name('dars.reject')->middleware('permission:can_approve');

    // Attendance
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/staffabsenluarkotainbeta', [AttendanceController::class, 'create'])->name('attendance.create'); // Legacy route
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
    });

    // Letter Management
    Route::resource('letters', LetterController::class);
    Route::post('/letters/{letter}/approve', [LetterController::class, 'approve'])->name('letters.approve');
    Route::post('/letters/{letter}/reject', [LetterController::class, 'reject'])->name('letters.reject');
    
    // Letter Templates
    Route::resource('letter-templates', LetterTemplateController::class)->only(['index', 'edit', 'update']);

    // Holiday Management
    Route::resource('holidays', HolidayController::class)->only(['index', 'store', 'destroy']);

    // Administration
    Route::resource('employees', EmployeeController::class)->middleware('permission:can_manage_users');
});
