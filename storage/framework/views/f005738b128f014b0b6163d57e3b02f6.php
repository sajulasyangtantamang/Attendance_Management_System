<?php $__env->startSection('title', 'Edit Class'); ?>

<?php $__env->startSection('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h5 class="mb-3">Edit Class</h5>
        <form method="POST" action="<?php echo e(route('admin.classes.update', $class)); ?>">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $class->name)); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Section</label>
                    <input type="text" name="section" class="form-control" value="<?php echo e(old('section', $class->section)); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-select">
                        <option value="">Select Department</option>
                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($department->id); ?>" <?php if($class->department_id == $department->id): echo 'selected'; endif; ?>><?php echo e($department->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Academic Year</label>
                    <select name="academic_year_id" class="form-select">
                        <option value="">Select Academic Year</option>
                        <?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($year->id); ?>" <?php if($class->academic_year_id == $year->id): echo 'selected'; endif; ?>><?php echo e($year->title); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Class Teacher</label>
                    <select name="class_teacher_id" class="form-select">
                        <option value="">Select Teacher</option>
                        <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($teacher->user_id); ?>" <?php if($class->class_teacher_id == $teacher->user_id): echo 'selected'; endif; ?>><?php echo e($teacher->user->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Subjects</label>
                    <?php $assigned = $class->subjects->pluck('id')->toArray(); ?>
                    <select name="subjects[]" class="form-select" multiple size="4">
                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($subject->id); ?>" <?php if(in_array($subject->id, $assigned)): echo 'selected'; endif; ?>><?php echo e($subject->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button class="btn btn-primary">Update</button>
                <a href="<?php echo e(route('admin.classes.index')); ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/sajulasyangtan/Desktop/attendance-system/resources/views/admin/classes/edit.blade.php ENDPATH**/ ?>