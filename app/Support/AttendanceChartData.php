<?php

namespace App\Support;

use App\Models\Attendance;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Shared aggregation helpers behind the admin/teacher/student dashboard charts.
 * Every dashboard's "chart-data" endpoint boils down to slicing the same
 * attendance/attendance_details rows a different way, so the math lives here
 * once instead of being copy-pasted across three controllers.
 */
class AttendanceChartData
{
    /**
     * @return array{0: Carbon, 1: Carbon} [start of month, end of month]
     */
    public static function resolveMonthRange(?string $month): array
    {
        $start = $month
            ? Carbon::createFromFormat('Y-m', $month)->startOfMonth()
            : Carbon::now()->startOfMonth();

        return [$start, $start->copy()->endOfMonth()];
    }

    /**
     * Day-by-day attendance % across a set of sessions (each with `details` loaded).
     * A day with no session at all is simply absent from the series (Chart.js
     * `spanGaps` bridges it) rather than plotted as a misleading 0%.
     *
     * @param  Collection<int, Attendance>  $sessions
     * @return array{labels: array<int, string>, data: array<int, float|null>}
     */
    public static function dailyTrend(Collection $sessions): array
    {
        $labels = [];
        $data = [];

        foreach ($sessions->groupBy(fn (Attendance $s) => $s->date->toDateString())->sortKeys() as $date => $daySessions) {
            $details = $daySessions->flatMap->details;
            $total = $details->count();
            $present = $details->whereIn('status', ['present', 'late'])->count();

            $labels[] = Carbon::parse($date)->format('M d');
            $data[] = $total > 0 ? round($present / $total * 100, 2) : null;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Present/late/leave/absent counts across a set of sessions.
     *
     * @param  Collection<int, Attendance>  $sessions
     * @return array{labels: array<int, string>, data: array<int, int>}
     */
    public static function statusBreakdown(Collection $sessions): array
    {
        return self::statusBreakdownFromDetails($sessions->flatMap->details);
    }

    /**
     * Same breakdown, but starting from an already-flattened details collection
     * (used by the student dashboard, which has no "sessions" of its own).
     *
     * @param  Collection<int, \App\Models\AttendanceDetail>  $details
     * @return array{labels: array<int, string>, data: array<int, int>}
     */
    public static function statusBreakdownFromDetails(Collection $details): array
    {
        $statuses = ['present', 'late', 'leave', 'absent'];

        return [
            'labels' => ['Present', 'Late', 'Leave', 'Absent'],
            'data' => collect($statuses)
                ->map(fn (string $status) => $details->where('status', $status)->count())
                ->values()
                ->all(),
        ];
    }

    /**
     * Attendance % per class across a set of sessions (each with `details` and
     * `classRoom` loaded). Used by the admin dashboard's class comparison chart.
     *
     * @param  Collection<int, Attendance>  $sessions
     * @return array{labels: array<int, string>, data: array<int, float>}
     */
    public static function classBreakdown(Collection $sessions): array
    {
        $labels = [];
        $data = [];

        foreach ($sessions->groupBy('class_id') as $classSessions) {
            $details = $classSessions->flatMap->details;
            $total = $details->count();
            $present = $details->whereIn('status', ['present', 'late'])->count();
            $classRoom = $classSessions->first()->classRoom;

            $labels[] = trim(($classRoom->name ?? 'Unknown').' '.($classRoom->section ?? ''));
            $data[] = $total > 0 ? round($present / $total * 100, 2) : 0;
        }

        return ['labels' => $labels, 'data' => $data];
    }
}
