@extends('layouts.master')
@section('title', 'Edit Subject')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h5 class="mb-3">Edit Subject</h5>
        <form method="POST" action="{{ route('admin.subjects.update', $subject) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $subject->name) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Code</label>
                <input type="text" name="code" class="form-control" value="{{ old('code', $subject->code) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Department</label>
                <select name="department_id" class="form-select">
                    <option value="">Select Department</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}" @selected($subject->department_id == $department->id)>{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-primary">Update</button>
            <a href="{{ route('admin.subjects.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
