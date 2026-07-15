<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\ClassRoom;
use App\Models\Department;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        $science = Department::where('code', 'SCI')->first();
        $year = AcademicYear::first();

        $classes = [
            ['name' => 'Grade 10', 'section' => 'A', 'department_id' => $science?->id, 'academic_year_id' => $year?->id],
            ['name' => 'Grade 10', 'section' => 'B', 'department_id' => $science?->id, 'academic_year_id' => $year?->id],
        ];

        foreach ($classes as $class) {
            ClassRoom::firstOrCreate(
                ['name' => $class['name'], 'section' => $class['section']],
                $class
            );
        }
    }
}
