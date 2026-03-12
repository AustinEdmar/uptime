<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

use Spatie\ShortSchedule\Facades\ShortSchedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// this command will run every second
ShortSchedule::command('checks:perform')->everySecond();

