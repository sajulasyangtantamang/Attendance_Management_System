<?php $__env->startSection('title', 'Edit Department'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h5 class="mb-3">Edit Department</h5>
        <form method="POST" action="<?php echo e(route('admin.departments.update', $department)); ?>">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $department->name)); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Code</label>
                <input type="text" name="code" class="form-control" value="<?php echo e(old('code', $department->code)); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"><?php echo e(old('description', $department->description)); ?></textarea>
            </div>
            <button class="btn btn-primary">Update</button>
            <a href="<?php echo e(route('admin.departments.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/sajulasyangtan/Desktop/attendance-system/resources/views/admin/departments/edit.blade.php ENDPATH**/ ?>