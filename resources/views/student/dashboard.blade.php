@extends('layouts.master')
@section('title', 'Student Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-muted small mb-2">Attendance Percentage</div>
                <div class="display-6 fw-bold {{ $percentage >= 75 ? 'text-success' : 'text-danger' }}">{{ $percentage }}%</div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Class</div>
                <div class="fs-5 fw-semibold">{{ $student->classRoom->name ?? 'Not assigned' }} {{ $student->classRoom->section ?? '' }}</div>
                <div class="text-muted small mt-2">Roll Number</div>
                <div class="fs-6">{{ $student->roll_number }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-semibold">Attendance History</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Date</th><th>Subject</th><th>Class</th><th>Status</th></tr></thead>
            <tbody>
                @forelse ($history as $detail)
                    <tr>
                        <td>{{ $detail->attendance->date->format('M d, Y') }}</td>
                        <td>{{ $detail->attendance->subject->name ?? '-' }}</td>
                        <td>{{ $detail->attendance->classRoom->name ?? '-' }}</td>
                        <td>
                            @php
                                $badge = match($detail->status) {
                                    'present' => 'success', 'late' => 'warning',
                                    'absent' => 'danger', 'leave' => 'secondary', default => 'info'
                                };
                            @endphp
                            <span class="badge bg-{{ $badge }}">{{ ucfirst($detail->status) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">No attendance records yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
