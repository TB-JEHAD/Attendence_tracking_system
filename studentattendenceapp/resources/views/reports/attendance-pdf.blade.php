<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Attendance Report - {{ $month }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
        h2 { text-align: center; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h2>📄 Attendance Report</h2>
    <p>Student: <strong>{{ $student->name }}</strong></p>
    <p>Email: {{ $student->email }}</p>
    <p>Month: {{ \Carbon\Carbon::parse($month)->format('F Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($records as $record)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($record->date)->format('d M Y') }}</td>
                    <td>{{ ucfirst($record->status) }}</td>
                    <td>{{ $record->remarks ?? '--' }}</td>
                </tr>
            @empty
                <tr><td colspan="3">No records found for this month.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
