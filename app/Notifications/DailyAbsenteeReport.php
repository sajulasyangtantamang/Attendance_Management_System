<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DailyAbsenteeReport extends Notification
{
    use Queueable;

    /**
     * @param  int  $sessionsTaken  Total attendance sessions recorded for the date.
     * @param  Collection<int, array{label: string, students: Collection<int, string>}>  $absenteeSessions  Only the sessions that had at least one absentee.
     */
    public function __construct(
        protected Carbon $date,
        protected int $sessionsTaken,
        protected Collection $absenteeSessions,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Daily Absentee Report — '.$this->date->format('d M Y'))
            ->greeting('Hello '.$notifiable->name.',')
            ->line('Here is the attendance absentee summary for '.$this->date->format('l, d M Y').'.');

        if ($this->sessionsTaken === 0) {
            return $mail->line('No attendance sessions were taken on this date.');
        }

        if ($this->absenteeSessions->isEmpty()) {
            return $mail->line('No absentees were recorded across any of the '.$this->sessionsTaken.' session(s) taken today. 🎉');
        }

        $totalAbsent = $this->absenteeSessions->sum(fn (array $session) => $session['students']->count());

        foreach ($this->absenteeSessions as $session) {
            $mail->line('**'.$session['label'].'** — '.$session['students']->count().' absent');

            foreach ($session['students'] as $student) {
                $mail->line('- '.$student);
            }
        }

        return $mail->line('**Total absentees today: '.$totalAbsent.'** across '.$this->sessionsTaken.' session(s) taken.');
    }
}
