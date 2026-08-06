<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
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

        $teachers = [
            [
                'name' => 'Jane Teacher',
                'email' => 'teacher@attendance.test',
                'employee_id' => 'EMP-0001',
                'designation' => 'Senior Teacher',
                'qualification' => 'M.Sc. Mathematics',
                'subject_codes' => ['MATH101', 'PHY101'],
                'class_section' => 'A',
            ],
            [
                'name' => 'Mark Educator',
                'email' => 'mark.teacher@attendance.test',
                'employee_id' => 'EMP-0002',
                'designation' => 'Assistant Teacher',
                'qualification' => 'M.A. English',
                'subject_codes' => ['ENG101', 'CS101'],
                'class_section' => 'B',
            ],
        ];

        foreach ($teachers as $t) {
            $user = User::firstOrCreate(
                ['email' => $t['email']],
                [
                    'name' => $t['name'],
                    'password' => Hash::make('password'),
                    'role_id' => $teacherRole->id,
                    'is_active' => true,
                ]
            );

            $teacher = Teacher::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'employee_id' => $t['employee_id'],
                    'department_id' => $science?->id,
                    'designation' => $t['designation'],
                    'joining_date' => now()->subYears(2),
                    'qualification' => $t['qualification'],
                ]
            );

            $teacher->subjects()->sync(Subject::whereIn('code', $t['subject_codes'])->pluck('id'));

            // Make this teacher the class teacher of their assigned section so they can
            // see it on their dashboard and take attendance for it.
            ClassRoom::where('name', 'Grade 10')
                ->where('section', $t['class_section'])
                ->update(['class_teacher_id' => $user->id]);
        }
    }
}
