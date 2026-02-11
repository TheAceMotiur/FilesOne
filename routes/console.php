<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:file-task')->everyMinute();