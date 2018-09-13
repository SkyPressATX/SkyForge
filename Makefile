VERSION := 0.1.0
PHP_IMAGE := 'wpengine/php'
COMPOSER_IMAGE := skypress/wp-composer

default: lint test

lint: lint-php
test: test-php
install: composer-install
update: composer-update

lint-php:
	@echo
	# Running php -l
	@docker run --rm \
		--volume $(PWD):/workspace \
		--workdir /workspace \
		$(PHP_IMAGE):7.2 \
			/bin/bash -c "find /workspace -path '*vendor' -prune -o -name '*.php' -print0 | xargs -I {} -0 php -l {}"
	@echo
	# Linted PHP files

composer-install:
	@echo
	# Installing packages from composer.json
	@docker run --rm \
		--volume $(PWD):/app \
		--workdir /app \
		$(COMPOSER_IMAGE):latest composer install -o
	@echo
	# Finished install packages

composer-update:
	@echo
	# Installing packages from composer.json
	@docker run --rm \
		--volume $(PWD):/app \
		--workdir /app \
		$(COMPOSER_IMAGE):latest composer update -o
	@echo
	# Finished install packages
