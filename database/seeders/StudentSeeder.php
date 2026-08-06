<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\Department;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $studentRole = Role::where('name', 'student')->first();
        $science = Department::where('code', 'SCI')->first();

        $classA = ClassRoom::where('name', 'Grade 10')->where('section', 'A')->first();
        $classB = ClassRoom::where('name', 'Grade 10')->where('section', 'B')->first();

        $students = [
            ['name' => 'Alice Sharma', 'email' => 'alice@attendance.test', 'roll' => 'R-1001', 'class' => $classA, 'dob' => '2009-03-14', 'guardian' => 'Ram Sharma', 'phone' => '9800000001'],
            ['name' => 'Bob Gurung', 'email' => 'bob@attendance.test', 'roll' => 'R-1002', 'class' => $classA, 'dob' => '2009-06-22', 'guardian' => 'Hari Gurung', 'phone' => '9800000002'],
            ['name' => 'Carol Thapa', 'email' => 'carol@attendance.test', 'roll' => 'R-1003', 'class' => $classA, 'dob' => '2009-01-09', 'guardian' => 'Sita Thapa', 'phone' => '9800000003'],
            ['name' => 'David Rai', 'email' => 'david@attendance.test', 'roll' => 'R-1004', 'class' => $classB, 'dob' => '2009-09-30', 'guardian' => 'Kumar Rai', 'phone' => '9800000004'],
            ['name' => 'Ema Karki', 'email' => 'ema@attendance.test', 'roll' => 'R-1005', 'class' => $classB, 'dob' => '2009-11-02', 'guardian' => 'Nabin Karki', 'phone' => '9800000005'],
            ['name' => 'Farhan Ali', 'email' => 'farhan@attendance.test', 'roll' => 'R-1006', 'class' => $classB, 'dob' => '2009-05-17', 'guardian' => 'Imran Ali', 'phone' => '9800000006'],
        ];

        foreach ($students as $s) {
            $user = User::firstOrCreate(
                ['email' => $s['email']],
                [
                    'name' => $s['name'],
                    'password' => Hash::make('password'),
                    'role_id' => $studentRole->id,
                    'is_active' => true,
                ]
            );

            Student::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'roll_number' => $s['roll'],
                    'class_id' => $s['class']?->id,
                    'department_id' => $science?->id,
                    'date_of_birth' => $s['dob'],
                    'guardian_name' => $s['guardian'],
                    'guardian_phone' => $s['phone'],
                    'address' => 'Kathmandu, Nepal',
                    'admission_date' => now()->subYear(),
                ]
            );
        }
    }
}
