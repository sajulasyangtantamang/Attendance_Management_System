<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Form to select class/subject/date before taking attendance.
     */
    public function create(Request $request)
    {
        $user = $request->user();

        $classes = $user->isAdmin()
            ? ClassRoom::all()
            : ClassRoom::where('class_teacher_id', $user->id)->get();

        $subjects = $user->isAdmin()
            ? Subject::all()
            : ($user->teacher?->subjects ?? collect());

        return view('attendance.select', compact('classes', 'subjects'));
    }

    /**
     * Load (or create) the roster for the chosen session so statuses can be marked.
     */
    public function take(Request $request)
    {
        $data = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'date' => 'required|date',
            'period' => 'nullable|string|max:50',
        ]);

        $class = ClassRoom::with('students.user')->findOrFail($data['class_id']);

        $attendance = Attendance::firstOrCreate(
            [
                'class_id' => $data['class_id'],
                'subject_id' => $data['subject_id'] ?? null,
                'date' => $data['date'],
                'period' => $data['period'] ?? null,
            ],
            ['teacher_id' => $request->user()->id]
        );

        $existingStatuses = $attendance->details()->pluck('status', 'student_id');

        return view('attendance.take', compact('class', 'attendance', 'existingStatuses'));
    }

    /**
     * Persist marked statuses for every student in the roster.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'attendance_id' => 'required|exists:attendance,id',
            'statuses' => 'required|array',
            'statuses.*' => 'in:present,absent,leave,late,holiday',
            'remarks' => 'nullable|array',
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['statuses'] as $studentId => $status) {
                AttendanceDetail::updateOrCreate(
                    ['attendance_id' => $data['attendance_id'], 'student_id' => $studentId],
                    ['status' => $status, 'remarks' => $data['remarks'][$studentId] ?? null]
                );
            }
        });

        return redirect()->route('attendance.create')->with('success', 'Attendance saved successfully.');
    }

    /**
     * Admin: view every recorded attendance session with filters.
     */
    public function index(Request $request)
    {
        $query = Attendance::with(['classRoom', 'subject', 'teacher']);

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $sessions = $query->latest('date')->paginate(20)->withQueryString();
        $classes = ClassRoom::all();
        $teachers = $request->user()->isAdmin() ? Teacher::with('user')->get() : collect();

        return view('attendance.index', compact('sessions', 'classes', 'teachers'));
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return back()->with('success', 'Attendance session deleted.');
    }
}
