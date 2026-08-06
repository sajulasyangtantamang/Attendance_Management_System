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
        $commerce = Department::where('code', 'COM')->first();
        $arts = Department::where('code', 'ART')->first();

        $subjects = [
            ['name' => 'Mathematics', 'code' => 'MATH101', 'department_id' => $science?->id],
            ['name' => 'Physics', 'code' => 'PHY101', 'department_id' => $science?->id],
            ['name' => 'Chemistry', 'code' => 'CHEM101', 'department_id' => $science?->id],
            ['name' => 'Computer Science', 'code' => 'CS101', 'department_id' => $science?->id],
            ['name' => 'English', 'code' => 'ENG101', 'department_id' => null],
            ['name' => 'Accountancy', 'code' => 'ACC101', 'department_id' => $commerce?->id],
            ['name' => 'History', 'code' => 'HIST101', 'department_id' => $arts?->id],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(['code' => $subject['code']], $subject);
        }
    }
}
