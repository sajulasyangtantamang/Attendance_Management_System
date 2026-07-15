<?php $__env->startSection('title', 'Teachers'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Teachers</h5>
    <a href="<?php echo e(route('admin.teachers.create')); ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Teacher</a>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-9">
                <input type="text" name="search" class="form-control" placeholder="Search by name or email" value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-secondary w-100"><i class="bi bi-search me-1"></i>Search</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Name</th><th>Email</th><th>Employee ID</th><th>Department</th><th>Designation</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($teacher->user->name); ?></td>
                        <td><?php echo e($teacher->user->email); ?></td>
                        <td><?php echo e($teacher->employee_id); ?></td>
                        <td><?php echo e($teacher->department->name ?? '-'); ?></td>
                        <td><?php echo e($teacher->designation ?? '-'); ?></td>
                        <td>
                            <a href="<?php echo e(route('admin.teachers.edit', $teacher)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="<?php echo e(route('admin.teachers.destroy', $teacher)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this teacher?');">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center text-muted py-3">No teachers found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-body"><?php echo e($teachers->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/sajulasyangtan/Desktop/attendance-system/resources/views/admin/teachers/index.blade.php ENDPATH**/ ?>