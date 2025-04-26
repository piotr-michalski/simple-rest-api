SHELL := /bin/bash

tests:
	php bin/console doctrine:database:drop --force --env=test -q || true
	php bin/phpunit $(MAKECMDGOALS)
.PHONY: tests
