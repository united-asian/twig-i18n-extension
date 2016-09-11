.PHONY: all tests clean

run: build tests

build:
	composer update

tests:
	vendor/bin/phpunit

test.duration:
	vendor/bin/phpunit tests/DurationExtensionTest

test.i18n:
	vendor/bin/phpunit tests/Formatter

test.period:
	vendor/bin/phpunit tests/PeriodExtensionTest
