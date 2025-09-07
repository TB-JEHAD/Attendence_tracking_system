<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Attendance <?php echo e($start); ?> to <?php echo e($end); ?></title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 1em; }
        th, td { border: 1px solid #333; padding: 4px 8px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h2>My Attendance</h2>
    <p>Period: <strong><?php echo e($start); ?></strong> to <strong><?php echo e($end); ?></strong></p>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e(\Carbon\Carbon::parse($row->date)->toFormattedDateString()); ?></td>
                    <td><?php echo e(ucfirst($row->status)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH /Users/abubakarsiddik/Documents/studentattendenceapp/resources/views/attendances/report_pdf_student.blade.php ENDPATH**/ ?>