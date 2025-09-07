<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttendanceExportController extends Controller
{
    /**
     * Export attendance as CSV file
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $month = $request->input('month', now()->format('Y-m'));

        $students = Student::with(['attendances' => function ($query) use ($month) {
            $query->whereYear('date', Carbon::parse($month)->year)
                  ->whereMonth('date', Carbon::parse($month)->month);
        }])->get();

        $fileName = "attendance_report_$month.csv";

        $callback = function () use ($students) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Roll', 'Class', 'Present', 'Absent', 'Late']);

            foreach ($students as $student) {
                fputcsv($file, [
                    $student->name,
                    $student->roll_number,
                    $student->class,
                    $student->attendances->where('status', 'present')->count(),
                    $student->attendances->where('status', 'absent')->count(),
                    $student->attendances->where('status', 'late')->count(),
                ]);
            }

            fclose($file);
        };

        return response()->streamDownload($callback, $fileName);
    }

    /**
     * Export attendance as PDF file
     */
    public function exportPdf(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));

        $students = Student::with(['attendances' => function ($query) use ($month) {
            $query->whereYear('date', Carbon::parse($month)->year)
                  ->whereMonth('date', Carbon::parse($month)->month);
        }])->get();

        $pdf = Pdf::loadView('attendances.pdf', compact('students', 'month'))
                  ->setPaper('A4', 'landscape');

        return $pdf->download("attendance_report_$month.pdf");
    }
}
