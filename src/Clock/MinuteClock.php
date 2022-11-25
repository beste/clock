<?php

declare(strict_types=1);

namespace Beste\Clock;

use Beste\Clock;
use DateTimeImmutable;
use StellaMaris\Clock\ClockInterface;

final class MinuteClock implements Clock
{
    private ClockInterface $clock;

    private function __construct(ClockInterface $clock)
    {
        $this->clock = $clock;
    }

    public static function wrapping(ClockInterface $clock): self
    {
        return new self($clock);
    }

    public function now(): DateTimeImmutable
    {
        return $this->floor($this->clock->now());
    }

    private function floor(DateTimeImmutable $now): DateTimeImmutable
    {
        return $now->setTime(
            (int) $now->format('H'),
            (int) $now->format('i')
        );
    }
}
