<?php

declare(strict_types=1);

namespace Beste\Clock;

use DateTimeImmutable;
use DateTimeZone;
use Psr\Clock\ClockInterface;

final class UTCClock implements ClockInterface
{
    private DateTimeZone $timeZone;

    private function __construct()
    {
        $this->timeZone = new DateTimeZone('UTC');
    }

    public static function create(): self
    {
        return new self();
    }

    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', $this->timeZone);
    }
}
