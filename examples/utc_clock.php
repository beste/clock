<?php

require __DIR__.'/../vendor/autoload.php';

use Beste\Clock\UTCClock;

$clock = UTCClock::create();

$anotherTimeZone = 'Africa/Casablanca';

date_default_timezone_set($anotherTimeZone);

printf("The system time zone is %s.\n", $anotherTimeZone);
printf("The clock's time zone is %s.\n", $clock->now()->getTimezone()->getName());
