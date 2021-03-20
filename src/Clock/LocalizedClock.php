<?php

declare(strict_types=1);

namespace Beste\Clock;

use Beste\Clock;
use DateTimeImmutable;
use DateTimeZone;

final class LocalizedClock implements Clock
{
    /**
     * @var DateTimeZone
     */
    private $timeZone;

    private function __construct(DateTimeZone $timeZone)
    {
        $this->timeZone = $timeZone;
    }

    /**
     * @param DateTimeZone|string $timeZone
     */
    public static function in($timeZone): self
    {
        if (is_string($timeZone)) {
            try {
                $timeZone = new DateTimeZone($timeZone);
            } catch (\Throwable $e) {
                throw new \InvalidArgumentException($e->getMessage());
            }
        }

        return new self($timeZone);
    }

    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', $this->timeZone);
    }
}
