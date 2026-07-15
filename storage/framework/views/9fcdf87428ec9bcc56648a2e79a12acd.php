<?php $__env->startSection('title', 'Edit Teacher'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h5 class="mb-3">Edit Teacher</h5>
        <form method="POST" action="<?php echo e(route('admin.teachers.update', $teacher)); ?>">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $teacher->user->name)); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $teacher->user->email)); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Employee ID</label>
                    <input type="text" name="employee_id" class="form-control" value="<?php echo e(old('employee_id', $teacher->employee_id)); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-select">
                        <option value="">Select Department</option>
                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($department->id); ?>" <?php if($teacher->department_id == $department->id): echo 'selected'; endif; ?>><?php echo e($department->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Designation</label>
                    <input type="text" name="designation" class="form-control" value="<?php echo e(old('designation', $teacher->designation)); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Qualification</label>
                    <input type="text" name="qualification" class="form-control" value="<?php echo e(old('qualification', $teacher->qualification)); ?>">
                </div>
                <div class="col-12">
                    <label class="form-label">Assign Subjects</label>
                    <?php $assigned = $teacher->subjects->pluck('id')->toArray(); ?>
                    <select name="subjects[]" class="form-select" multiple size="4">
                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($subject->id); ?>" <?php if(in_array($subject->id, $assigned)): echo 'selected'; endif; ?>><?php echo e($subject->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button class="btn btn-primary">Update Teacher</button>
                <a href="<?php echo e(route('admin.teachers.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/sajulasyangtan/Desktop/attendance-system/resources/views/admin/teachers/edit.blade.php ENDPATH**/ ?>