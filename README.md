# Clock

A collection of Clock implementations.

## Table of Contents

- [Introduction](#introduction)
- [Installation](#installation)
- [Clocks](#clocks)
    - [`SystemClock`](#systemclock) - Time, as your computer (k)nows it

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

## Running tests

```shell
$ composer test
```
