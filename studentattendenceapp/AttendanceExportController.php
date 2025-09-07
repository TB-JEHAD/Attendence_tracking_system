<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendanceExportController extends Controller
{
    /**
     * Export filtered attendance data as CSV (Admin & Teacher)
     */
    public function exportCsv(Request $request)
    {
        $month = $request->input('month');
        $class = $request->input('class');
        $studentId = $request->input('student_id');

        $query = Attendance::with('student');

        if ($month) {
            $query->whereMonth('date', Carbon::parse($month)->month)
                  ->whereYear('date', Carbon::parse($month)->year);
        }

        if ($class) {
            $query->whereHas('student', fn($q) => $q->where('class', $class));
        }

        if ($studentId) {
            $query->where('student_id', $studentId);
        }

        $attendances = $query->get();

        $filename = 'attendance_export_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($attendances) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Student Name', 'Class', 'Date', 'Status', 'Remarks']);

            foreach ($attendances as $attendance) {
                fputcsv($handle, [
                    $attendance->student->name,
                    $attendance->student->class,
                    $attendance->date,
                    ucfirst($attendance->status),
                    $attendance->remarks,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export filtered attendance data as PDF (Admin & Teacher)
     */
    public function exportPdf(Request $request)
    {
        $month = $request->input('month');
        $class = $request->input('class');
        $studentId = $request->input('student_id');

        $query = Attendance::with('student');

        if ($month) {
            $query->whereMonth('date', Carbon::parse($month)->month)
                  ->whereYear('date', Carbon::parse($month)->year);
        }

        if ($class) {
            $query->whereHas('student', fn($q) => $q->where('class', $class));
        }

        if ($studentId) {
            $query->where('student_id', $studentId);
        }

        $attendances = $query->get();

        $pdf = Pdf::loadView('attendances.pdf', ['attendances' => $attendances]);

        return $pdf->download('attendance_export_' . now()->format('Ymd_His') . '.pdf');
    }
}
