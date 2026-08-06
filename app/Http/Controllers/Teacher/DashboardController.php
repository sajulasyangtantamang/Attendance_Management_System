<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassRoom;
use App\Support\AttendanceChartData;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $teacherId = $request->user()->id;

        $todaysClasses = ClassRoom::where('class_teacher_id', $teacherId)->get();

        $assignedSubjects = $request->user()->teacher?->subjects ?? collect();

        $todaysAttendance = Attendance::with(['classRoom', 'subject'])
            ->where('teacher_id', $teacherId)
            ->whereDate('date', Carbon::today())
            ->get();

        return view('teacher.dashboard', compact('todaysClasses', 'assignedSubjects', 'todaysAttendance'));
    }

    /**
     * JSON feed behind the dashboard's Chart.js widgets, scoped to sessions
     * this teacher personally took and sliced by month.
     */
    public function chartData(Request $request)
    {
        $request->validate([
            'month' => 'nullable|date_format:Y-m',
        ]);

        [$start, $end] = AttendanceChartData::resolveMonthRange($request->input('month'));

        $sessions = Attendance::with(['details', 'classRoom'])
            ->where('teacher_id', $request->user()->id)
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->get();

        return response()->json([
            'trend' => AttendanceChartData::dailyTrend($sessions),
            'status' => AttendanceChartData::statusBreakdown($sessions),
        ]);
    }
}
