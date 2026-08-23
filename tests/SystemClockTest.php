<?php

declare(strict_types=1);

namespace Beste\Clock\Tests;

use Beste\Clock\SystemClock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(SystemClock::class)]
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

    #[Test]
    public function itUsesTheSystemTimeZone(): void
    {
        $timeZone = $this->defaultTimeZone === 'Europe/Berlin' ? 'UTC' : 'Europe/Berlin';
        self::assertNotSame($timeZone, $this->defaultTimeZone);

        date_default_timezone_set($timeZone);

        $clock = SystemClock::create();
        $now = $clock->now();

        self::assertSame($timeZone, $now->getTimezone()->getName());
    }
}
