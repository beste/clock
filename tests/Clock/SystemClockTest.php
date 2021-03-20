<?php

declare(strict_types=1);

namespace Beste\Clock\Tests;

use Beste\Clock\SystemClock;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversDefaultClass \Beste\Clock\SystemClock
 */
final class SystemClockTest extends TestCase
{
    private string $defaultTimeZone;

    protected function setUp(): void
    {
        $this->defaultTimeZone = date_default_timezone_get();
    }

    protected function tearDown(): void
    {
        date_default_timezone_set($this->defaultTimeZone);
    }

    /**
     * @test
     *
     * @covers ::__construct
     * @covers ::create
     * @covers ::now
     */
    public function itUsesTheSystemTimeZone(): void
    {
        $timeZone = 'Europe/Berlin';
        self::assertNotSame($timeZone, $this->defaultTimeZone);

        date_default_timezone_set($timeZone);

        $clock = SystemClock::create();
        $now = $clock->now();

        self::assertSame($timeZone, $now->getTimezone()->getName());
    }
}
