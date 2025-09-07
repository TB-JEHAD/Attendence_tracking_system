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
        background: linear-gradient(to right, #10b981, #14b8a6);
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

    .summary-card {
        background: #ffffff;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 6px 16px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }

    .quick-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .quick-card {
        flex: 1;
        min-width: 280px;
        background-color: #ffffff;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 6px 12px rgba(0,0,0,0.06);
        transition: transform 0.3s ease;
        text-align: center;
    }

    .quick-card:hover {
        transform: translateY(-4px);
    }

    .quick-card h5 {
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 1.2rem;
        color: #374151;
    }

    .quick-card p {
        font-size: 0.9rem;
        color: #6b7280;
        margin-bottom: 1rem;
    }

    .card-bg-report { background-color: #e0f2fe; }
    .card-bg-history { background-color: #fefce8; }
    .card-bg-profile { background-color: #f3f4f6; }

    .btn-open {
        border: 1px solid #14b8a6;
        color: #14b8a6;
        font-size: 0.85rem;
        padding: 0.4rem 1rem;
        border-radius: 0.5rem;
        text-decoration: none;
    }

    .btn-open:hover {
        background-color: #14b8a6;
        color: white;
    }

    @media (max-width: 768px) {
        .welcome-card {
            flex-direction: column;
            text-align: center;
        }
        .avatar-img {
            margin-top: 1rem;
        }
        .quick-actions {
            flex-direction: column;
        }
    }
</style>

<div class="container dashboard-container py-5">

    <!-- 🎓 Welcome Card -->
    <div class="welcome-card">
        <div class="welcome-text">
            <h3>Hello, <?php echo e(Auth::user()->name); ?> 👋</h3>
            <p>Your attendance progress and tools are below.</p>
        </div>
        <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode(Auth::user()->name)); ?>&background=10b981&color=fff&size=128" class="avatar-img" alt="Avatar">
    </div>

    <!-- 📊 Attendance Summary -->
    <div class="summary-card">
        <h5 class="fw-bold mb-3">📅 Monthly Attendance Summary</h5>
        <p>
            You've attended <strong><?php echo e($daysPresent ?? '--'); ?></strong> out of 
            <strong><?php echo e($totalDays ?? '--'); ?></strong> days this month.
        </p>
        <a href="<?php echo e(route('attendances.report')); ?>" class="btn-open">View Detailed Report</a>
    </div>

    <!-- 🔗 Quick Actions -->
    <div class="quick-actions">
        <div class="quick-card card-bg-history">
            <h5>🕒 Attendance History</h5>
            <p>Track your past attendance records.</p>
         <a href="<?php echo e(route('attendances.history')); ?>" class="btn-open">Open</a>

        </div>
        <div class="quick-card card-bg-profile">
            <h5>📝 Edit Profile</h5>
            <p>Update your personal details here.</p>
            <a href="<?php echo e(route('profile.edit')); ?>" class="btn-open">Open</a>
        </div>
        <div class="quick-card card-bg-report">
            <h5>📑 Download Report</h5>
            <p>Export your monthly attendance report.</p>
            <a href="<?php echo e(route('attendances.export.pdf')); ?>" class="btn-open">Download PDF</a>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/abubakarsiddik/Documents/studentattendenceapp/resources/views/dashboards/student.blade.php ENDPATH**/ ?>