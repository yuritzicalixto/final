<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Schedule::command('reservations:expire')->dailyAt('00:05');


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Recordatorios de citas: cada día a las 6:00 PM
// (para recordar las citas de mañana)
Schedule::command('notifications:appointment-reminders')
    ->dailyAt('18:00')
    ->withoutOverlapping();

// Recordatorios de apartados: cada día a las 10:00 AM
Schedule::command('notifications:reservation-reminders')
    ->dailyAt('10:00')
    ->withoutOverlapping();
