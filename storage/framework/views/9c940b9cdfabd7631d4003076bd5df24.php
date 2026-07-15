<?php $__env->startSection('title', 'Edit Student'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h5 class="mb-3">Edit Student</h5>
        <form method="POST" action="<?php echo e(route('admin.students.update', $student)); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $student->user->name)); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $student->user->email)); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Roll Number</label>
                    <input type="text" name="roll_number" class="form-control" value="<?php echo e(old('roll_number', $student->roll_number)); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Class</label>
                    <select name="class_id" class="form-select">
                        <option value="">Select Class</option>
                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($class->id); ?>" <?php if($student->class_id == $class->id): echo 'selected'; endif; ?>><?php echo e($class->name); ?> <?php echo e($class->section); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-select">
                        <option value="">Select Department</option>
                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($department->id); ?>" <?php if($student->department_id == $department->id): echo 'selected'; endif; ?>><?php echo e($department->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-control" value="<?php echo e(old('date_of_birth', $student->date_of_birth?->format('Y-m-d'))); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Photo</label>
                    <input type="file" name="photo" class="form-control" accept="image/*">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Guardian Name</label>
                    <input type="text" name="guardian_name" class="form-control" value="<?php echo e(old('guardian_name', $student->guardian_name)); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Guardian Phone</label>
                    <input type="text" name="guardian_phone" class="form-control" value="<?php echo e(old('guardian_phone', $student->guardian_phone)); ?>">
                </div>
                <div class="col-12">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="2"><?php echo e(old('address', $student->address)); ?></textarea>
                </div>
            </div>
            <div class="mt-4">
                <button class="btn btn-primary">Update Student</button>
                <a href="<?php echo e(route('admin.students.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/sajulasyangtan/Desktop/attendance-system/resources/views/admin/students/edit.blade.php ENDPATH**/ ?>