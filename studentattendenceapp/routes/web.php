<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Shared Routes for Students & Teachers
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:student|teacher'])->group(function () {
    Route::get('/attendances/report',          [AttendanceController::class, 'report'])->name('attendances.report');
    Route::get('/attendances/history',         [AttendanceController::class, 'history'])->name('attendances.history');
    Route::get('/attendances/export/pdf',      [AttendanceController::class, 'exportPdf'])->name('attendances.export.pdf');
    Route::get('/attendances/export/csv',      [AttendanceController::class, 'exportCsv'])->name('attendances.export.csv');
    Route::get('/attendances/weekly-overview', [AttendanceController::class, 'weeklyOverview'])->name('attendances.weekly-overview');
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('welcome'));
require __DIR__.'/auth.php';

Route::get('/redirect', function () {
    $user = Auth::user();
    if ($user->hasRole('admin'))   return redirect()->route('admin.dashboard');
    if ($user->hasRole('teacher')) return redirect()->route('teacher.dashboard');
    if ($user->hasRole('student')) return redirect()->route('student.dashboard');
    abort(403, 'Unauthorized');
})->middleware('auth')->name('redirect');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin-dashboard', function () {
        $totalTeachers = User::role('teacher')->count();
        $totalStudents = User::role('student')->count();
        $presentToday  = Attendance::whereDate('date', today())->where('status', 'present')->count();
        $absentToday   = Attendance::whereDate('date', today())->where('status', 'absent')->count();
        $attendanceRate = $totalStudents ? round($presentToday / $totalStudents * 100, 2) : 0;

        $lastWeek = Attendance::selectRaw('DATE(date) as date, COUNT(*) as count')
            ->whereBetween('date', [now()->subDays(6)->toDateString(), now()->toDateString()])
            ->groupByRaw('DATE(date)')
            ->orderBy('date')
            ->get();

        $labels         = $lastWeek->pluck('date')->map(fn($d) => Carbon::parse($d)->format('D'))->toArray();
        $presentCounts  = $lastWeek->pluck('count')->toArray();
        $registeredTeachers = User::role('teacher')
            ->whereDoesntHave('roles', fn($q) => $q->where('name', 'admin'))
            ->get();

        return view('dashboards.admin', compact(
            'totalTeachers', 'totalStudents', 'presentToday', 'absentToday',
            'attendanceRate', 'labels', 'presentCounts', 'registeredTeachers'
        ));
    })->name('admin.dashboard');

    Route::delete('/admin-dashboard/delete-teacher/{id}', function ($id) {
        $user = User::findOrFail($id);
        if ($user->hasRole('teacher')) {
            $user->delete();
            return back()->with('success', 'Teacher deleted.');
        }
        return back()->with('error', 'Not a teacher.');
    })->name('admin.teachers.destroy');
});

/*
|--------------------------------------------------------------------------
| Teacher Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/teacher-dashboard', function () {
        $totalStudents = User::role('student')->count();

        $lastWeek = Attendance::selectRaw('DATE(date) as day, SUM(status="present") as present_count')
            ->whereBetween('date', [now()->subDays(6)->toDateString(), now()->toDateString()])
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $labels = $lastWeek->pluck('day')->map(fn($d) => Carbon::parse($d)->format('D'))->toArray();
        $rates = $lastWeek->map(fn($rec) =>
            $totalStudents ? round($rec->present_count / $totalStudents * 100, 2) : 0
        )->toArray();

        $registeredStudents = User::role('student')
            ->whereDoesntHave('roles', fn($q) => $q->whereIn('name', ['admin', 'teacher']))
            ->paginate(15);

       return view('dashboards.teacher', [
    'registeredStudents' => $registeredStudents,
    'days' => $labels, // 👈 This renames $labels to $days for the Blade file
    'rates' => $rates
]);

    })->name('teacher.dashboard');

    Route::delete('/teacher-dashboard/delete-student/{id}', function ($id) {
        $user = User::findOrFail($id);
        if ($user->hasRole('student')) {
            $user->delete();
            return back()->with('success', 'Student deleted.');
        }
        return back()->with('error', 'Not a student.');
    })->name('teacher.students.destroy');

    Route::get('/students',                     [StudentController::class, 'index'])->name('students.index');
    Route::delete('/students/{id}',             [StudentController::class, 'destroy'])->name('students.destroy');

    Route::get('/attendances',                  [AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('/attendances/create',           [AttendanceController::class, 'create'])->name('attendances.create');
    Route::post('/attendances',                 [AttendanceController::class, 'store'])->name('attendances.store');
    Route::get('/attendances/cancel',           [AttendanceController::class, 'cancel'])->name('attendances.cancel');
    Route::get('/attendances/edit',             [AttendanceController::class, 'edit'])->name('attendances.edit');
});

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student-dashboard', fn() => view('dashboards.student'))->name('student.dashboard');
});

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::get('/profile',   [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');
});
