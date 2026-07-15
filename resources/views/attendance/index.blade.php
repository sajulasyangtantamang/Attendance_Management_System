@extends('layouts.master')
@section('title', 'Attendance Records')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Attendance Records</h5>
    @auth
        @if (auth()->user()->isTeacher() || auth()->user()->isAdmin())
            <a href="{{ route('attendance.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Take Attendance</a>
        @endif
    @endauth
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-5">
                <select name="class_id" class="form-select">
                    <option value="">All Classes</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" @selected(request('class_id') == $class->id)>{{ $class->name }} {{ $class->section }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-secondary w-100"><i class="bi bi-funnel me-1"></i>Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Date</th><th>Class</th><th>Subject</th><th>Teacher</th><th>Students Marked</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse ($sessions as $session)
                    <tr>
                        <td>{{ $session->date->format('M d, Y') }}</td>
                        <td>{{ $session->classRoom->name }} {{ $session->classRoom->section }}</td>
                        <td>{{ $session->subject->name ?? '-' }}</td>
                        <td>{{ $session->teacher->name }}</td>
                        <td>{{ $session->details()->count() }}</td>
                        <td>
                            @if (auth()->user()->isAdmin())
                                <form action="{{ route('attendance.destroy', $session) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this session?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-3">No attendance sessions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $sessions->links() }}</div>
</div>
@endsection
