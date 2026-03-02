<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\tenant\users\UserDashboardController;
use App\Http\Controllers\tenant\users\UserPayrollController;

Route::middleware([
  'web',
  'auth',
  'role:hr|manager|field_employee'
])->prefix('user')->name('user.')->group(function () {
  Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard.index');

  // Leaves
  Route::prefix('leaves')->name('leaves.')->group(function () {
    Route::get('/', [UserDashboardController::class, 'leaveIndex'])->name('index');
    Route::post('/store', [UserDashboardController::class, 'leaveStore'])->name('store');
  });

  // Expenses
  Route::prefix('expenses')->name('expenses.')->group(function () {
    Route::get('/', [UserDashboardController::class, 'expenseIndex'])->name('index');
    Route::post('/store', [UserDashboardController::class, 'expenseStore'])->name('store');
  });

  // Attendance
  Route::get('/attendance', [UserDashboardController::class, 'attendanceIndex'])->name('attendance.index');

  // SOS
  Route::get('/sos', [UserDashboardController::class, 'sosIndex'])->name('sos.index');

  // Visits
  Route::get('/visits', [UserDashboardController::class, 'visitIndex'])->name('visits.index');

  // Payroll
  Route::prefix('payroll')->name('payroll.')->group(function () {
      Route::get('/', [UserPayrollController::class, 'index'])->name('index');
      Route::get('/{id}/download', [UserPayrollController::class, 'download'])->name('download');
  });
});
