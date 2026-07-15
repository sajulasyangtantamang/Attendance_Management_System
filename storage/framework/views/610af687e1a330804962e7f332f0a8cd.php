<?php $__env->startSection('title', 'Students'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Students</h5>
    <a href="<?php echo e(route('admin.students.create')); ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Student</a>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search by name, email, roll number" value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-4">
                <select name="class_id" class="form-select">
                    <option value="">All Classes</option>
                    <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($class->id); ?>" <?php if(request('class_id') == $class->id): echo 'selected'; endif; ?>><?php echo e($class->name); ?> <?php echo e($class->section); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-secondary w-100"><i class="bi bi-search me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Photo</th><th>Name</th><th>Email</th><th>Roll No.</th><th>Class</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><img src="<?php echo e($student->user->photoUrl()); ?>" width="36" height="36" class="rounded-circle" style="object-fit:cover;"></td>
                        <td><?php echo e($student->user->name); ?></td>
                        <td><?php echo e($student->user->email); ?></td>
                        <td><?php echo e($student->roll_number); ?></td>
                        <td><?php echo e($student->classRoom->name ?? '-'); ?> <?php echo e($student->classRoom->section ?? ''); ?></td>
                        <td>
                            <a href="<?php echo e(route('admin.students.show', $student)); ?>" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                            <a href="<?php echo e(route('admin.students.edit', $student)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="<?php echo e(route('admin.students.destroy', $student)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this student?');">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center text-muted py-3">No students found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-body">
        <?php echo e($students->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/sajulasyangtan/Desktop/attendance-system/resources/views/admin/students/index.blade.php ENDPATH**/ ?>