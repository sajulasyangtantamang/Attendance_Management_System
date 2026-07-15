@extends('layouts.master')
@section('title', 'Take Attendance')

@section('content')
<div class="card border-0 shadow-sm" style="max-width:600px;">
    <div class="card-body">
        <h5 class="mb-3">Select Session</h5>
        <form method="POST" action="{{ route('attendance.take') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Class</label>
                <select name="class_id" class="form-select" required>
                    <option value="">Select Class</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }} {{ $class->section }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Subject</label>
                <select name="subject_id" class="form-select">
                    <option value="">Select Subject (optional)</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Period (optional)</label>
                <input type="text" name="period" class="form-control" placeholder="e.g. 1st Period">
            </div>
            <button class="btn btn-primary">Continue</button>
        </form>
    </div>
</div>
@endsection
