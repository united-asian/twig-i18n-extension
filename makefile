run: build tests

build:
	composer update

tests:
	vendor/bin/phpunit