<?php $__env->startSection('content'); ?>
<style>
    body {
        background: linear-gradient(to right, #f8fafc, #e0f2fe);
        font-family: 'Inter', sans-serif;
    }

    .attendance-container {
        max-width: 900px;
        margin: auto;
        padding: 3rem 1rem;
    }

    .attendance-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .attendance-header h2 {
        font-size: 2.2rem;
        font-weight: 700;
        color: #1e3a8a;
    }

    .attendance-header span {
        display: block;
        font-size: 1rem;
        color: #64748b;
        margin-top: 0.25rem;
    }

    .card-table {
        background: #ffffff;
        border-radius: 1rem;
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.05);
        padding: 2rem;
        overflow-x: auto;
    }

    .table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: collapse;
    }

    .table thead {
        background-color: #f1f5f9;
    }

    .table th, .table td {
        text-align: center;
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        font-size: 1rem;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    .status-present {
        color: #16a34a;
        font-weight: 600;
    }

    .status-absent {
        color: #dc2626;
        font-weight: 600;
    }

    .status-norecord {
        color: #f59e0b;
        font-weight: 600;
    }

    .status-icon {
        margin-right: 0.5rem;
    }

    .table tbody tr:hover {
        background-color: #f9fafb;
        transition: 0.2s ease-in-out;
    }
</style>

<div class="attendance-container">
    <div class="attendance-header">
        <h2>🗓 Your Attendance</h2>
        <span><?php echo e($start); ?> → <?php echo e($end); ?></span>
    </div>

    <div class="card-table">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $period; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
               <?php
    $statusRaw = $records[$day]->status ?? 'No Record';
    $status = strtolower($statusRaw);
    $statusClass = match($status) {
        'present' => 'status-present',
        'absent' => 'status-absent',
        default => 'status-norecord',
    };
    $statusIcon = match($status) {
        'present' => '✅',
        'absent' => '❌',
        default => '🟡',
    };
?>

                    <tr>
                        <td><?php echo e(\Carbon\Carbon::parse($day)->format('M j, Y (D)')); ?></td>
                        <td class="<?php echo e($statusClass); ?>">
                            <span class="status-icon"><?php echo e($statusIcon); ?></span><?php echo e($status); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/abubakarsiddik/Documents/studentattendenceapp/resources/views/attendances/report_student.blade.php ENDPATH**/ ?>