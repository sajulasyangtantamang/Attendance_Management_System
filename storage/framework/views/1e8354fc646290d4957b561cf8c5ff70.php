<?php $__env->startSection('title', 'Edit Subject'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h5 class="mb-3">Edit Subject</h5>
        <form method="POST" action="<?php echo e(route('admin.subjects.update', $subject)); ?>">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $subject->name)); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Code</label>
                <input type="text" name="code" class="form-control" value="<?php echo e(old('code', $subject->code)); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Department</label>
                <select name="department_id" class="form-select">
                    <option value="">Select Department</option>
                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($department->id); ?>" <?php if($subject->department_id == $department->id): echo 'selected'; endif; ?>><?php echo e($department->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <button class="btn btn-primary">Update</button>
            <a href="<?php echo e(route('admin.subjects.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/sajulasyangtan/Desktop/attendance-system/resources/views/admin/subjects/edit.blade.php ENDPATH**/ ?>