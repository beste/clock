<?php

declare(strict_types=1);

namespace Beste;

use DateTimeImmutable;

interface Clock
{
    public function now(): DateTimeImmutable;
}
