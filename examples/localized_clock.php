<?php

require __DIR__.'/../vendor/autoload.php';

use Beste\Clock\LocalizedClock;

$berlin = LocalizedClock::in('Europe/Berlin');
$denver = LocalizedClock::in(new DateTimeZone('America/Denver'));

printf("Berlin: %s\n", $berlin->now()->format('Y-m-d H:i:s T (P)'));
printf("Denver: %s\n", $denver->now()->format('Y-m-d H:i:s T (P)'));
