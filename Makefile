all: build

build:
	@docker build -t nidjo17/mongoquila .

install:
	@docker compose run --rm mongoquila-lib composer install -o

db:
	@docker compose run --rm --use-aliases mongoquila-db

tests:
	@docker compose run --rm mongoquila-lib php vendor/bin/phpunit

cli:
	@docker compose run --rm mongoquila-lib bash

.PHONY: all build install db tests cli

