<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Emails every admin the day's absentee list, grouped by class/subject session.
// Runs at 5 PM Asia/Kathmandu regardless of the app's own (UTC) timezone.
Schedule::command('attendance:daily-absentee-report')
    ->dailyAt('17:00')
    ->timezone('Asia/Kathmandu')
    ->withoutOverlapping();
