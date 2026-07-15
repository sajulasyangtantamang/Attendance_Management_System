@extends('layouts.master')
@section('title', 'Teacher Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">My Classes</div>
                <div class="fs-3 fw-bold">{{ $todaysClasses->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Assigned Subjects</div>
                <div class="fs-3 fw-bold">{{ $assignedSubjects->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Sessions Taken Today</div>
                <div class="fs-3 fw-bold">{{ $todaysAttendance->count() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
        Quick Action
        <a href="{{ route('attendance.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-clipboard-check me-1"></i>Take Attendance</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-semibold">Today's Sessions</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Class</th><th>Subject</th><th>Period</th></tr></thead>
            <tbody>
                @forelse ($todaysAttendance as $session)
                    <tr>
                        <td>{{ $session->classRoom->name }} {{ $session->classRoom->section }}</td>
                        <td>{{ $session->subject->name ?? '-' }}</td>
                        <td>{{ $session->period ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted py-3">No sessions taken yet today.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
