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

{{-- Chart filter: scopes every chart below it. --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form class="row g-3 align-items-end" onsubmit="return false;">
            <div class="col-sm-4 col-md-3">
                <label for="monthFilter" class="form-label small text-muted mb-1">Month</label>
                <input type="month" id="monthFilter" class="form-control" value="{{ now()->format('Y-m') }}">
            </div>
        </form>
    </div>
</div>

<div id="chartsWrap" class="row g-3 mb-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold">My Attendance Trend</div>
            <div class="card-body">
                <div style="height: 280px; position: relative;">
                    <canvas id="trendChart"></canvas>
                    <div id="trendChartEmpty" class="d-none text-center text-muted small position-absolute top-50 start-50 translate-middle">
                        No sessions taken this month.
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script src="{{ asset('js/dashboard-charts.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var trendCanvas = document.getElementById('trendChart');

    var trendChart = DashboardCharts.createTrendChart(trendCanvas, 'Attendance %');
    var statusChart = DashboardCharts.createStatusChart(document.getElementById('statusChart'));

    var monthFilter = document.getElementById('monthFilter');
    var chartsWrap = document.getElementById('chartsWrap');

    function load() {
        DashboardCharts.setLoading(chartsWrap, true);

        DashboardCharts.fetchDashboardData("{{ route('teacher.dashboard.chart-data') }}", {
            month: monthFilter.value,
        }).then(function (data) {
            trendChart.data.labels = data.trend.labels;
            trendChart.data.datasets[0].data = data.trend.data;
            trendChart.update();
            DashboardCharts.toggleEmptyState(trendCanvas, data.trend.labels.length === 0);

            statusChart.data.datasets[0].data = data.status.data;
            statusChart.update();
        }).catch(function () {
            // Leave the previous render in place rather than blanking the charts.
        }).finally(function () {
            DashboardCharts.setLoading(chartsWrap, false);
        });
    }

    monthFilter.addEventListener('change', load);

    load();
});
</script>
@endpush
@endsection
