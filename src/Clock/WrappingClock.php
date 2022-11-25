<?php

declare(strict_types=1);

namespace Beste\Clock;

use Beste\Clock;
use DateTimeImmutable;
use StellaMaris\Clock\ClockInterface;

final class WrappingClock implements Clock
{
    private ClockInterface $wrappedClock;

    private function __construct(ClockInterface $wrappedClock)
    {
        $this->wrappedClock = $wrappedClock;
    }

    public static function wrapping(object $clock): self
    {
        if ($clock instanceof ClockInterface) {
            return new self($clock);
        }

        if (!method_exists($clock, 'now')) {
            throw new \InvalidArgumentException('$clock must implement StellaMaris\Clock\ClockInterface or have a now() method');
        }

        if (!($clock->now() instanceof DateTimeImmutable)) {
            throw new \InvalidArgumentException('$clock->now() must return a DateTimeImmutable');
        }

        $wrappedClock = new class($clock) implements ClockInterface {
            private object $clock;

            public function __construct(object $clock)
            {
                $this->clock = $clock;
            }

            public function now(): \DateTimeImmutable
            {
                assert(method_exists($this->clock, 'now'));

                $now = $this->clock->now();

                assert($now instanceof \DateTimeImmutable);

                return $now;
            }
        };

        return new self($wrappedClock);
    }

    public function now(): DateTimeImmutable
    {
        return $this->wrappedClock->now();
    }
}
