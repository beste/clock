<?php

namespace Psr\Clock;

if (class_exists('\Psr\Clock\ClockInterface')) {
    return;
}

use DateTimeImmutable;

interface ClockInterface
{
    /**
     * Returns the current time as a DateTimeImmutable Object
     */
    public function now(): DateTimeImmutable;
}
