<?php $__env->startSection('title', 'Teacher Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">My Classes</div>
                <div class="fs-3 fw-bold"><?php echo e($todaysClasses->count()); ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Assigned Subjects</div>
                <div class="fs-3 fw-bold"><?php echo e($assignedSubjects->count()); ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Sessions Taken Today</div>
                <div class="fs-3 fw-bold"><?php echo e($todaysAttendance->count()); ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
        Quick Action
        <a href="<?php echo e(route('attendance.create')); ?>" class="btn btn-primary btn-sm"><i class="bi bi-clipboard-check me-1"></i>Take Attendance</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-semibold">Today's Sessions</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Class</th><th>Subject</th><th>Period</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $todaysAttendance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($session->classRoom->name); ?> <?php echo e($session->classRoom->section); ?></td>
                        <td><?php echo e($session->subject->name ?? '-'); ?></td>
                        <td><?php echo e($session->period ?? '-'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="3" class="text-center text-muted py-3">No sessions taken yet today.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/sajulasyangtan/Desktop/attendance-system/resources/views/teacher/dashboard.blade.php ENDPATH**/ ?>