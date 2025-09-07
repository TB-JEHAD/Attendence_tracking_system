<?php $__env->startSection('content'); ?>
    <div class="max-w-7xl mx-auto py-12 px-4">
        <h2 class="text-lg font-medium text-black">Profile Information</h2>
        <p class="mt-1 text-sm text-black">
            Update your account's profile information and email address.
        </p>

        <?php echo $__env->make('profile.partials.update-profile-information-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/abubakarsiddik/Documents/studentattendenceapp/resources/views/profile/edit.blade.php ENDPATH**/ ?>