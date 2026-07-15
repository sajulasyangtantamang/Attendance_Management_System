@extends('layouts.master')
@section('title', 'Mark Attendance')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">
        {{ $class->name }} {{ $class->section }} &mdash; {{ $attendance->date->format('M d, Y') }}
        @if ($attendance->period) ({{ $attendance->period }}) @endif
    </h5>
</div>

<form method="POST" action="{{ route('attendance.store') }}">
    @csrf
    <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Roll No.</th>
                        <th>Student</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($class->students as $student)
                        @php $current = $existingStatuses[$student->id] ?? 'present'; @endphp
                        <tr>
                            <td>{{ $student->roll_number }}</td>
                            <td>{{ $student->user->name }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    @foreach (['present' => 'success', 'absent' => 'danger', 'late' => 'warning', 'leave' => 'secondary', 'holiday' => 'info'] as $status => $color)
                                        <input type="radio" class="btn-check" name="statuses[{{ $student->id }}]" id="{{ $status }}_{{ $student->id }}" value="{{ $status }}" {{ $current === $status ? 'checked' : '' }}>
                                        <label class="btn btn-outline-{{ $color }}" for="{{ $status }}_{{ $student->id }}">{{ ucfirst($status) }}</label>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <input type="text" name="remarks[{{ $student->id }}]" class="form-control form-control-sm" placeholder="Optional remarks">
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No students enrolled in this class.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Save Attendance</button>
    </div>
</form>
@endsection
