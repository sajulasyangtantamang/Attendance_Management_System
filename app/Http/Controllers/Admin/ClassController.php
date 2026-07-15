<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ClassRoom;
use App\Models\Department;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $query = ClassRoom::with(['department', 'classTeacher']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $classes = $query->latest()->paginate(15)->withQueryString();

        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        $departments = Department::all();
        $academicYears = AcademicYear::all();
        $teachers = Teacher::with('user')->get();
        $subjects = Subject::all();

        return view('admin.classes.create', compact('departments', 'academicYears', 'teachers', 'subjects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:50',
            'department_id' => 'nullable|exists:departments,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'class_teacher_id' => 'nullable|exists:users,id',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        $class = ClassRoom::create($data);
        $class->subjects()->sync($data['subjects'] ?? []);

        return redirect()->route('admin.classes.index')->with('success', 'Class created successfully.');
    }

    public function edit(ClassRoom $class)
    {
        $class->load('subjects');
        $departments = Department::all();
        $academicYears = AcademicYear::all();
        $teachers = Teacher::with('user')->get();
        $subjects = Subject::all();

        return view('admin.classes.edit', compact('class', 'departments', 'academicYears', 'teachers', 'subjects'));
    }

    public function update(Request $request, ClassRoom $class)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:50',
            'department_id' => 'nullable|exists:departments,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'class_teacher_id' => 'nullable|exists:users,id',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        $class->update($data);
        $class->subjects()->sync($data['subjects'] ?? []);

        return redirect()->route('admin.classes.index')->with('success', 'Class updated successfully.');
    }

    public function destroy(ClassRoom $class)
    {
        $class->delete();

        return redirect()->route('admin.classes.index')->with('success', 'Class deleted successfully.');
    }
}
