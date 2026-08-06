<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Department;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use App\Notifications\AccountCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['user', 'classRoom', 'department']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"))
                ->orWhere('roll_number', 'like', "%{$search}%");
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        $students = $query->latest()->paginate(15)->withQueryString();
        $classes = ClassRoom::all();

        return view('admin.students.index', compact('students', 'classes'));
    }

    public function create()
    {
        $classes = ClassRoom::all();
        $departments = Department::all();

        return view('admin.students.create', compact('classes', 'departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'roll_number' => 'required|string|unique:students,roll_number',
            'class_id' => 'nullable|exists:classes,id',
            'department_id' => 'nullable|exists:departments,id',
            'date_of_birth' => 'nullable|date',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        $defaultPassword = config('accounts.default_password');

        $user = DB::transaction(function () use ($data, $request, $defaultPassword) {
            $studentRole = Role::firstOrCreate(['name' => 'student'], ['label' => 'Student']);

            $photoPath = $request->hasFile('photo')
                ? $request->file('photo')->store('photos/students', 'public')
                : null;

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($defaultPassword),
                'role_id' => $studentRole->id,
                'photo' => $photoPath,
                'must_change_password' => true,
            ]);

            Student::create([
                'user_id' => $user->id,
                'roll_number' => $data['roll_number'],
                'class_id' => $data['class_id'] ?? null,
                'department_id' => $data['department_id'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'guardian_name' => $data['guardian_name'] ?? null,
                'guardian_phone' => $data['guardian_phone'] ?? null,
                'address' => $data['address'] ?? null,
                'admission_date' => now(),
            ]);

            return $user;
        });

        try {
            $user->notify(new AccountCreated('Student', $defaultPassword));
        } catch (\Throwable $e) {
            Log::error('Failed to send student account-creation email: '.$e->getMessage());

            return redirect()->route('admin.students.index')
                ->with('warning', 'Student added, but the account-creation email could not be sent.');
        }

        return redirect()->route('admin.students.index')->with('success', 'Student added successfully.');
    }

    public function show(Student $student)
    {
        $student->load(['user', 'classRoom', 'department', 'attendanceDetails.attendance']);

        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $student->load('user');
        $classes = ClassRoom::all();
        $departments = Department::all();

        return view('admin.students.edit', compact('student', 'classes', 'departments'));
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$student->user_id,
            'roll_number' => 'required|string|unique:students,roll_number,'.$student->id,
            'class_id' => 'nullable|exists:classes,id',
            'department_id' => 'nullable|exists:departments,id',
            'date_of_birth' => 'nullable|date',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($data, $request, $student) {
            if ($request->hasFile('photo')) {
                if ($student->user->photo) {
                    Storage::disk('public')->delete($student->user->photo);
                }
                $data['photo'] = $request->file('photo')->store('photos/students', 'public');
            }

            $student->user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'photo' => $data['photo'] ?? $student->user->photo,
            ]);

            $student->update([
                'roll_number' => $data['roll_number'],
                'class_id' => $data['class_id'] ?? null,
                'department_id' => $data['department_id'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'guardian_name' => $data['guardian_name'] ?? null,
                'guardian_phone' => $data['guardian_phone'] ?? null,
                'address' => $data['address'] ?? null,
            ]);
        });

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        DB::transaction(function () use ($student) {
            $student->delete();
            $student->user()->delete();
        });

        return redirect()->route('admin.students.index')->with('success', 'Student removed successfully.');
    }
}
