@extends('layouts.app')
@php
    use Illuminate\Support\Facades\Auth;
@endphp

@section('content')

<style>
    body {
        background-color: #f8fafc;
        font-family: 'Segoe UI', sans-serif;
    }
    .dashboard-container {
        max-width: 1140px;
        margin: auto;
    }
    .welcome-card {
        background: linear-gradient(to right, #4f46e5, #3b82f6);
        color: white;
        border-radius: 1rem;
        padding: 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
    }
    .welcome-text h3 {
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-size: 1.75rem;
    }
    .welcome-text p {
        font-size: 1rem;
        opacity: 0.95;
    }
    .avatar-img {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 0 0 4px rgba(255,255,255,0.2);
    }
    .stat-card-group {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        flex: 1;
        min-width: 280px;
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.06);
        transition: transform 0.3s ease;
        background-color: #ffffff;
    }
    .stat-card:hover {
        transform: translateY(-4px);
    }
    .bg-teachers { background-color: #eef2ff; }
    .bg-students { background-color: #e6fffa; }
    .bg-attendance { background-color: #fff7ed; }
    .stat-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #4b5563;
    }
    .stat-value {
        font-size: 2.3rem;
        font-weight: 700;
        color: #2d3748;
    }
    .stat-icon {
        font-size: 2.5rem;
        margin-top: 0.5rem;
    }
    .chart-card {
        background: #fff;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 6px 12px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }
    .chart-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #2d3748;
    }
    canvas {
        max-height: 280px;
    }
    @media (max-width: 768px) {
        .welcome-card {
            flex-direction: column;
            text-align: center;
        }
        .avatar-img {
            margin-top: 1rem;
        }
        .stat-card-group {
            flex-direction: column;
        }
    }
</style>

<div class="container dashboard-container py-5">
    <!-- 🌟 Welcome Section -->
    <div class="welcome-card">
        <div class="welcome-text">
            <h3>Welcome, {{ Auth::user()->name }} 👋</h3>
            <p>Monitor and manage key system metrics.</p>
        </div>
        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff&size=128"
             class="avatar-img" alt="Avatar">
    </div>

    <!-- 📊 Stats Section -->
    <div class="stat-card-group">
        <div class="stat-card bg-teachers">
            <div class="stat-title">Total Teachers</div>
            <div class="stat-value">{{ $totalTeachers ?? '--' }}</div>
            <i class="fas fa-chalkboard-teacher stat-icon" style="color:#6366f1;"></i>
        </div>
        <div class="stat-card bg-students">
            <div class="stat-title">Total Students</div>
            <div class="stat-value">{{ $totalStudents ?? '--' }}</div>
            <i class="fas fa-user-graduate stat-icon" style="color:#10b981;"></i>
        </div>
        <div class="stat-card bg-attendance">
            <div class="stat-title">Today's Attendance Rate</div>
            <div class="stat-value">{{ $attendanceRate ?? '--' }}%</div>
            <i class="fas fa-calendar-check stat-icon" style="color:#f59e0b;"></i>
        </div>
    </div>

    <!-- 📈 Charts -->
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="chart-card">
                <div class="chart-title">📈 Attendance Trends by Day</div>
                <canvas id="adminLineChart"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="chart-card">
                <div class="chart-title">📊 Present vs Absent (Today)</div>
                <canvas id="adminPieChart"></canvas>
            </div>
        </div>
    </div>

    <!-- 🧑‍🏫 Registered Teachers Section -->
    <div class="chart-card">
        <div class="chart-title">📋 Registered Teachers</div>

        @if(session('success'))
            <p style="color:green;">{{ session('success') }}</p>
        @elseif(session('error'))
            <p style="color:red;">{{ session('error') }}</p>
        @endif

        <table border="1" width="100%" cellpadding="8" style="border-collapse: collapse;">
            <thead>
                <tr style="background-color:#eef2ff;">
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($registeredTeachers as $teacher)
                    <tr>
                        <td>{{ $teacher->id }}</td>
                        <td>{{ $teacher->name }}</td>
                        <td>{{ $teacher->email }}</td>
                        <td>
                            <form action="{{ route('admin.teachers.destroy', $teacher->id) }}" method="POST"
                                  onsubmit="return confirm('Delete this teacher permanently?')">
                                @csrf
                                @method('DELETE')
                                <button style="background:#ef4444; color:white; border:none; padding:6px 12px; border-radius:6px;">
                                    🗑 Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @if ($registeredTeachers->isEmpty())
                    <tr>
                        <td colspan="4" style="text-align:center;">No teachers found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js and Icons -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

<script>
    // Line Chart
    const lineCtx = document.getElementById('adminLineChart').getContext('2d');
    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels ?? []) !!},
            datasets: [{
                label: 'Present Count',
                data: {!! json_encode($presentCounts ?? []) !!},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.2)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#10b981'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, max: 100, ticks: { stepSize: 10 } }
            }
        }
    });

    // Pie Chart
    const pieCtx = document.getElementById('adminPieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['Present', 'Absent'],
            datasets: [{
                data: [{{ $presentToday ?? 0 }}, {{ $absentToday ?? 0 }}],
                backgroundColor: ['#10b981', '#ef4444'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>

@endsection
```