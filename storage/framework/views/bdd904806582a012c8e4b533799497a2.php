<?php $__env->startSection('title', 'Mark Attendance'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">
        <?php echo e($class->name); ?> <?php echo e($class->section); ?> &mdash; <?php echo e($attendance->date->format('M d, Y')); ?>

        <?php if($attendance->period): ?> (<?php echo e($attendance->period); ?>) <?php endif; ?>
    </h5>
</div>

<form method="POST" action="<?php echo e(route('attendance.store')); ?>">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="attendance_id" value="<?php echo e($attendance->id); ?>">

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Roll No.</th>
                        <th>Student</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $class->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $current = $existingStatuses[$student->id] ?? 'present'; ?>
                        <tr>
                            <td><?php echo e($student->roll_number); ?></td>
                            <td><?php echo e($student->user->name); ?></td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <?php $__currentLoopData = ['present' => 'success', 'absent' => 'danger', 'late' => 'warning', 'leave' => 'secondary', 'holiday' => 'info']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <input type="radio" class="btn-check" name="statuses[<?php echo e($student->id); ?>]" id="<?php echo e($status); ?>_<?php echo e($student->id); ?>" value="<?php echo e($status); ?>" <?php echo e($current === $status ? 'checked' : ''); ?>>
                                        <label class="btn btn-outline-<?php echo e($color); ?>" for="<?php echo e($status); ?>_<?php echo e($student->id); ?>"><?php echo e(ucfirst($status)); ?></label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="remarks[<?php echo e($student->id); ?>]" class="form-control form-control-sm" placeholder="Optional remarks">
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4" class="text-center text-muted py-3">No students enrolled in this class.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Save Attendance</button>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/sajulasyangtan/Desktop/attendance-system/resources/views/attendance/take.blade.php ENDPATH**/ ?>