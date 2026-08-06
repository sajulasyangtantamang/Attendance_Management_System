<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
            DepartmentSeeder::class,
            AcademicYearSeeder::class,
            SubjectSeeder::class,
            ClassSeeder::class,
            TeacherSeeder::class,
            StudentSeeder::class,
            HolidaySeeder::class,
            AttendanceSeeder::class,
        ]);
    }
}
