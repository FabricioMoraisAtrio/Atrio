<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command('atrio:notificacoes-diarias')->dailyAt('07:00');
Schedule::command('atrio:faturas-vencendo')->dailyAt('08:00');
Schedule::command('atrio:backup --keep=14')->dailyAt('03:00');
Schedule::command('queue:work --stop-when-empty --max-time=50 --tries=3')->everyFiveMinutes()->withoutOverlapping();
