<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalClasses = ClassRoom::count();
        $totalSubjects = Subject::count();

        $todayAttendanceIds = Attendance::whereDate('date', Carbon::today())->pluck('id');
        $todayTotal = AttendanceDetail::whereIn('attendance_id', $todayAttendanceIds)->count();
        $todayPresent = AttendanceDetail::whereIn('attendance_id', $todayAttendanceIds)
            ->whereIn('status', ['present', 'late'])->count();
        $todayPercentage = $todayTotal > 0 ? round(($todayPresent / $todayTotal) * 100, 2) : 0;

        $monthAttendanceIds = Attendance::whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)->pluck('id');
        $monthTotal = AttendanceDetail::whereIn('attendance_id', $monthAttendanceIds)->count();
        $monthPresent = AttendanceDetail::whereIn('attendance_id', $monthAttendanceIds)
            ->whereIn('status', ['present', 'late'])->count();
        $monthPercentage = $monthTotal > 0 ? round(($monthPresent / $monthTotal) * 100, 2) : 0;

        $recentAttendance = Attendance::with(['classRoom', 'subject', 'teacher'])
            ->latest('date')->take(8)->get();

        return view('admin.dashboard', compact(
            'totalStudents', 'totalTeachers', 'totalClasses', 'totalSubjects',
            'todayPercentage', 'monthPercentage', 'recentAttendance'
        ));
    }
}
