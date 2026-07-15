@extends('layouts.master')
@section('title', 'Student Profile')

@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <img src="{{ $student->user->photoUrl() }}" class="rounded-circle mb-3" width="100" height="100" style="object-fit:cover;">
                <h5 class="mb-0">{{ $student->user->name }}</h5>
                <p class="text-muted">{{ $student->roll_number }}</p>
                <p class="fw-bold {{ $student->attendancePercentage() >= 75 ? 'text-success' : 'text-danger' }}">
                    {{ $student->attendancePercentage() }}% Attendance
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="mb-3">Details</h6>
                <p><strong>Email:</strong> {{ $student->user->email }}</p>
                <p><strong>Class:</strong> {{ $student->classRoom->name ?? '-' }} {{ $student->classRoom->section ?? '' }}</p>
                <p><strong>Department:</strong> {{ $student->department->name ?? '-' }}</p>
                <p><strong>Guardian:</strong> {{ $student->guardian_name ?? '-' }} ({{ $student->guardian_phone ?? '-' }})</p>
                <p><strong>Address:</strong> {{ $student->address ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mt-3">
    <div class="card-header bg-white fw-semibold">Attendance History</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="table-light"><tr><th>Date</th><th>Status</th></tr></thead>
            <tbody>
                @forelse ($student->attendanceDetails as $detail)
                    <tr>
                        <td>{{ $detail->attendance->date->format('M d, Y') }}</td>
                        <td>{{ ucfirst($detail->status) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="text-center text-muted py-3">No records yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
