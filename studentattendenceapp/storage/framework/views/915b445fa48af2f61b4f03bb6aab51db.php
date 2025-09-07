

<?php $__env->startSection('content'); ?>
<?php use Carbon\Carbon; ?>

<!-- 🌟 Custom Styles -->
<style>
    body {
        background: linear-gradient(to right, #e0f2fe, #fefce8);
        font-family: 'Inter', sans-serif;
    }

    .section-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: #1e3a8a;
        text-shadow: 1px 1px 2px #cbd5e1;
        margin-bottom: 2.5rem;
    }

    .filter-card {
        backdrop-filter: blur(12px);
        background: rgba(255, 255, 255, 0.85);
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06);
        margin-bottom: 2.5rem;
    }

    .filter-card label {
        font-weight: 600;
        color: #334155;
    }

    .filter-card .form-control {
        border-radius: 0.75rem;
        border: 1px solid #cbd5e1;
        background-color: #f8fafc;
        transition: all 0.2s ease;
    }

    .filter-card .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.1rem rgba(59, 130, 246, 0.25);
    }

    .filter-card .btn {
        border-radius: 0.75rem;
        font-weight: 600;
        padding: 0.55rem 1.5rem;
        transition: all 0.25s ease-in-out;
    }

    .filter-card .btn-primary:hover {
        background-color: #2563eb;
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35);
    }

    .filter-card .btn-outline-success:hover {
        background-color: #16a34a;
        color: #fff;
        border-color: #16a34a;
    }

    .filter-card .btn-outline-danger:hover {
        background-color: #dc2626;
        color: #fff;
        border-color: #dc2626;
    }

    .table-wrapper {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.05);
        overflow-x: auto;
    }

    .table thead {
        background-color: #f1f5f9;
        color: #1e293b;
        font-weight: 700;
    }

    .table td, .table th {
        vertical-align: middle;
        padding: 1rem;
    }

    .table tbody tr:hover {
        background-color: #f9fafb;
        transition: background-color 0.2s ease;
    }
</style>

<!-- 📄 Attendance Report Page -->
<div class="container py-5">
    <h2 class="section-title text-center">📑 Attendance Report</h2>

    <!-- 🎯 Filter Panel -->
    <div class="filter-card">
        <form class="row g-3" method="GET" action="">
            <div class="col-md-3">
                <label for="start" class="form-label">Start Date</label>
                <input type="date" id="start" name="start" class="form-control" value="<?php echo e($start); ?>">
            </div>
            <div class="col-md-3">
                <label for="end" class="form-label">End Date</label>
                <input type="date" id="end" name="end" class="form-control" value="<?php echo e($end); ?>">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <a href="<?php echo e(route('attendances.export.csv', ['start'=>$start,'end'=>$end])); ?>"
                   class="btn btn-outline-success w-50">
                    <i class="fas fa-file-csv me-1"></i> CSV
                </a>
                <a href="<?php echo e(route('attendances.export.pdf', ['start'=>$start,'end'=>$end])); ?>"
                   class="btn btn-outline-danger w-50">
                    <i class="fas fa-file-pdf me-1"></i> PDF
                </a>
            </div>
        </form>
    </div>

    <!-- 📊 Table Section -->
    <div class="table-wrapper">
        <table class="table table-bordered align-middle text-center">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Total Days</th>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>Attendance %</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($row->name); ?></td>
                        <td><?php echo e($row->total_days); ?></td>
                        <td><?php echo e($row->present_count); ?></td>
                        <td><?php echo e($row->absent_count); ?></td>
                        <td>
                            <?php echo e($row->total_days
                                ? round($row->present_count / $row->total_days * 100, 2) . '%'
                                : 'N/A'); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-muted">No attendance records found for this range.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/abubakarsiddik/Documents/studentattendenceapp/resources/views/attendances/report.blade.php ENDPATH**/ ?>