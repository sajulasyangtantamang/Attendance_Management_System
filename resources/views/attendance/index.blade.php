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

{{-- Attendance Summary: per-student % for the month, exportable as PDF/Excel. --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span>Attendance Summary</span>
        <div class="d-flex gap-2">
            <a id="exportPdf" href="#" class="btn btn-outline-danger btn-sm"><i class="bi bi-file-earmark-pdf me-1"></i>Export PDF</a>
            <a id="exportExcel" href="#" class="btn btn-outline-success btn-sm"><i class="bi bi-file-earmark-excel me-1"></i>Export Excel</a>
        </div>
    </div>
    <div class="card-body">
        <form class="row g-3 align-items-end mb-3" onsubmit="return false;">
            <div class="col-sm-4 col-md-3">
                <label for="summaryMonth" class="form-label small text-muted mb-1">Month</label>
                <input type="month" id="summaryMonth" class="form-control" value="{{ now()->format('Y-m') }}">
            </div>
            @if (auth()->user()->isAdmin())
                <div class="col-sm-4 col-md-3">
                    <label for="summaryClass" class="form-label small text-muted mb-1">Class</label>
                    <select id="summaryClass" class="form-select">
                        <option value="">All Classes</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }} {{ $class->section }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-4 col-md-3">
                    <label for="summaryTeacher" class="form-label small text-muted mb-1">Teacher</label>
                    <select id="summaryTeacher" class="form-select">
                        <option value="">All Teachers</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->user_id }}">{{ $teacher->user->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </form>

        <div id="summaryWrap" class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Roll No</th><th>Name</th><th>Class</th>
                        <th class="text-end">Total</th><th class="text-end">Present</th><th class="text-end">Late</th>
                        <th class="text-end">Leave</th><th class="text-end">Absent</th><th class="text-end">Attendance %</th>
                    </tr>
                </thead>
                <tbody id="summaryBody">
                    <tr><td colspan="9" class="text-center text-muted py-3">Loading…</td></tr>
                </tbody>
            </table>
        </div>
        <div class="small text-muted mt-2">
            <span class="d-inline-block" style="width:10px;height:10px;background:#f8d7da;border:1px solid #dc3545;"></span>
            Below {{ \App\Support\AttendanceSummaryReport::THRESHOLD }}% attendance threshold
        </div>
    </div>
</div>

<h6 class="mb-3">Session Log</h6>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var monthFilter = document.getElementById('summaryMonth');
    var classFilter = document.getElementById('summaryClass');
    var teacherFilter = document.getElementById('summaryTeacher');
    var summaryWrap = document.getElementById('summaryWrap');
    var summaryBody = document.getElementById('summaryBody');
    var exportPdf = document.getElementById('exportPdf');
    var exportExcel = document.getElementById('exportExcel');

    function currentParams() {
        var params = { month: monthFilter.value };
        if (classFilter && classFilter.value) params.class_id = classFilter.value;
        if (teacherFilter && teacherFilter.value) params.teacher_id = teacherFilter.value;
        return params;
    }

    function buildUrl(base, params) {
        var qs = new URLSearchParams(params).toString();
        return base + (qs ? '?' + qs : '');
    }

    function td(text, alignEnd) {
        var cell = document.createElement('td');
        if (alignEnd) cell.className = 'text-end';
        cell.textContent = text; // untrusted data (names, roll numbers) — never innerHTML
        return cell;
    }

    function renderRows(rows) {
        summaryBody.innerHTML = '';

        if (rows.length === 0) {
            var emptyRow = document.createElement('tr');
            var emptyCell = document.createElement('td');
            emptyCell.colSpan = 9;
            emptyCell.className = 'text-center text-muted py-3';
            emptyCell.textContent = 'No students found for this filter.';
            emptyRow.appendChild(emptyCell);
            summaryBody.appendChild(emptyRow);
            return;
        }

        rows.forEach(function (row) {
            var tr = document.createElement('tr');
            if (row.below_threshold) tr.className = 'table-danger';

            tr.appendChild(td(row.roll_number));
            tr.appendChild(td(row.name));
            tr.appendChild(td(row.class));
            tr.appendChild(td(row.total, true));
            tr.appendChild(td(row.present, true));
            tr.appendChild(td(row.late, true));
            tr.appendChild(td(row.leave, true));
            tr.appendChild(td(row.absent, true));
            tr.appendChild(td(row.percentage === null ? 'N/A' : row.percentage + '%', true));

            summaryBody.appendChild(tr);
        });
    }

    function load() {
        var params = currentParams();

        exportPdf.href = buildUrl("{{ route('attendance.summary-pdf') }}", params);
        exportExcel.href = buildUrl("{{ route('attendance.summary-excel') }}", params);

        summaryWrap.style.transition = 'opacity 150ms ease';
        summaryWrap.style.opacity = '0.5';

        fetch(buildUrl("{{ route('attendance.summary-data') }}", params), { headers: { Accept: 'application/json' } })
            .then(function (r) {
                if (!r.ok) throw new Error('Request failed with status ' + r.status);
                return r.json();
            })
            .then(function (data) { renderRows(data.rows); })
            .catch(function () {
                // Leave the previous render in place rather than blanking the table.
            })
            .finally(function () {
                summaryWrap.style.opacity = '1';
            });
    }

    monthFilter.addEventListener('change', load);
    if (classFilter) classFilter.addEventListener('change', load);
    if (teacherFilter) teacherFilter.addEventListener('change', load);

    load();
});
</script>
@endpush
@endsection
