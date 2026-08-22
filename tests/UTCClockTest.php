<?php

declare(strict_types=1);

namespace Beste\Clock\Tests;

use Beste\Clock\UTCClock;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversDefaultClass \Beste\Clock\UTCClock
 */
final class UTCClockTest extends TestCase
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
    public function itUsesUTCRegardlessOfTheSystemTimeZone(): void
    {
        date_default_timezone_set('Europe/Berlin');

        $clock = UTCClock::create();
        $now = $clock->now();

        self::assertSame('UTC', $now->getTimezone()->getName());
    }
}
