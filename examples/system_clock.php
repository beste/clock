<?php

require __DIR__.'/../vendor/autoload.php';

use Beste\Clock\SystemClock;

$clock = SystemClock::create();

printf("On your system, the current date and time is %s\n", $clock->now()->format('Y-m-d H:i:s T (P)'));

date_default_timezone_set('America/Los_Angeles');

printf("Now it's %s\n", $clock->now()->format('Y-m-d H:i:s T (P)'));

date_default_timezone_set('Europe/Berlin');

printf("Now it's %s\n", $clock->now()->format('Y-m-d H:i:s T (P)'));
