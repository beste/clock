<?php

declare(strict_types=1);

namespace Beste\Clock\Tests;

use Beste\Clock\FrozenClock;
use Beste\Clock\WrappingClock;
use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/**
 * @internal
 */
#[CoversClass(WrappingClock::class)]
#[UsesClass(FrozenClock::class)]
final class WrappingClockTest extends TestCase
{
    #[Test]
    public function itWrapsAClockInterface(): void
    {
        $clock = FrozenClock::fromUTC();

        $wrappedClock = WrappingClock::wrapping($clock);

        self::assertSame(
            $clock->now()->format(DATE_ATOM),
            $wrappedClock->now()->format(DATE_ATOM)
        );
    }

    #[Test]
    public function itWrapsAnObjectWithANowMethod(): void
    {
        $now = FrozenClock::fromUTC()->now();

        $clock = new class($now) {
            private DateTimeImmutable $now;

            public function __construct(DateTimeImmutable $now)
            {
                $this->now = $now;
            }

            public function now(): DateTimeImmutable
            {
                return $this->now;
            }
        };

        $wrappedClock = WrappingClock::wrapping($clock);

        self::assertSame(
            $now->format(DATE_ATOM),
            $wrappedClock->now()->format(DATE_ATOM)
        );
    }

    #[Test]
    public function itDoesNotCallNowWhileWrapping(): void
    {
        $clock = new class() {
            public int $calls = 0;

            public function now(): DateTimeImmutable
            {
                ++$this->calls;

                return new DateTimeImmutable();
            }
        };

        $wrappedClock = WrappingClock::wrapping($clock);

        self::assertSame(0, $clock->calls);

        $wrappedClock->now();

        self::assertSame(1, $clock->calls);
    }

    #[Test]
    public function itRejectsObjectsWithANowMethodReturningANonDateTimeImmutable(): void
    {
        $clock = new class() {
            public function now(): string
            {
                return 'foo';
            }
        };

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('$clock->now() must return a DateTimeImmutable');

        WrappingClock::wrapping($clock)->now();
    }

    #[Test]
    public function itRejectsObjectsWithoutANowMethod(): void
    {
        $clock = new class() {
        };

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('$clock must implement Psr\Clock\ClockInterface or have a now() method');

        WrappingClock::wrapping($clock);
    }

    #[Test]
    public function itRejectsObjectsWithANonPublicNowMethod(): void
    {
        $clock = new class() {
            protected function now(): DateTimeImmutable
            {
                return new DateTimeImmutable();
            }
        };

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('$clock->now() must be public and accept no required parameters');

        WrappingClock::wrapping($clock);
    }

    #[Test]
    public function itRejectsObjectsWithANowMethodRequiringArguments(): void
    {
        $clock = new class() {
            public function now(string $timeZone): DateTimeImmutable
            {
                return new DateTimeImmutable('now', new DateTimeZone($timeZone));
            }
        };

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('$clock->now() must be public and accept no required parameters');

        WrappingClock::wrapping($clock);
    }
}
