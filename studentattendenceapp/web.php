<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public welcome page
Route::get('/', function () {
    return view('welcome');
});

// Role-based redirect route
Route::get('/redirect', function () {
    $user = auth()->user();
    if ($user->hasRole('admin')) {
        return redirect('/admin-dashboard');
    } elseif ($user->hasRole('teacher')) {
        return redirect('/teacher-dashboard');
    } else {
        return redirect('/student-dashboard');
    }
})->middleware('auth')->name('redirect');

// Student list
Route::get('/students', [StudentController::class, 'index'])
    ->middleware('auth')
    ->name('students.index');

// Attendance list
Route::get('/attendances', [AttendanceController::class, 'index'])
    ->middleware('auth')
    ->name('attendances.index');

// Attendance report
Route::get('/attendances/report', [AttendanceController::class, 'report'])
    ->middleware('auth')
    ->name('attendances.report');

// Optional default dashboard view (can be removed if not used)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// User profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Breeze auth routes (login, register, etc.)
require __DIR__.'/auth.php';
