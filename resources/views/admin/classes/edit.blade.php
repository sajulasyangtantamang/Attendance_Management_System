@extends('layouts.master')
@section('title', 'Edit Class')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h5 class="mb-3">Edit Class</h5>
        <form method="POST" action="{{ route('admin.classes.update', $class) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $class->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Section</label>
                    <input type="text" name="section" class="form-control" value="{{ old('section', $class->section) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-select">
                        <option value="">Select Department</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" @selected($class->department_id == $department->id)>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Academic Year</label>
                    <select name="academic_year_id" class="form-select">
                        <option value="">Select Academic Year</option>
                        @foreach ($academicYears as $year)
                            <option value="{{ $year->id }}" @selected($class->academic_year_id == $year->id)>{{ $year->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Class Teacher</label>
                    <select name="class_teacher_id" class="form-select">
                        <option value="">Select Teacher</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->user_id }}" @selected($class->class_teacher_id == $teacher->user_id)>{{ $teacher->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Subjects</label>
                    @php $assigned = $class->subjects->pluck('id')->toArray(); @endphp
                    <select name="subjects[]" class="form-select" multiple size="4">
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" @selected(in_array($subject->id, $assigned))>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button class="btn btn-primary">Update</button>
                <a href="{{ route('admin.classes.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

