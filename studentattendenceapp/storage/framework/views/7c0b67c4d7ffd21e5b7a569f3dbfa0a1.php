<?php
    use Illuminate\Support\Facades\Auth;
?>

<?php $__env->startSection('content'); ?>

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

    .chart-card {
        background: #ffffff;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 6px 16px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }

    .action-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .action-card {
        flex: 1;
        min-width: 280px;
        background-color: #ffffff;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 6px 12px rgba(0,0,0,0.06);
        transition: transform 0.3s ease;
        text-align: center;
    }

    .action-card:hover {
        transform: translateY(-4px);
    }

    .action-card h5 {
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 1.2rem;
        color: #374151;
    }

    .action-card p {
        font-size: 0.9rem;
        color: #6b7280;
        margin-bottom: 1rem;
    }

    .card-bg-attendance { background-color: #eef2ff; }
    .card-bg-students   { background-color: #e6fffa; }
    .card-bg-report     { background-color: #fff7ed; }

    .btn-open {
        border: 1px solid #3a8ef6;
        color: #3a8ef6;
        font-size: 0.85rem;
        padding: 0.4rem 1rem;
        border-radius: 0.5rem;
        text-decoration: none;
    }

    .btn-open:hover {
        background-color: #3a8ef6;
        color: white;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }
    table th,
    table td {
        border: 1px solid #e2e8f0;
        padding: 0.75rem;
        text-align: left;
    }
    table thead tr {
        background-color: #e6fffa;
    }
    table tbody tr:nth-child(even) {
        background-color: #f8fafc;
    }
    .delete-btn {
        background-color: #ef4444;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        cursor: pointer;
    }
    .delete-btn:hover {
        opacity: 0.9;
    }

    @media (max-width: 768px) {
        .welcome-card {
            flex-direction: column;
            text-align: center;
        }
        .avatar-img {
            margin-top: 1rem;
        }
        .action-row {
            flex-direction: column;
        }
    }
</style>

<div class="container dashboard-container py-5">

    <!-- 🎉 Welcome Section -->
    <div class="welcome-card">
        <div class="welcome-text">
            <h3>Welcome, <?php echo e(Auth::user()->name); ?> 👋</h3>
            <p>Stay on top of your teaching tasks today.</p>
        </div>
        <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode(Auth::user()->name)); ?>&background=0D8ABC&color=fff&size=128"
             class="avatar-img" alt="Avatar">
    </div>

    <!-- 📈 Chart Section -->
    <div class="chart-card">
        <h5 class="fw-bold mb-3">📊 Weekly Attendance Overview</h5>
        <div style="height: 280px;">
            <canvas id="attendanceChart"></canvas>
        </div>
    </div>

    <!-- 📌 Action Cards -->
    <div class="action-row">
        <div class="action-card card-bg-attendance">
            <h5>📝 Take Attendance</h5>
            <p>Quickly mark and update daily records.</p>
            <a href="<?php echo e(route('attendances.create')); ?>" class="btn-open">Open</a>
        </div>
        <div class="action-card card-bg-students">
            <h5>🎓 Student List</h5>
            <p>View and manage enrolled students.</p>
            <a href="<?php echo e(route('students.index')); ?>" class="btn-open">Open</a>
        </div>
        <div class="action-card card-bg-report">
            <h5>📑 Reports</h5>
            <p>Generate attendance insights in PDF/CSV.</p>
            <a href="<?php echo e(route('attendances.report')); ?>" class="btn-open">Open</a>
        </div>
    </div>


    
<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($days, 15, 512) ?>,
            datasets: [{
                label: 'Attendance Rate (%)',
                data: <?php echo json_encode($rates, 15, 512) ?>,
                backgroundColor: 'rgba(63, 131, 248, 0.1)',
                borderColor: '#3a8ef6',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#3a8ef6'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 10,
                        callback: (value) => value + '%'
                    }
                }
            }
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/abubakarsiddik/Documents/studentattendenceapp/resources/views/dashboards/teacher.blade.php ENDPATH**/ ?>