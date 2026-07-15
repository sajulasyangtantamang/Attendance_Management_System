@extends('layouts.master')
@section('title', 'Admin Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Students</div>
                <div class="fs-3 fw-bold">{{ $totalStudents }}</div>
                <i class="bi bi-mortarboard text-primary"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Teachers</div>
                <div class="fs-3 fw-bold">{{ $totalTeachers }}</div>
                <i class="bi bi-person-badge text-success"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Classes</div>
                <div class="fs-3 fw-bold">{{ $totalClasses }}</div>
                <i class="bi bi-easel text-warning"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Subjects</div>
                <div class="fs-3 fw-bold">{{ $totalSubjects }}</div>
                <i class="bi bi-journal-bookmark text-danger"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small mb-2">Today's Attendance</div>
                <div class="progress" style="height: 24px;">
                    <div class="progress-bar bg-success" style="width: {{ $todayPercentage }}%;">{{ $todayPercentage }}%</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted small mb-2">Monthly Attendance</div>
                <div class="progress" style="height: 24px;">
                    <div class="progress-bar bg-info" style="width: {{ $monthPercentage }}%;">{{ $monthPercentage }}%</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-semibold">Recent Attendance Sessions</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Date</th><th>Class</th><th>Subject</th><th>Teacher</th></tr>
            </thead>
            <tbody>
                @forelse ($recentAttendance as $session)
                    <tr>
                        <td>{{ $session->date->format('M d, Y') }}</td>
                        <td>{{ $session->classRoom->name ?? '-' }} {{ $session->classRoom->section ?? '' }}</td>
                        <td>{{ $session->subject->name ?? '-' }}</td>
                        <td>{{ $session->teacher->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">No attendance sessions recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
