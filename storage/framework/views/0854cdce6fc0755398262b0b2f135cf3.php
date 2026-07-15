<?php $__env->startSection('title', 'Attendance Records'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Attendance Records</h5>
    <?php if(auth()->guard()->check()): ?>
        <?php if(auth()->user()->isTeacher() || auth()->user()->isAdmin()): ?>
            <a href="<?php echo e(route('attendance.create')); ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Take Attendance</a>
        <?php endif; ?>
    <?php endif; ?>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-5">
                <select name="class_id" class="form-select">
                    <option value="">All Classes</option>
                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($class->id); ?>" <?php if(request('class_id') == $class->id): echo 'selected'; endif; ?>><?php echo e($class->name); ?> <?php echo e($class->section); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-4">
                <input type="date" name="date" class="form-control" value="<?php echo e(request('date')); ?>">
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-secondary w-100"><i class="bi bi-funnel me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Date</th><th>Class</th><th>Subject</th><th>Teacher</th><th>Students Marked</th><th>Actions</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $sessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($session->date->format('M d, Y')); ?></td>
                        <td><?php echo e($session->classRoom->name); ?> <?php echo e($session->classRoom->section); ?></td>
                        <td><?php echo e($session->subject->name ?? '-'); ?></td>
                        <td><?php echo e($session->teacher->name); ?></td>
                        <td><?php echo e($session->details()->count()); ?></td>
                        <td>
                            <?php if(auth()->user()->isAdmin()): ?>
                                <form action="<?php echo e(route('attendance.destroy', $session)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this session?');">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center text-muted py-3">No attendance sessions found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-body"><?php echo e($sessions->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/sajulasyangtan/Desktop/attendance-system/resources/views/attendance/index.blade.php ENDPATH**/ ?>