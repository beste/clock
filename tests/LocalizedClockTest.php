<?php

declare(strict_types=1);

namespace Beste\Clock\Tests;

use Beste\Clock\LocalizedClock;
use DateTimeZone;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(LocalizedClock::class)]
final class LocalizedClockTest extends TestCase
{
    #[Test]
    public function itRejectsAnInvalidTimeZone(): void
    {
        $this->expectException(InvalidArgumentException::class);

        LocalizedClock::in('invalid');
    }

    #[Test]
    public function itUsesTheGivenTimeZone(): void
    {
        $timeZone = new DateTimeZone('Asia/Bangkok');
        $clock = LocalizedClock::in($timeZone);
        $now = $clock->now();

        self::assertSame($timeZone->getName(), $now->getTimezone()->getName());
    }

    #[Test]
    public function itAcceptsTheTimeZoneAsAString(): void
    {
        $timeZone = 'Pacific/Guam';
        $clock = LocalizedClock::in($timeZone);
        $now = $clock->now();

        self::assertSame($timeZone, $now->getTimezone()->getName());
    }
}
