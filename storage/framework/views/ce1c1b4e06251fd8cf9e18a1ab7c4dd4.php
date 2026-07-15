<?php $__env->startSection('title', 'Classes'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Classes</h5>
    <a href="<?php echo e(route('admin.classes.create')); ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Class</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Name</th><th>Section</th><th>Department</th><th>Class Teacher</th><th>Actions</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($class->name); ?></td>
                        <td><?php echo e($class->section); ?></td>
                        <td><?php echo e($class->department->name ?? '-'); ?></td>
                        <td><?php echo e($class->classTeacher->name ?? '-'); ?></td>
                        <td>
                            <a href="<?php echo e(route('admin.classes.edit', $class)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="<?php echo e(route('admin.classes.destroy', $class)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this class?');">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-center text-muted py-3">No classes found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-body"><?php echo e($classes->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/sajulasyangtan/Desktop/attendance-system/resources/views/admin/classes/index.blade.php ENDPATH**/ ?>