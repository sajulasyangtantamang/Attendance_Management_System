<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
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
}
