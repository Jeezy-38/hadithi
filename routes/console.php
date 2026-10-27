<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Sambaza arifa ya Hadithi ya Siku kila asubuhi saa 12:00 asubuhi (06:00 EAT)
Schedule::command('hadith:notify-daily')->dailyAt('06:00');

// Futa ziara za zaidi ya siku 90 ili jedwali la page_views lisikue bila mwisho
Schedule::command('app:prune-page-views')->dailyAt('03:00');

