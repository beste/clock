<?php

declare(strict_types=1);

namespace Beste\Clock\Tests;

use Beste\Clock\LocalizedClock;
use DateTimeZone;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversDefaultClass \Beste\Clock\LocalizedClock
 */
final class LocalizedClockTest extends TestCase
{
    /**
     * @test
     * @covers ::in
     */
    public function itRejectsAnInvalidTimeZone(): void
    {
        $this->expectException(InvalidArgumentException::class);

        LocalizedClock::in('invalid');
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::in
     * @covers ::now
     */
    public function itUsesTheGivenTimeZone(): void
    {
        $timeZone = new DateTimeZone('Asia/Bangkok');
        $clock = LocalizedClock::in($timeZone);
        $now = $clock->now();

        self::assertSame($timeZone->getName(), $now->getTimezone()->getName());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::in
     * @covers ::now
     */
    public function itAcceptsTheTimeZoneAsAString(): void
    {
        $timeZone = 'Pacific/Guam';
        $clock = LocalizedClock::in($timeZone);
        $now = $clock->now();

        self::assertSame($timeZone, $now->getTimezone()->getName());
    }
}
