<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance Report <?php echo e($start); ?> to <?php echo e($end); ?></title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 1em; }
        th, td { border: 1px solid #333; padding: 4px 8px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h2>Attendance Report</h2>
    <p>Period: <strong><?php echo e($start); ?></strong> to <strong><?php echo e($end); ?></strong></p>

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
            <?php $__currentLoopData = $summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($row->name); ?></td>
                    <td><?php echo e($row->total_days); ?></td>
                    <td><?php echo e($row->present_count); ?></td>
                    <td><?php echo e($row->absent_count); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH /Users/abubakarsiddik/Documents/studentattendenceapp/resources/views/attendances/report_pdf_teacher.blade.php ENDPATH**/ ?>