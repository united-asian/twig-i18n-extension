.PHONY: all tests clean

run: build tests

build:
	composer update

tests:
	vendor/bin/phpunit

test.bytes:
	vendor/bin/phpunit tests/Formatter/ByteFormatterTest

test.country:
	vendor/bin/phpunit tests/CountryFormatterTest

test.currency:
	vendor/bin/phpunit tests/CurrencyFormatterTest

test.date:
	vendor/bin/phpunit tests/DateFormatterTest

test.datetime:
	vendor/bin/phpunit tests/DateTimeFormatterTest

test.duration:
	vendor/bin/phpunit tests/DurationExtensionTest

test.i18n:
	vendor/bin/phpunit tests/Formatter

test.language:
	vendor/bin/phpunit tests/LanguageFormatterTest

test.number:
	vendor/bin/phpunit tests/NumberFormatterTest

test.period:
	vendor/bin/phpunit tests/PeriodExtensionTest
