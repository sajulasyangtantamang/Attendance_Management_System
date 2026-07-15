<?php $__env->startSection('title', 'Take Attendance'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm" style="max-width:600px;">
    <div class="card-body">
        <h5 class="mb-3">Select Session</h5>
        <form method="POST" action="<?php echo e(route('attendance.take')); ?>">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label class="form-label">Class</label>
                <select name="class_id" class="form-select" required>
                    <option value="">Select Class</option>
                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?> <?php echo e($class->section); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Subject</label>
                <select name="subject_id" class="form-select">
                    <option value="">Select Subject (optional)</option>
                    <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($subject->id); ?>"><?php echo e($subject->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="<?php echo e(date('Y-m-d')); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Period (optional)</label>
                <input type="text" name="period" class="form-control" placeholder="e.g. 1st Period">
            </div>
            <button class="btn btn-primary">Continue</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/sajulasyangtan/Desktop/attendance-system/resources/views/attendance/select.blade.php ENDPATH**/ ?>