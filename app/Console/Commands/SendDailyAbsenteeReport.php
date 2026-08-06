<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\User;
use App\Notifications\DailyAbsenteeReport;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendDailyAbsenteeReport extends Command
{
    /**
     * --date lets you re-run the report for a past day, e.g.
     * php artisan attendance:daily-absentee-report --date=2026-07-17
     */
    protected $signature = 'attendance:daily-absentee-report {--date= : The date to report on, defaults to today (Y-m-d)}';

    protected $description = 'Email every admin the list of students marked absent for each attendance session on the given date.';

    public function handle(): int
    {
        $date = $this->option('date') ? Carbon::parse($this->option('date')) : Carbon::today();

        $sessions = Attendance::with([
                'classRoom',
                'subject',
                'details' => fn ($query) => $query->where('status', 'absent')->with('student.user'),
            ])
            ->whereDate('date', $date)
            ->get();

        $absenteeSessions = $sessions
            ->map(function (Attendance $attendance) {
                $label = trim(
                    trim(($attendance->classRoom->name ?? 'Unknown Class').' '.($attendance->classRoom->section ?? ''))
                    .' — '.($attendance->subject->name ?? 'General')
                    .($attendance->period ? ' ('.$attendance->period.')' : '')
                );

                $students = $attendance->details->map(
                    fn ($detail) => ($detail->student->user->name ?? 'Unknown student').' ('.($detail->student->roll_number ?? '-').')'
                );

                return ['label' => $label, 'students' => $students];
            })
            ->filter(fn (array $session) => $session['students']->isNotEmpty())
            ->values();

        $admins = User::whereHas('role', fn ($query) => $query->where('name', 'admin'))->get();

        if ($admins->isEmpty()) {
            $this->warn('No admin users found; nothing to send.');

            return self::SUCCESS;
        }

        foreach ($admins as $admin) {
            try {
                $admin->notify(new DailyAbsenteeReport($date, $sessions->count(), $absenteeSessions));
            } catch (\Throwable $e) {
                $this->error("Failed to email {$admin->email}: {$e->getMessage()}");
                report($e);
            }
        }

        $this->info(sprintf(
            'Daily absentee report for %s sent to %d admin(s). Sessions: %d, sessions with absentees: %d.',
            $date->toDateString(),
            $admins->count(),
            $sessions->count(),
            $absenteeSessions->count()
        ));

        return self::SUCCESS;
    }
}
