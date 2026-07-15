@extends('layouts.master')
@section('title', 'Edit Teacher')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h5 class="mb-3">Edit Teacher</h5>
        <form method="POST" action="{{ route('admin.teachers.update', $teacher) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $teacher->user->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $teacher->user->email) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Employee ID</label>
                    <input type="text" name="employee_id" class="form-control" value="{{ old('employee_id', $teacher->employee_id) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-select">
                        <option value="">Select Department</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" @selected($teacher->department_id == $department->id)>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Designation</label>
                    <input type="text" name="designation" class="form-control" value="{{ old('designation', $teacher->designation) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Qualification</label>
                    <input type="text" name="qualification" class="form-control" value="{{ old('qualification', $teacher->qualification) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Assign Subjects</label>
                    @php $assigned = $teacher->subjects->pluck('id')->toArray(); @endphp
                    <select name="subjects[]" class="form-select" multiple size="4">
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" @selected(in_array($subject->id, $assigned))>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button class="btn btn-primary">Update Teacher</button>
                <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
