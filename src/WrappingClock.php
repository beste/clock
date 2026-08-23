<?php

declare(strict_types=1);

namespace Beste\Clock;

use Closure;
use DateTimeImmutable;
use InvalidArgumentException;
use Psr\Clock\ClockInterface;
use ReflectionMethod;
use UnexpectedValueException;

final class WrappingClock implements ClockInterface
{
    private ClockInterface $wrappedClock;

    private function __construct(ClockInterface $wrappedClock)
    {
        $this->wrappedClock = $wrappedClock;
    }

    /**
     * @throws InvalidArgumentException when the given object doesn't behave like a clock.
     */
    public static function wrapping(object $clock): self
    {
        if ($clock instanceof ClockInterface) {
            return new self($clock);
        }

        if (!method_exists($clock, 'now')) {
            throw new InvalidArgumentException('$clock must implement Psr\Clock\ClockInterface or have a now() method');
        }

        $method = new ReflectionMethod($clock, 'now');

        if (!$method->isPublic() || $method->getNumberOfRequiredParameters() > 0) {
            throw new InvalidArgumentException('$clock->now() must be public and accept no required parameters');
        }

        $wrappedClock = new class($method->getClosure($clock)) implements ClockInterface {
            private Closure $now;

            public function __construct(Closure $now)
            {
                $this->now = $now;
            }

            public function now(): DateTimeImmutable
            {
                $now = ($this->now)();

                if (!$now instanceof DateTimeImmutable) {
                    throw new UnexpectedValueException('$clock->now() must return a DateTimeImmutable');
                }

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
