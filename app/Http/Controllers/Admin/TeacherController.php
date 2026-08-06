<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Role;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use App\Notifications\AccountCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::with(['user', 'department']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"));
        }

        $teachers = $query->latest()->paginate(15)->withQueryString();

        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        $departments = Department::all();
        $subjects = Subject::all();

        return view('admin.teachers.create', compact('departments', 'subjects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'employee_id' => 'required|string|unique:teachers,employee_id',
            'department_id' => 'nullable|exists:departments,id',
            'designation' => 'nullable|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        $defaultPassword = config('accounts.default_password');

        $user = DB::transaction(function () use ($data, $defaultPassword) {
            $teacherRole = Role::firstOrCreate(['name' => 'teacher'], ['label' => 'Teacher']);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($defaultPassword),
                'role_id' => $teacherRole->id,
                'must_change_password' => true,
            ]);

            $teacher = Teacher::create([
                'user_id' => $user->id,
                'employee_id' => $data['employee_id'],
                'department_id' => $data['department_id'] ?? null,
                'designation' => $data['designation'] ?? null,
                'qualification' => $data['qualification'] ?? null,
                'joining_date' => now(),
            ]);

            $teacher->subjects()->sync($data['subjects'] ?? []);

            return $user;
        });

        try {
            $user->notify(new AccountCreated('Teacher', $defaultPassword));
        } catch (\Throwable $e) {
            Log::error('Failed to send teacher account-creation email: '.$e->getMessage());

            return redirect()->route('admin.teachers.index')
                ->with('warning', 'Teacher added, but the account-creation email could not be sent.');
        }

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher added successfully.');
    }

    public function edit(Teacher $teacher)
    {
        $teacher->load(['user', 'subjects']);
        $departments = Department::all();
        $subjects = Subject::all();

        return view('admin.teachers.edit', compact('teacher', 'departments', 'subjects'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$teacher->user_id,
            'employee_id' => 'required|string|unique:teachers,employee_id,'.$teacher->id,
            'department_id' => 'nullable|exists:departments,id',
            'designation' => 'nullable|string|max:255',
            'qualification' => 'nullable|string|max:255',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        DB::transaction(function () use ($data, $teacher) {
            $teacher->user->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            $teacher->update([
                'employee_id' => $data['employee_id'],
                'department_id' => $data['department_id'] ?? null,
                'designation' => $data['designation'] ?? null,
                'qualification' => $data['qualification'] ?? null,
            ]);

            $teacher->subjects()->sync($data['subjects'] ?? []);
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        DB::transaction(function () use ($teacher) {
            $teacher->delete();
            $teacher->user()->delete();
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher removed successfully.');
    }
}
