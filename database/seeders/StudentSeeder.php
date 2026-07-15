<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
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
        $class = ClassRoom::first();

        $students = [
            ['name' => 'Alice Student', 'email' => 'alice@attendance.test', 'roll' => 'R-1001'],
            ['name' => 'Bob Student', 'email' => 'bob@attendance.test', 'roll' => 'R-1002'],
        ];

        foreach ($students as $s) {
            $user = User::firstOrCreate(
                ['email' => $s['email']],
                [
                    'name' => $s['name'],
                    'password' => Hash::make('password'),
                    'role_id' => $studentRole->id,
                ]
            );

            Student::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'roll_number' => $s['roll'],
                    'class_id' => $class?->id,
                    'admission_date' => now(),
                ]
            );
        }
    }
}
