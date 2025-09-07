<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Support\Carbon;

class AdminDashboardController extends Controller
{
   public function index()
{
    $today = Carbon::today()->toDateString();
    $month = Carbon::now()->month;
    $year = Carbon::now()->year;

    $totalTeachers = User::role('teacher')->count();
    $totalStudents = User::role('student')->count();

    $presentToday = Attendance::whereDate('date', $today)->where('status', 'present')->count();
    $absentToday = Attendance::whereDate('date', $today)->where('status', 'absent')->count();

    $totalToday = $presentToday + $absentToday;
    $attendanceRate = $totalToday > 0 ? round(($presentToday / $totalToday) * 100) : 0;

    $labels = [];
    $presentCounts = [];

    for ($d = 1; $d <= 30; $d++) {
        $date = Carbon::createFromDate($year, $month, $d)->format('Y-m-d');
        $labels[] = $d;
        $presentCounts[] = Attendance::whereDate('date', $date)->where('status', 'present')->count();
    }

    return view('dashboards.admin', compact(
        'totalTeachers', 'totalStudents', 'attendanceRate',
        'presentToday', 'absentToday', 'labels', 'presentCounts'
    ));
}

}
