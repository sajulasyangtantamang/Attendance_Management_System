<?php

namespace App\Support;

use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\Student;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Builds the per-student attendance % rows behind the PDF/Excel "Attendance
 * Summary" report and its on-screen table. A row with zero recorded sessions
 * gets a null percentage (shown as "N/A") rather than 0% — no data isn't the
 * same claim as poor attendance, and shouldn't be flagged the same way.
 */
class AttendanceSummaryReport
{
    public const THRESHOLD = 80.0;

    /**
     * @param  array<int, int>|null  $classIds  Restrict to these classes; null = every class.
     * @param  int|null  $teacherId  Restrict to sessions taken by this teacher; null = every session.
     * @return Collection<int, array{
     *     roll_number: string, name: string, class: string, total: int,
     *     present: int, late: int, leave: int, absent: int,
     *     percentage: float|null, below_threshold: bool,
     * }>
     */
    public static function build(Carbon $start, Carbon $end, ?array $classIds = null, ?int $teacherId = null): Collection
    {
        $students = Student::with(['user', 'classRoom'])
            ->when($classIds !== null, fn ($q) => $q->whereIn('class_id', $classIds))
            ->get();

        $attendanceIds = Attendance::whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->when($teacherId, fn ($q) => $q->where('teacher_id', $teacherId))
            ->pluck('id');

        $detailsByStudent = AttendanceDetail::whereIn('attendance_id', $attendanceIds)
            ->get()
            ->groupBy('student_id');

        return $students
            ->map(function (Student $student) use ($detailsByStudent) {
                $details = $detailsByStudent->get($student->id, collect());

                $total = $details->count();
                $present = $details->whereIn('status', ['present', 'late'])->count();
                $percentage = $total > 0 ? round($present / $total * 100, 2) : null;

                return [
                    'roll_number' => $student->roll_number,
                    'name' => $student->user->name ?? 'Unknown',
                    'class' => trim(($student->classRoom->name ?? '-').' '.($student->classRoom->section ?? '')),
                    'total' => $total,
                    'present' => $details->where('status', 'present')->count(),
                    'late' => $details->where('status', 'late')->count(),
                    'leave' => $details->where('status', 'leave')->count(),
                    'absent' => $details->where('status', 'absent')->count(),
                    'percentage' => $percentage,
                    'below_threshold' => $percentage !== null && $percentage < self::THRESHOLD,
                ];
            })
            ->sortBy('name')
            ->values();
    }
}
