<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\User;

class AttendanceController extends Controller
{
   public function __construct()
{
    // Teacher-only methods
    $this->middleware(['auth','role:teacher'])->only([
        'create', 'store', 'cancel', 'edit'
    ]);

    // Shared methods for student & teacher
    $this->middleware(['auth','role:student|teacher'])->only([
        'report', 'history', 'exportPdf', 'exportCsv', 'weeklyOverview'
    ]);
}

public function history(Request $request)
{
    $user = auth()->user();

    $records = Attendance::where('user_id', $user->id)
        ->orderBy('date', 'desc')
        ->paginate(20);

    return view('attendances.history', compact('records'));
}

    public function create(Request $request)
    {
        $date     = $request->query('date', Carbon::today()->toDateString());
        $students = User::role('student')
            ->whereDoesntHave('roles', fn($q) => $q->whereIn('name',['admin','teacher']))
            ->orderBy('name')
            ->get();

        $records   = Attendance::whereDate('date', $date)->get();
        $statusMap = $records->pluck('status','user_id')->toArray();

        $canEdit = $records->isNotEmpty()
            && Carbon::now()->diffInMinutes($records->first()->created_at) <= 15;

        return view('attendances.create', compact(
            'students','date','statusMap','canEdit'
        ));
    }

    public function store(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        $data = $request->validate([
            'attendance'          => 'required|array',
            'attendance.*.status' => 'required|in:present,absent',
        ]);

        foreach ($data['attendance'] as $userId => $attrs) {
            $rec = Attendance::where('user_id',$userId)
                ->whereDate('date',$date)
                ->first();

            if ($rec) {
                if (Carbon::now()->diffInMinutes($rec->created_at) <= 15) {
                    $rec->update(['status' => $attrs['status']]);
                }
            } else {
                Attendance::create([
                    'user_id' => $userId,
                    'date'    => $date,
                    'status'  => $attrs['status'],
                ]);
            }
        }

        return redirect()
            ->route('attendances.create',['date'=>$date])
            ->with('success','Attendance saved.');
    }

    public function cancel(Request $request)
    {
        $date = $request->query('date', Carbon::today()->toDateString());
        Attendance::whereDate('date', $date)->delete();

        return redirect()
            ->route('attendances.create',['date'=>$date])
            ->with('success',"Attendance for {$date} canceled.");
    }

    public function edit(Request $request)
    {
        return $this->create($request);
    }

    public function report(Request $request)
    {
        $user  = auth()->user();
        $start = $request->query('start', now()->startOfMonth()->toDateString());
        $end   = $request->query('end',   now()->endOfMonth()->toDateString());

        if ($user->hasRole('teacher')) {
            // 🧑‍🏫 Teacher sees summary for all students
            $summary = DB::table('attendances')
                ->join('users','attendances.user_id','=','users.id')
                ->select(
                    'users.name',
                    DB::raw('COUNT(*) as total_days'),
                    DB::raw("SUM(status='present') as present_count"),
                    DB::raw("SUM(status='absent')  as absent_count")
                )
                ->whereBetween('date', [$start, $end])
                ->groupBy('users.id', 'users.name')
                ->orderBy('users.name')
                ->get();

            return view('attendances.report', compact('summary','start','end'));
        }

        // 👨‍🎓 Student sees only their attendance
        $records = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$start, $end])
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $period = Carbon::parse($start)
            ->daysUntil($end)
            ->map(fn($d) => $d->toDateString());

        return view('attendances.report_student', [
            'user'    => $user,
            'period'  => $period,
            'records' => $records,
            'start'   => $start,
            'end'     => $end,
        ]);
    }

    public function exportCsv(Request $request)
    {
        $start = $request->query('start', now()->startOfMonth()->toDateString());
        $end   = $request->query('end',   now()->endOfMonth()->toDateString());

        $rows = DB::table('attendances')
            ->join('users','attendances.user_id','=','users.id')
            ->select(
                'users.name as Name',
                DB::raw('COUNT(*) as TotalDays'),
                DB::raw("SUM(status='present') as Present"),
                DB::raw("SUM(status='absent')  as Absent")
            )
            ->whereBetween('date', [$start, $end])
            ->groupBy('users.id','users.name')
            ->orderBy('users.name')
            ->get()
            ->toArray();

        $filename = "attendance_report_{$start}_to_{$end}.csv";
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($rows) {
            $out = fopen('php://output','w');
            fputcsv($out, ['Name','Total Days','Present','Absent']);
            foreach ($rows as $row) {
                fputcsv($out, [
                    $row->Name,
                    $row->TotalDays,
                    $row->Present,
                    $row->Absent,
                ]);
            }
            fclose($out);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }

public function exportPdf(Request $request)
{
    $user  = auth()->user();
    $start = $request->query('start', now()->startOfMonth()->toDateString());
    $end   = $request->query('end',   now()->endOfMonth()->toDateString());

    if ($user->hasRole('teacher')) {
        // Teacher: multi-student summary
        $summary = DB::table('attendances')
            ->join('users','attendances.user_id','=','users.id')
            ->select(
                'users.name',
                DB::raw('COUNT(*) as total_days'),
                DB::raw("SUM(status='present') as present_count"),
                DB::raw("SUM(status='absent')  as absent_count")
            )
            ->whereBetween('date', [$start, $end])
            ->groupBy('users.id','users.name')
            ->orderBy('users.name')
            ->get();

        return Pdf::loadView('attendances.report_pdf_teacher', compact('summary','start','end'))
                  ->setPaper('a4','landscape')
                  ->download("attendance_report_{$start}_to_{$end}.pdf");
    }

    if ($user->hasRole('student')) {
        // Student: only their own records
        $records = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$start, $end])
            ->orderBy('date')
            ->get();

        return Pdf::loadView('attendances.report_pdf_student', compact('records','start','end'))
                  ->setPaper('a4','portrait')
                  ->download("my_attendance_{$start}_to_{$end}.pdf");
    }

    abort(403, 'Unauthorized');
}


    public function weeklyOverview()
    {
        // Total student count
        $totalStudents = User::role('student')
            ->whereDoesntHave('roles', fn($q) => $q->whereIn('name',['admin','teacher']))
            ->count();

        // Present count per day for last 7 days
        $last7 = Attendance::selectRaw('DATE(date) as day, SUM(status="present") as present_count')
            ->whereBetween('date', [
                now()->subDays(6)->toDateString(),
                now()->toDateString()
            ])
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // Build day labels (Mon, Tue…)
        $labels = $last7->pluck('day')
            ->map(fn($d) => Carbon::parse($d)->format('D'))
            ->toArray();

        // Build percentages without a `use` in the closure
        $percentages = $last7->map(
            fn($rec) => $totalStudents
                ? round($rec->present_count / $totalStudents * 100, 2)
                : 0
        )->toArray();

        return view('attendances.weekly', compact('labels','percentages'));
    }
}
