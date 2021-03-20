<?php

declare(strict_types=1);

namespace Beste\Clock\Tests;

use Beste\Clock\FrozenClock;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;

/**
 * @internal
 * @coversDefaultClass \Beste\Clock\FrozenClock
 */
final class FrozenClockTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     * @covers ::at
     * @covers ::now
     */
    public function itFreezesTime(): void
    {
        $now = new DateTimeImmutable('2021-03-21 22:16:00');
        $clock = FrozenClock::at($now);

        self::assertEquals($now, $clock->now());
    }

    /**
     * @test
     *
     * @uses \Beste\Clock\FrozenClock::__construct
     * @uses \Beste\Clock\FrozenClock::fromUTC()
     *
     * @covers ::now()
     */
    public function itReturnsAnEqualNowButNotTheSame(): void
    {
        $clock = FrozenClock::fromUTC();

        $first = $clock->now();
        $second = $clock->now();

        self::assertEquals($first, $second);
        self::assertNotSame($first, $second);
    }

    /**
     * @test
     *
     * @uses \Beste\Clock\FrozenClock::__construct
     * @uses \Beste\Clock\FrozenClock::now
     *
     * @covers ::withNowFrom
     */
    public function itFreezesTimeFromAnotherClock(): void
    {
        $now = new DateTimeImmutable('now');

        $clock = $this->createMock(ClockInterface::class);
        $clock
            ->expects(self::once())
            ->method('now')
            ->willReturn($now);

        $frozenClock = FrozenClock::withNowFrom($clock);

        self::assertEquals($now, $frozenClock->now());
    }

    /**
     * @test
     *
     * @uses \Beste\Clock\FrozenClock::__construct
     * @uses \Beste\Clock\FrozenClock::now
     *
     * @covers ::fromUTC
     */
    public function itFreezesTheCurrentUTCTime(): void
    {
        $clock = FrozenClock::fromUTC();

        self::assertSame('UTC', $clock->now()->getTimezone()->getName());
        self::assertEquals($clock->now(), $clock->now());
    }

    /**
     * @test
     *
     * @uses \Beste\Clock\FrozenClock::__construct
     * @uses \Beste\Clock\FrozenClock::at
     * @uses \Beste\Clock\FrozenClock::now
     *
     * @covers ::setTo
     */
    public function itCanBeSet(): void
    {
        $now = new DateTimeImmutable('2021-03-21 18:18:18');
        $then = new DateTimeImmutable('2021-03-21 19:19:19');

        $clock = FrozenClock::at($now);
        $clock->setTo($then);

        self::assertEquals($then, $clock->now());
    }
}
