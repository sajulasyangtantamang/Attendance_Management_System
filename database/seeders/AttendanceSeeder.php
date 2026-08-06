<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\ClassRoom;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Weighted pool so most days come out "present" but the demo data still
     * shows a realistic mix of absences, late arrivals, and leave.
     */
    private const STATUS_POOL = [
        'present', 'present', 'present', 'present', 'present',
        'late', 'absent', 'leave',
    ];

    public function run(): void
    {
        ClassRoom::with(['students', 'classTeacher.teacher.subjects'])
            ->get()
            ->each(function (ClassRoom $class) {
                if (! $class->classTeacher || $class->students->isEmpty()) {
                    return;
                }

                $subject = $class->classTeacher->teacher?->subjects->first();

                $date = Carbon::now()->subDays(20);
                $today = Carbon::now();

                while ($date->lte($today)) {
                    if (! $date->isWeekend()) {
                        $this->seedSession($class, $subject?->id, $date);
                    }
                    $date->addDay();
                }
            });
    }

    private function seedSession(ClassRoom $class, ?int $subjectId, Carbon $date): void
    {
        $attendance = Attendance::firstOrCreate(
            [
                'class_id' => $class->id,
                'subject_id' => $subjectId,
                'date' => $date->toDateString(),
                'period' => '1st Period',
            ],
            ['teacher_id' => $class->class_teacher_id]
        );

        foreach ($class->students as $student) {
            AttendanceDetail::firstOrCreate(
                ['attendance_id' => $attendance->id, 'student_id' => $student->id],
                ['status' => self::STATUS_POOL[array_rand(self::STATUS_POOL)]]
            );
        }
    }
}
