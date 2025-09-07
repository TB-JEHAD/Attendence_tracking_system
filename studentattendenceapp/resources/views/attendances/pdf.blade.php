<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance Report - {{ \Carbon\Carbon::parse($month)->format('F Y') }}</title>
    <style>
        @page { margin: 20px; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #999;
            padding: 6px 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            text-align: center;
            font-style: italic;
            font-size: 11px;
            color: #666;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <h2>Attendance Report — {{ \Carbon\Carbon::parse($month)->format('F Y') }}</h2>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Roll</th>
                <th>Class</th>
                <th>Present</th>
                <th>Absent</th>
                <th>Late</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                @php
                    $present = $student->attendances->where('status', 'present')->count();
                    $absent  = $student->attendances->where('status', 'absent')->count();
                    $late    = $student->attendances->where('status', 'late')->count();
                @endphp
                <tr>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->roll_number }}</td>
                    <td>{{ $student->class }}</td>
                    <td>{{ $present }}</td>
                    <td>{{ $absent }}</td>
                    <td>{{ $late }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Report generated on {{ now()->format('d M Y, h:i A') }}
    </div>
</body>
</html>
