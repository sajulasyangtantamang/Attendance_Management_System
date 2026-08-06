/**
 * Shared Chart.js setup for the admin/teacher/student dashboards.
 *
 * Colors come from the project's validated data-viz palette:
 *   - trend line: sequential blue (single series, magnitude over time)
 *   - status bars: the reserved status ramp (good/warning/serious/critical),
 *     mapped onto present/late/leave/absent by severity
 *   - class comparison: the same sequential blue, flat (comparing magnitude,
 *     not identity)
 * Never edit these hexes ad hoc — they're picked to stay distinguishable
 * under color-blindness simulation and against the card surface.
 */
(function (window) {
    var COLORS = {
        sequential: '#2a78d6',
        sequentialFill: 'rgba(42, 120, 214, 0.10)',
        grid: '#e1e0d9',
        axis: '#c3c2b7',
        textMuted: '#898781',
        tooltipBg: '#0b0b0b',
        surface: '#fcfcfb',
        status: {
            present: '#0ca30c', // good
            late: '#fab219',    // warning
            leave: '#ec835a',   // serious
            absent: '#d03b3b',  // critical
        },
    };

    /** Fade a container while its data refetches, per the "refetch keeps the frame" rule. */
    function setLoading(el, loading) {
        if (!el) return;
        el.style.transition = 'opacity 150ms ease';
        el.style.opacity = loading ? '0.45' : '1';
    }

    function toggleEmptyState(canvas, isEmpty) {
        var emptyEl = document.getElementById(canvas.id + 'Empty');
        if (!emptyEl) return;
        emptyEl.classList.toggle('d-none', !isEmpty);
        canvas.classList.toggle('d-none', isEmpty);
    }

    function baseGridOptions() {
        return {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: COLORS.tooltipBg,
                    padding: 10,
                    cornerRadius: 6,
                    titleFont: { weight: '600' },
                },
            },
        };
    }

    function createTrendChart(canvas, label) {
        return new Chart(canvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: label || 'Attendance %',
                    data: [],
                    borderColor: COLORS.sequential,
                    backgroundColor: COLORS.sequentialFill,
                    borderWidth: 2,
                    pointRadius: 3,
                    pointBackgroundColor: COLORS.sequential,
                    pointBorderColor: COLORS.surface,
                    pointBorderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    spanGaps: true,
                }],
            },
            options: Object.assign(baseGridOptions(), {
                interaction: { mode: 'index', intersect: false },
                plugins: Object.assign(baseGridOptions().plugins, {
                    tooltip: Object.assign(baseGridOptions().plugins.tooltip, {
                        callbacks: {
                            label: function (ctx) {
                                return ctx.parsed.y === null ? 'No session that day' : ctx.parsed.y + '% present';
                            },
                        },
                    }),
                }),
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: { color: COLORS.grid },
                        ticks: { color: COLORS.textMuted, callback: function (v) { return v + '%'; } },
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: COLORS.textMuted, maxRotation: 0, autoSkip: true },
                    },
                },
            }),
        });
    }

    function createStatusChart(canvas) {
        return new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Present', 'Late', 'Leave', 'Absent'],
                datasets: [{
                    data: [0, 0, 0, 0],
                    backgroundColor: [COLORS.status.present, COLORS.status.late, COLORS.status.leave, COLORS.status.absent],
                    borderRadius: 4,
                    borderSkipped: false,
                    maxBarThickness: 56,
                }],
            },
            options: Object.assign(baseGridOptions(), {
                plugins: Object.assign(baseGridOptions().plugins, {
                    tooltip: Object.assign(baseGridOptions().plugins.tooltip, {
                        callbacks: {
                            label: function (ctx) { return ctx.parsed.y + ' student-session(s)'; },
                        },
                    }),
                }),
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: COLORS.textMuted, precision: 0 },
                        grid: { color: COLORS.grid },
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: COLORS.textMuted },
                    },
                },
            }),
        });
    }

    function createComparisonChart(canvas) {
        return new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: COLORS.sequential,
                    borderRadius: 4,
                    borderSkipped: false,
                    maxBarThickness: 40,
                }],
            },
            options: Object.assign(baseGridOptions(), {
                plugins: Object.assign(baseGridOptions().plugins, {
                    tooltip: Object.assign(baseGridOptions().plugins.tooltip, {
                        callbacks: {
                            label: function (ctx) { return ctx.parsed.y + '% attendance'; },
                        },
                    }),
                }),
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: { color: COLORS.grid },
                        ticks: { color: COLORS.textMuted, callback: function (v) { return v + '%'; } },
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: COLORS.textMuted },
                    },
                },
            }),
        });
    }

    function fetchDashboardData(url, params) {
        var qs = new URLSearchParams(params).toString();
        return fetch(url + (qs ? '?' + qs : ''), { headers: { Accept: 'application/json' } }).then(function (r) {
            if (!r.ok) throw new Error('Request failed with status ' + r.status);
            return r.json();
        });
    }

    window.DashboardCharts = {
        COLORS: COLORS,
        setLoading: setLoading,
        toggleEmptyState: toggleEmptyState,
        createTrendChart: createTrendChart,
        createStatusChart: createStatusChart,
        createComparisonChart: createComparisonChart,
        fetchDashboardData: fetchDashboardData,
    };
})(window);
