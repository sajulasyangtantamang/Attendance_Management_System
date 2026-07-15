@extends('layouts.master')
@section('title', 'Classes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Classes</h5>
    <a href="{{ route('admin.classes.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Class</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Name</th><th>Section</th><th>Department</th><th>Class Teacher</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse ($classes as $class)
                    <tr>
                        <td>{{ $class->name }}</td>
                        <td>{{ $class->section }}</td>
                        <td>{{ $class->department->name ?? '-' }}</td>
                        <td>{{ $class->classTeacher->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.classes.edit', $class) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.classes.destroy', $class) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this class?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">No classes found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $classes->links() }}</div>
</div>
@endsection
