<?php

require __DIR__.'/../vendor/autoload.php';

use Beste\Clock\FrozenClock;
use Beste\Clock\SystemClock;

$frozenClock = FrozenClock::withNowFrom(SystemClock::create());

printf("The clock is frozen at %s", $frozenClock->now()->format('Y-m-d H:i:s T (P)'));
sleep(1);
printf("It's one second later, but the clock is still frozen at %s", $frozenClock->now()->format('Y-m-d H:i:s T (P)'));

$frozenClock->setTo($frozenClock->now()->modify('-5 minutes'));
printf("%s", $frozenClock->now()->format('Y-m-d H:i:s T (P)'));

$frozenUTC = FrozenClock::fromUTC();
printf("Now (UTC) was frozen just now™ to %s", $frozenUTC->now()->format('Y-m-d H:i:s T (P)'));
