<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance Report {{ $start }} to {{ $end }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 1em; }
        th, td { border: 1px solid #333; padding: 4px 8px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h2>Attendance Report</h2>
    <p>Period: <strong>{{ $start }}</strong> to <strong>{{ $end }}</strong></p>

    <table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Total Days</th>
                <th>Present</th>
                <th>Absent</th>
            </tr>
        </thead>
        <tbody>
            @foreach($summary as $row)
                <tr>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->total_days }}</td>
                    <td>{{ $row->present_count }}</td>
                    <td>{{ $row->absent_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
