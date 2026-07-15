<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassRoom;
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
}
