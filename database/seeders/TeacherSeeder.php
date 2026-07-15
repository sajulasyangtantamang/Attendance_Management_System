<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Role;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teacherRole = Role::where('name', 'teacher')->first();
        $science = Department::where('code', 'SCI')->first();

        $user = User::firstOrCreate(
            ['email' => 'teacher@attendance.test'],
            [
                'name' => 'Jane Teacher',
                'password' => Hash::make('password'),
                'role_id' => $teacherRole->id,
            ]
        );

        $teacher = Teacher::firstOrCreate(
            ['user_id' => $user->id],
            [
                'employee_id' => 'EMP-0001',
                'department_id' => $science?->id,
                'designation' => 'Senior Teacher',
                'joining_date' => now(),
                'qualification' => 'M.Sc.',
            ]
        );

        $teacher->subjects()->sync(Subject::pluck('id')->take(2));
    }
}
