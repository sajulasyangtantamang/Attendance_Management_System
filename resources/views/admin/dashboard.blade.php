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

{{-- Chart filters: one row, scopes every chart below it (see interaction rules). --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form class="row g-3 align-items-end" onsubmit="return false;">
            <div class="col-sm-4 col-md-3">
                <label for="monthFilter" class="form-label small text-muted mb-1">Month</label>
                <input type="month" id="monthFilter" class="form-control" value="{{ now()->format('Y-m') }}">
            </div>
            <div class="col-sm-5 col-md-4">
                <label for="teacherFilter" class="form-label small text-muted mb-1">Teacher</label>
                <select id="teacherFilter" class="form-select">
                    <option value="">All Teachers</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->user_id }}">{{ $teacher->user->name }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

<div id="chartsWrap" class="row g-3 mb-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold">Attendance Trend</div>
            <div class="card-body">
                <div style="height: 280px; position: relative;">
                    <canvas id="trendChart"></canvas>
                    <div id="trendChartEmpty" class="d-none text-center text-muted small position-absolute top-50 start-50 translate-middle">
                        No attendance sessions recorded for this month.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold">Status Breakdown</div>
            <div class="card-body">
                <div style="height: 280px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Attendance by Class</div>
            <div class="card-body">
                <div style="height: 280px; position: relative;">
                    <canvas id="classChart"></canvas>
                    <div id="classChartEmpty" class="d-none text-center text-muted small position-absolute top-50 start-50 translate-middle">
                        No attendance sessions recorded for this month.
                    </div>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script src="{{ asset('js/dashboard-charts.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var trendCanvas = document.getElementById('trendChart');
    var classCanvas = document.getElementById('classChart');

    var trendChart = DashboardCharts.createTrendChart(trendCanvas, 'Attendance %');
    var statusChart = DashboardCharts.createStatusChart(document.getElementById('statusChart'));
    var classChart = DashboardCharts.createComparisonChart(classCanvas);

    var monthFilter = document.getElementById('monthFilter');
    var teacherFilter = document.getElementById('teacherFilter');
    var chartsWrap = document.getElementById('chartsWrap');

    function load() {
        DashboardCharts.setLoading(chartsWrap, true);

        DashboardCharts.fetchDashboardData("{{ route('admin.dashboard.chart-data') }}", {
            month: monthFilter.value,
            teacher_id: teacherFilter.value,
        }).then(function (data) {
            trendChart.data.labels = data.trend.labels;
            trendChart.data.datasets[0].data = data.trend.data;
            trendChart.update();
            DashboardCharts.toggleEmptyState(trendCanvas, data.trend.labels.length === 0);

            statusChart.data.datasets[0].data = data.status.data;
            statusChart.update();

            classChart.data.labels = data.classes.labels;
            classChart.data.datasets[0].data = data.classes.data;
            classChart.update();
            DashboardCharts.toggleEmptyState(classCanvas, data.classes.labels.length === 0);
        }).catch(function () {
            // Leave the previous render in place rather than blanking the charts.
        }).finally(function () {
            DashboardCharts.setLoading(chartsWrap, false);
        });
    }

    monthFilter.addEventListener('change', load);
    teacherFilter.addEventListener('change', load);

    load();
});
</script>
@endpush
@endsection
