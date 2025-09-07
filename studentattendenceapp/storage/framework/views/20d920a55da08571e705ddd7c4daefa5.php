<?php $__env->startSection('content'); ?>
<div class="container py-5">

    <h1 class="mb-4">📋 Registered Students</h1>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php elseif(session('error')): ?>
        <div class="alert alert-danger">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <?php if($students->isEmpty()): ?>
        <p>No students found.</p>
    <?php else: ?>
        <table class="table table-striped table-bordered">
            <thead class="thead-light">
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 40%;">Name</th>
                    <th style="width: 40%;">Email</th>
                    <th style="width: 15%;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($student->id); ?></td>
                        <td><?php echo e($student->name); ?></td>
                        <td><?php echo e($student->email); ?></td>
                        <td>
                            <form action="<?php echo e(route('students.destroy', $student->id)); ?>" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this student permanently?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">
                                    🗑 Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            <?php echo e($students->links()); ?>

        </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/abubakarsiddik/Documents/studentattendenceapp/resources/views/students/index.blade.php ENDPATH**/ ?>