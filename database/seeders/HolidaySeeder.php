<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    public function run(): void
    {
        $holidays = [
            ['title' => 'Constitution Day', 'date' => '2025-09-19', 'description' => 'National holiday.'],
            ['title' => 'Dashain Festival', 'date' => '2025-10-02', 'description' => 'School closed for Dashain.'],
            ['title' => 'Winter Break', 'date' => '2025-12-25', 'description' => 'School closed for winter break.'],
            ['title' => 'New Year', 'date' => '2026-01-01', 'description' => 'New Year holiday.'],
        ];

        foreach ($holidays as $holiday) {
            Holiday::firstOrCreate(
                ['title' => $holiday['title'], 'date' => $holiday['date']],
                $holiday
            );
        }
    }
}
