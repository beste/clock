<?php

declare(strict_types=1);

namespace Beste\Clock\Tests;

use Beste\Clock\SystemClock;
use Beste\Clock\UTCClock;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Beste\Clock\UTCClock
 */
class UTCClockTest extends TestCase
{
    /** @var string */
    private $defaultTimeZone;

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
