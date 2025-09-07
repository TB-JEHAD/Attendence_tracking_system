<!DOCTYPE html>
<html>
<head>
    <title>📅 My Attendance History</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            margin: 0;
            padding: 2rem;
            color: #1e293b;
        }

        h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 2rem;
            color: #2563eb;
        }

        .table-wrapper {
            max-width: 800px;
            margin: auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 12px;
            overflow: hidden;
        }

        thead {
            background-color: #f1f5f9;
        }

        th, td {
            padding: 1rem;
            text-align: center;
        }

        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .status-present {
            color: #22c55e;
            font-weight: 600;
        }

        .status-absent {
            color: #ef4444;
            font-weight: 600;
        }

        .status-norecord {
            color: #f59e0b;
            font-weight: 600;
        }

        .pagination {
            text-align: center;
            margin-top: 1.5rem;
        }

        .pagination svg {
            height: 1.2rem;
        }

        .pagination nav > div:first-child {
            display: none;
        }
    </style>
</head>
<body>

    <h2>📅 My Attendance History</h2>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $row)
                    @php
                        $status = strtolower($row->status);
                        $class = match($status) {
                            'present' => 'status-present',
                            'absent' => 'status-absent',
                            default => 'status-norecord'
                        };
                    @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($row->date)->toFormattedDateString() }}</td>
                        <td class="{{ $class }}">{{ ucfirst($status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $records->links() }}
    </div>

</body>
</html>
