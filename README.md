# Clock

A collection of Clock implementations.

## Table of Contents

- [Introduction](#introduction)
- [Installation](#installation)
- [Clocks](#clocks)
    - [`SystemClock`](#systemclock) - Time, as your computer (k)nows it
    - [`LocalizedClock`](#localizedclock) - A clock in a(nother) time zone
    - [`UTCClock`](#utcclock) - The clock that you should™ use

## Introduction

The purpose of a clock is to give us the current time, which doesn't always have to be **now**, for example in tests.

## Installation

```shell
$ composer require beste/clock
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
printf("The clock's time zone is always %s.\n", $clock->now()->getTimezone()->getName());
```

## Running tests

```shell
$ composer test
```
