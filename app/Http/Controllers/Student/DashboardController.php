<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Support\AttendanceChartData;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;

        abort_if(! $student, 404, 'Student profile not found.');

        $percentage = $student->attendancePercentage();

        $history = $student->attendanceDetails()
            ->with(['attendance.subject', 'attendance.classRoom'])
            ->latest('created_at')
            ->take(20)
            ->get();

        return view('student.dashboard', compact('student', 'percentage', 'history'));
    }

    /**
     * JSON feed behind the dashboard's Chart.js widgets: this student's own
     * running attendance % through the month, plus their status breakdown.
     */
    public function chartData(Request $request)
    {
        $request->validate([
            'month' => 'nullable|date_format:Y-m',
        ]);

        $student = $request->user()->student;

        abort_if(! $student, 404, 'Student profile not found.');

        [$start, $end] = AttendanceChartData::resolveMonthRange($request->input('month'));

        $details = $student->attendanceDetails()
            ->whereHas('attendance', fn ($q) => $q->whereBetween('date', [$start->toDateString(), $end->toDateString()]))
            ->with('attendance')
            ->get()
            ->sortBy(fn ($detail) => $detail->attendance->date);

        $labels = [];
        $data = [];
        $runningTotal = 0;
        $runningPresent = 0;

        foreach ($details as $detail) {
            $runningTotal++;
            if (in_array($detail->status, ['present', 'late'], true)) {
                $runningPresent++;
            }

            $labels[] = $detail->attendance->date->format('M d');
            $data[] = round($runningPresent / $runningTotal * 100, 2);
        }

        return response()->json([
            'trend' => ['labels' => $labels, 'data' => $data],
            'status' => AttendanceChartData::statusBreakdownFromDetails($details),
        ]);
    }
}
