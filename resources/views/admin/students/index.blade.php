@extends('layouts.master')
@section('title', 'Students')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Students</h5>
    <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Student</a>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search by name, email, roll number" value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="class_id" class="form-select">
                    <option value="">All Classes</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>{{ $class->name }} {{ $class->section }}</option>
                    @endforeach
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
                @forelse ($students as $student)
                    <tr>
                        <td><img src="{{ $student->user->photoUrl() }}" width="36" height="36" class="rounded-circle" style="object-fit:cover;"></td>
                        <td>{{ $student->user->name }}</td>
                        <td>{{ $student->user->email }}</td>
                        <td>{{ $student->roll_number }}</td>
                        <td>{{ $student->classRoom->name ?? '-' }} {{ $student->classRoom->section ?? '' }}</td>
                        <td>
                            <a href="{{ route('admin.students.show', $student) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this student?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-3">No students found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">
        {{ $students->links() }}
    </div>
</div>
@endsection
