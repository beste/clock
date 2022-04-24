<?php

require __DIR__.'/../vendor/autoload.php';

use Beste\Clock\WrappingClock;

// Create a frozen $now so that we can test the wrapping clock.
$now = new DateTimeImmutable('2012-04-24 12:00:00');

// Create an object that is NOT a clock, but has a now() method returning the frozen $now.
$clock = new class($now) {
    private \DateTimeImmutable $now;

    public function __construct(\DateTimeImmutable $now)
    {
        $this->now = $now;
    }

    public function now(): \DateTimeImmutable
    {
        return $this->now;
    }
};

// We can now wrap the object in a clock.
$wrappedClock = WrappingClock::wrapping($clock);

assert($now->format(DATE_ATOM) === $wrappedClock->now()->format(DATE_ATOM));
