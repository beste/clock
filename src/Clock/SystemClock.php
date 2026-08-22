<?php

declare(strict_types=1);

namespace Beste\Clock;

use DateTimeImmutable;
use Psr\Clock\ClockInterface;

final class SystemClock implements ClockInterface
{
    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }

    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}
