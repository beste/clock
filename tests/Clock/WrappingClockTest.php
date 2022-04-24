<?php

declare(strict_types=1);

namespace Beste\Clock\Tests;

use Beste\Clock\FrozenClock;
use Beste\Clock\WrappingClock;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversDefaultClass \Beste\Clock\WrappingClock
 */
final class WrappingClockTest extends TestCase
{
    /**
     * @test
     *
     * @covers ::__construct
     * @covers ::create
     * @covers ::now
     */
    public function itWrapsAClockInterface(): void
    {
        $clock = FrozenClock::fromUTC();

        $wrappedClock = WrappingClock::wrapping($clock);

        self::assertSame(
            $clock->now()->format(DATE_ATOM),
            $wrappedClock->now()->format(DATE_ATOM)
        );
    }

    /**
     * @test
     *
     * @covers ::__construct
     * @covers ::create
     * @covers ::now
     */
    public function itWrapsAnObjectWithANowMethod(): void
    {
        $now = FrozenClock::fromUTC()->now();

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

        $wrappedClock = WrappingClock::wrapping($clock);

        self::assertSame(
            $now->format(DATE_ATOM),
            $wrappedClock->now()->format(DATE_ATOM)
        );
    }

    /**
     * @test
     *
     * @covers ::create
     */
    public function itRejectsObjectsWithANowMethodReturningANonDateTimeImmutable(): void
    {
        $clock = new class() {
            public function now(): string
            {
                return 'foo';
            }
        };

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('$clock->now() must return a DateTimeImmutable');

        WrappingClock::wrapping($clock);
    }
}
