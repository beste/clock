<?php

require __DIR__.'/../vendor/autoload.php';

use Beste\Clock\FrozenClock;
use Beste\Clock\SystemClock;

$frozenClock = FrozenClock::withNowFrom(SystemClock::create());

printf("\nThe clock is frozen at %s", $frozenClock->now()->format('Y-m-d H:i:s T (P)'));
printf("\nLet's wait a second…");
sleep(1);
printf("\nIt's one second later, but the clock is still frozen at %s", $frozenClock->now()->format('Y-m-d H:i:s T (P)'));

$frozenClock->setTo($frozenClock->now()->modify('-5 minutes'));
printf("\nAfter turning back the clock 5 minutes, it's %s", $frozenClock->now()->format('Y-m-d H:i:s T (P)'));
