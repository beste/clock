<?php

require __DIR__.'/../vendor/autoload.php';

use Beste\Clock\FrozenClock;
use Beste\Clock\MinuteClock;

$frozenClock = FrozenClock::at(new DateTimeImmutable('01:23:45'));
$clock = MinuteClock::wrapping($frozenClock);

printf("For %s, the minute clock returns %s\n",
    $frozenClock->now()->format('H:i:s'),
    $clock->now()->format('H:i:s')
);

$frozenClock->setTo($frozenClock->now()->modify('+10 seconds')); // 01:23:55

printf("For %s, the minute clock still returns %s\n",
    $frozenClock->now()->format('H:i:s'),
    $clock->now()->format('H:i:s')
);
