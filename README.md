# Clock

[![Current version](https://img.shields.io/packagist/v/beste/clock.svg?logo=composer)](https://packagist.org/packages/beste/clock)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/beste/clock)](https://packagist.org/packages/beste/clock)
[![Tests](https://github.com/beste/clock/actions/workflows/tests.yml/badge.svg)](https://github.com/beste/clock/actions/workflows/tests.yml)

A collection of Clock implementations.

## Table of Contents

- [Installation](#installation)
- [Clocks](#clocks)
    - [`SystemClock`](#systemclock) - Time, as your computer (k)nows it
    - [`LocalizedClock`](#localizedclock) - A clock in a(nother) time zone
    - [`UTCClock`](#utcclock) - The clock that you should™ use
    - [`FrozenClock`](#frozenclock) - A clock that stopped moving (perfect for tests)
    - [`MinuteClock`](#minuteclock) - Who cares about seconds or even less?
    - [`WrappingClock`](#wrappingclock) - Allows wrapping a non-clock with a `now()` method in a clock
- [Running Tests](#running-tests)
    
## Installation

```shell
composer require beste/clock
```

## Clocks

### `SystemClock`

A System Clock will return a time just as if you would use `new DateTimeImmutable()`. The time zone of the returned
value is determined by the clock's environment, for example by the time zone that has been configured in your
application, by a previously used `date_default_timezone_set()` or by the value of `date.timezone` in the
`php.ini`. If none of these are explicitly set, it uses the `UTC` timezone.

```php
# examples/system_clock.php

use Beste\Clock\SystemClock;

$clock = SystemClock::create();

printf("On your system, the current date and time is %s\n", $clock->now()->format('Y-m-d H:i:s T (P)'));

date_default_timezone_set('America/Los_Angeles');

printf("Now it's %s\n", $clock->now()->format('Y-m-d H:i:s T (P)'));

date_default_timezone_set('Europe/Berlin');

printf("Now it's %s\n", $clock->now()->format('Y-m-d H:i:s T (P)'));
```

### `LocalizedClock`

A localized clock is aware of the time zone in which it is located. While the time zone of the `SystemClock` is
determined from the environment (your PHP configuration), this clock uses the time zone that you initialize it with.

```php
# examples/localized_clock.php

use Beste\Clock\LocalizedClock;

$berlin = LocalizedClock::in('Europe/Berlin');
$denver = LocalizedClock::in(new DateTimeZone('America/Denver'));

printf("Berlin: %s\n", $berlin->now()->format('Y-m-d H:i:s T (P)'));
printf("Denver: %s\n", $denver->now()->format('Y-m-d H:i:s T (P)'));
```

### `UTCClock`

`UTC` is the abbreviation for [Coordinated Universal Time](https://en.wikipedia.org/wiki/Coordinated_Universal_Time)
and a special kind of time zone that is not affected by daylight saving time. It is commonly used for the communication
of time across different systems (e.g. between your PHP application and a database, or between a backend
and a frontend). An `UTCClock` instance behaves exactly the same as an instance of `LocalizedClock::in('UTC')`.

```php
# examples/utc_clock.php

use Beste\Clock\UTCClock;

$clock = UTCClock::create();

$anotherTimeZone = 'Africa/Casablanca';

date_default_timezone_set($anotherTimeZone);

printf("The system time zone is %s.\n", $anotherTimeZone);
printf("The clock's time zone is %s.\n", $clock->now()->getTimezone()->getName());
```

### `FrozenClock`

A frozen clock doesn't move - the time we set it with will stay the same... unless we change it. That makes the
frozen clock perfect for testing the behaviour of your time-based use cases, for example in Unit Tests.

```php
# examples/frozen_clock.php

use Beste\Clock\FrozenClock;
use Beste\Clock\SystemClock;

$frozenClock = FrozenClock::withNowFrom(SystemClock::create());

printf("\nThe clock is frozen at %s", $frozenClock->now()->format('Y-m-d H:i:s T (P)'));
printf("\nLet's wait a second…");
sleep(1);
printf("\nIt's one second later, but the clock is still frozen at %s", $frozenClock->now()->format('Y-m-d H:i:s T (P)'));

$frozenClock->setTo($frozenClock->now()->modify('-5 minutes'));
printf("\nAfter turning back the clock 5 minutes, it's %s", $frozenClock->now()->format('Y-m-d H:i:s T (P)'));
```

### `MinuteClock`

In some cases, microseconds, milliseconds, or even seconds are too precise for some use cases - sometimes it's just
enough if something happened in the same minute. Using the minute

```php
# examples/minute_clock.php

use Beste\Clock\FrozenClock;
use Beste\Clock\MinuteClock;

$frozenClock = FrozenClock::at(new DateTimeImmutable('01:23:45'));
$clock = MinuteClock::wrapping($frozenClock);

printf("For %s, the minute clock returns %s\n",
    $frozenClock->now()->format('H:i:s'),
    $clock->now()->format('H:i:s')
);

$frozenClock->setTo($frozenClock->now()->modify('+10 seconds')); // 01:23:55

printf("For %s, the minute clock still returns %s\n",
    $frozenClock->now()->format('H:i:s'),
    $clock->now()->format('H:i:s')
);
```

### `WrappingClock`

If you already have an object with a `now()` method returning a `DateTimeImmutable` object, you can wrap it 
in a `WrappingClock` to make it a "real" Clock.

as a "real" clock.

```php
# examples/wrapping_clock.php

use Beste\Clock\WrappingClock;

// Create a frozen $now so that we can test the wrapping clock.
$now = new DateTimeImmutable('2012-04-24 12:00:00');

// Create an object that is NOT a clock, but has a now() method returning the frozen $now.
$clock = new class($now) {
    private \DateTimeImmutable $now;

    public function __construct(\DateTimeImmutable $now)
    {
        $this->now = $now;
    }

    public function now(): \DateTimeImmutable
    {
        return $this->now;
    }
};

// We can now wrap the object in a clock.
$wrappedClock = WrappingClock::wrapping($clock);

assert($now->format(DATE_ATOM) === $wrappedClock->now()->format(DATE_ATOM));
```

## Running tests

```shell
composer test
```
