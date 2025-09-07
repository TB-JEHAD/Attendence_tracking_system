<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Attendance {{ $start }} to {{ $end }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 1em; }
        th, td { border: 1px solid #333; padding: 4px 8px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h2>My Attendance</h2>
    <p>Period: <strong>{{ $start }}</strong> to <strong>{{ $end }}</strong></p>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $row)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($row->date)->toFormattedDateString() }}</td>
                    <td>{{ ucfirst($row->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
