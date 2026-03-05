<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Employee\SalaryController;
use App\Http\Controllers\Employee\AttendanceController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\ManageLeavesController;
use App\Http\Controllers\Employee\LeaveController;
use App\Http\Controllers\HR\AttendanceController as HRAttendanceController;
use App\Models\User;
use App\Models\Leave;

// Middleware
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\EmployeeMiddleware;
use App\Http\Middleware\HRMiddleware;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => Inertia::render('Auth/Login'))->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', AdminMiddleware::class])
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/api/dashboard/stats', [AdminDashboardController::class, 'getStats'])->name('dashboard.stats');

        Route::get('/departments', [DepartmentController::class, 'index'])->name('departments');
        Route::inertia('/adddepartment', 'Admin/Departments/AddDepartment')->name('adddepartment');
        Route::post('/adddepartment', [DepartmentController::class, 'store']);
        Route::get('/editdepartment/{id}', [DepartmentController::class, 'edit'])->name('editdepartment');
        Route::put('/editdepartment/{id}', [DepartmentController::class, 'update']);
        Route::delete('/departments/{id}', [DepartmentController::class, 'destroy']);

        Route::get('/manageemployees', [EmployeeController::class, 'index'])->name('manageemployees');
        Route::get('/addnewemployee', [EmployeeController::class, 'create'])->name('addnewemployee');
        Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::get('/view/{id}', [EmployeeController::class, 'show'])->name('view');
        Route::get('/edit/{id}', [EmployeeController::class, 'edit'])->name('edit');
        Route::put('/edit/{id}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('/delete/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');

        Route::inertia('/settings', 'Admin/Settings/AdminSettings')->name('settings');
        Route::post('/reset-password', [AuthController::class, 'changePassword'])->name('password.reset');

        Route::get('/manageleaves/leaves', [ManageLeavesController::class, 'index'])->name('manageleaves.index');
        Route::get('/manageleaves/leaves/review/{leave}', [ManageLeavesController::class, 'review'])->name('manageleaves.review');
        Route::post('/manageleaves/leaves/{leave}/approve', [ManageLeavesController::class, 'approve'])->name('manageleaves.approve');
        Route::post('/manageleaves/leaves/{leave}/reject', [ManageLeavesController::class, 'reject'])->name('manageleaves.reject');
    });

/*
|--------------------------------------------------------------------------
| Employee Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', EmployeeMiddleware::class])
    ->prefix('employee')
    ->name('employee.')
    ->group(function () {

        Route::get('/dashboard', function () {
            $user = Auth::user();

            $attendanceHistory = DB::table('attendances')
                ->where('employee_id', $user->id)
                ->orderBy('date', 'desc')
                ->limit(5)
                ->get();

            $presentDays = DB::table('attendances')
                ->where('employee_id', $user->id)
                ->whereMonth('date', now()->month)
                ->where('status', 'present')
                ->count();

            $pendingLeaves = DB::table('leaves')
                ->where('employee_id', $user->id)
                ->where('status', 'pending')
                ->count();

            $payroll = DB::table('payrolls')
                ->where('employee_id', $user->id)
                ->where('month', now()->format('F Y'))
                ->first();

            $estimatedSalary = $payroll
                ? floatval($payroll->net_salary)
                : floatval($user->salary ?? 0);

            return Inertia::render('Employee/Dashboard', [
                'attendanceHistory' => $attendanceHistory,
                'presentDays'       => $presentDays,
                'pendingLeaves'     => $pendingLeaves,
                'estimatedSalary'   => $estimatedSalary,
            ]);
        })->name('dashboard');

        Route::inertia('/index', 'Employee/Index')->name('index');

        Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves');
        Route::get('/leaves/create', [LeaveController::class, 'create'])->name('create-leave');
        Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
        Route::get('/leaves/{leave}', [LeaveController::class, 'show'])->name('view-leave');

        Route::get('/salary', [SalaryController::class, 'index'])->name('salary');

        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
        Route::post('/attendance/record', [AttendanceController::class, 'record'])->name('attendance.record');

        Route::inertia('/settings', 'Employee/Settings/Index')->name('settings');
        Route::inertia('/about', 'Employee/Settings/About')->name('about');
        Route::inertia('/support', 'Employee/Settings/CustomerSupport')->name('customer-support');
        Route::inertia('/change-password', 'Employee/Settings/ChangePassword')->name('change-password');
        Route::post('/reset-password', [AuthController::class, 'changePassword'])->name('password.reset');
    });

/*
|--------------------------------------------------------------------------
| HR Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', HRMiddleware::class])
    ->prefix('hr')
    ->name('hr.')
    ->group(function () {

        // ✅ HR Dashboard — passes stats as Inertia props
        Route::get('/dashboard', function () {
            $today = Carbon::today('Asia/Manila');

            $presentToday = DB::table('attendances')
                ->whereDate('date', $today)
                ->whereNotNull('check_in')
                ->whereIn('status', ['present', 'late'])
                ->count();

            $totalEmployees = User::where('role', 'employee')->count();
            $absentToday    = max(0, $totalEmployees - $presentToday);

            return Inertia::render('HR/Dashboard', [
                'stats' => [
                    'presentToday'   => $presentToday,
                    'absentToday'    => $absentToday,
                    'totalEmployees' => $totalEmployees,
                    'leavePending'   => Leave::where('status', 'pending')->count(),
                    'leaveApproved'  => Leave::where('status', 'approved')->count(),
                    'leaveRejected'  => Leave::where('status', 'rejected')->count(),
                ]
            ]);
        })->name('dashboard');

        Route::get('/leaves', [ManageLeavesController::class, 'index'])->name('leaves.index');
        Route::get('/leaves/review/{leave}', [ManageLeavesController::class, 'review'])->name('leaves.review');
        Route::post('/leaves/{leave}/approve', [ManageLeavesController::class, 'approve'])->name('leaves.approve');
        Route::post('/leaves/{leave}/reject', [ManageLeavesController::class, 'reject'])->name('leaves.reject');

        Route::get('/attendance/employees', [HRAttendanceController::class, 'employees'])->name('attendance.employees');
        Route::get('/attendance', [HRAttendanceController::class, 'index'])->name('attendance');
        Route::get('/attendance/{id}/edit', [HRAttendanceController::class, 'edit'])->name('attendance.edit');
        Route::put('/attendance/{id}', [HRAttendanceController::class, 'update'])->name('attendance.update');
        Route::delete('/attendance/{id}', [HRAttendanceController::class, 'destroy'])->name('attendance.destroy');

        Route::get('/salaries', [App\Http\Controllers\HR\SalaryController::class, 'index'])->name('salaries.index');
        Route::get('/salaries/{employeeId}', [App\Http\Controllers\HR\SalaryController::class, 'view'])->name('salaries.view');
        Route::post('/salaries/{employeeId}/generate-payroll', [App\Http\Controllers\HR\SalaryController::class, 'generatePayroll'])->name('salaries.generatePayroll');

        Route::inertia('/settings', 'HR/Settings/Index')->name('settings');
        Route::inertia('/change-password', 'HR/Settings/ChangePassword')->name('change-password');
        Route::post('/reset-password', [AuthController::class, 'changePassword'])->name('password.reset');
        Route::inertia('/about', 'HR/Settings/About')->name('about');
        Route::inertia('/support', 'HR/Settings/CustomerSupport')->name('customer-support');
    });