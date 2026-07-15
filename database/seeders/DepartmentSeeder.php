<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Science', 'code' => 'SCI', 'description' => 'Science department'],
            ['name' => 'Arts', 'code' => 'ART', 'description' => 'Arts department'],
            ['name' => 'Commerce', 'code' => 'COM', 'description' => 'Commerce department'],
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate(['code' => $department['code']], $department);
        }
    }
}
