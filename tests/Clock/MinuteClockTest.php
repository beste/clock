<?php

declare(strict_types=1);

namespace Beste\Clock\Tests;

use Beste\Clock\MinuteClock;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;

/**
 * @internal
 * @coversDefaultClass \Beste\Clock\MinuteClock
 */
final class MinuteClockTest extends TestCase
{
    /**
     * @test
     *
     * @covers ::__construct
     * @covers ::wrapping
     * @covers ::now
     * @covers ::floor
     *
     * @uses \Beste\Clock\FrozenClock
     *
     * @dataProvider dateTimeStringProvider
     */
    public function itDiscardsEverythingSmallerThanMinutes(string $format, string $input, string $expected): void
    {
        $mockClock = $this->createMock(ClockInterface::class);
        $mockClock->method('now')->willReturn(new DateTimeImmutable($input));

        // Just to be sure that the test input is valid ^^
        self::assertSame($input, $mockClock->now()->format($format));

        $clock = MinuteClock::wrapping($mockClock);

        self::assertSame($expected, $clock->now()->format($format));
    }

    /**
     * @return array<string, string[]>
     */
    public function dateTimeStringProvider(): array
    {
        return [
            'microseconds' => [
                'Y-m-d H:i:s.u',
                '2021-03-24 01:23:45.123456',
                '2021-03-24 01:23:00.000000',
            ],
            'milliseconds' => [
                'Y-m-d H:i:s.v',
                '2021-03-24 01:23:45.123',
                '2021-03-24 01:23:00.000',
            ],
            'seconds' => [
                'Y-m-d H:i:s',
                '2021-03-24 01:23:45',
                '2021-03-24 01:23:00',
            ],
        ];
    }
}
