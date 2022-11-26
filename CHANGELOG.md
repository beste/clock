# CHANGELOG

## 3.0.0 - 2022-11-26
This release replaces `stella-maris/clock` with `psr/clock`.

## 2.3.1 - 2022-11-25
This release reverts the change to implement `psr/clock` directly, to not break dependents who rely on `stella-maris/clock`

## 2.3.0 - 2022-11-25
This release re-introduces support for PHP ^8.0 and implements the freshly released `psr/clock` (PSR-20) directly.

## 2.2.0 - 2022-11-04
This release drops support for PHP <8.1.

## 2.1.0 - 2022-04-22
Adds the `WrappingClock` which allows using an object with a `now()` method returning a `DateTimeImmutable` object
as a "real" clock.

## 2.0.0 - 2022-04-20
This release introduces a compatibility layer with the PSR-20 draft, allowing us to already
get some interoperability by depending on a shared interface.

## 1.0.0 - 2021-12-10
Initial Release
