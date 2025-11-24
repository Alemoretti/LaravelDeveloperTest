<?php

use App\Jobs\SyncAllSwapiDataJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule SWAPI data synchronization
Schedule::job(new SyncAllSwapiDataJob)
    ->dailyAt('02:00')
    ->withoutOverlapping()
    ->onOneServer()
    ->name('swapi-sync')
    ->description('Synchronize Star Wars data from SWAPI');
