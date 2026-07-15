<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Students</div>
                <div class="fs-3 fw-bold"><?php echo e($totalStudents); ?></div>
                <i class="bi bi-mortarboard text-primary"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Teachers</div>
                <div class="fs-3 fw-bold"><?php echo e($totalTeachers); ?></div>
                <i class="bi bi-person-badge text-success"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Classes</div>
                <div class="fs-3 fw-bold"><?php echo e($totalClasses); ?></div>
                <i class="bi bi-easel text-warning"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Subjects</div>
                <div class="fs-3 fw-bold"><?php echo e($totalSubjects); ?></div>
                <i class="bi bi-journal-bookmark text-danger"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small mb-2">Today's Attendance</div>
                <div class="progress" style="height: 24px;">
                    <div class="progress-bar bg-success" style="width: <?php echo e($todayPercentage); ?>%;"><?php echo e($todayPercentage); ?>%</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small mb-2">Monthly Attendance</div>
                <div class="progress" style="height: 24px;">
                    <div class="progress-bar bg-info" style="width: <?php echo e($monthPercentage); ?>%;"><?php echo e($monthPercentage); ?>%</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-semibold">Recent Attendance Sessions</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Date</th><th>Class</th><th>Subject</th><th>Teacher</th></tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $recentAttendance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($session->date->format('M d, Y')); ?></td>
                        <td><?php echo e($session->classRoom->name ?? '-'); ?> <?php echo e($session->classRoom->section ?? ''); ?></td>
                        <td><?php echo e($session->subject->name ?? '-'); ?></td>
                        <td><?php echo e($session->teacher->name ?? '-'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="4" class="text-center text-muted py-3">No attendance sessions recorded yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/sajulasyangtan/Desktop/attendance-system/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>