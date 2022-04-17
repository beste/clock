<?php

declare(strict_types=1);

namespace Beste\Clock;

use Beste\Clock;
use DateTimeImmutable;
use DateTimeZone;
use StellaMaris\Clock\ClockInterface;

final class FrozenClock implements Clock
{
    private DateTimeImmutable $frozenAt;

    private function __construct(DateTimeImmutable $frozenAt)
    {
        $this->frozenAt = $frozenAt;
    }

    public static function at(DateTimeImmutable $time): self
    {
        return new self($time);
    }

    public static function withNowFrom(ClockInterface $clock): self
    {
        return new self($clock->now());
    }

    public static function fromUTC(): self
    {
        return new self(new DateTimeImmutable('now', new DateTimeZone('UTC')));
    }

    public function setTo(DateTimeImmutable $time): self
    {
        $this->frozenAt = $time;

        return $this;
    }

    public function now(): DateTimeImmutable
    {
        return clone $this->frozenAt;
    }
}
