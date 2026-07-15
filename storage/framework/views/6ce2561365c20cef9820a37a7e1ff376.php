<?php $__env->startSection('title', 'Student Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-muted small mb-2">Attendance Percentage</div>
                <div class="display-6 fw-bold <?php echo e($percentage >= 75 ? 'text-success' : 'text-danger'); ?>"><?php echo e($percentage); ?>%</div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Class</div>
                <div class="fs-5 fw-semibold"><?php echo e($student->classRoom->name ?? 'Not assigned'); ?> <?php echo e($student->classRoom->section ?? ''); ?></div>
                <div class="text-muted small mt-2">Roll Number</div>
                <div class="fs-6"><?php echo e($student->roll_number); ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-semibold">Attendance History</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Date</th><th>Subject</th><th>Class</th><th>Status</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($detail->attendance->date->format('M d, Y')); ?></td>
                        <td><?php echo e($detail->attendance->subject->name ?? '-'); ?></td>
                        <td><?php echo e($detail->attendance->classRoom->name ?? '-'); ?></td>
                        <td>
                            <?php
                                $badge = match($detail->status) {
                                    'present' => 'success', 'late' => 'warning',
                                    'absent' => 'danger', 'leave' => 'secondary', default => 'info'
                                };
                            ?>
                            <span class="badge bg-<?php echo e($badge); ?>"><?php echo e(ucfirst($detail->status)); ?></span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="4" class="text-center text-muted py-3">No attendance records yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/sajulasyangtan/Desktop/attendance-system/resources/views/student/dashboard.blade.php ENDPATH**/ ?>