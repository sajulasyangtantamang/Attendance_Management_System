<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $science = Department::where('code', 'SCI')->first();

        $subjects = [
            ['name' => 'Mathematics', 'code' => 'MATH101', 'department_id' => $science?->id],
            ['name' => 'Physics', 'code' => 'PHY101', 'department_id' => $science?->id],
            ['name' => 'English', 'code' => 'ENG101', 'department_id' => null],
            ['name' => 'Computer Science', 'code' => 'CS101', 'department_id' => $science?->id],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(['code' => $subject['code']], $subject);
        }
    }
}
