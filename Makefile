PHP_IMAGE	:= wpengine/php
PHP_VERSION := 7.2
COMPOSER_IMAGE := skypress/wp-composer
COMPOSER_VERSION := latest
USER_NAME := www-data
USER_GROUP := www-data

default: lint

lint: lint-php
clean: file-perms wp-clean
install: composer-install file-perms
update: composer-update

lint-php:
		@echo
		# Running php -l
		@docker run --rm \
			--volume $(PWD):/workspace \
			--workdir /workspace \
			$(PHP_IMAGE):$(PHP_VERSION) \
				/bin/bash -c 'find . \
					-not \( -path "*/vendor" -prune \) \
					-name \*.php \
					-print0 | \
					xargs -I {} -0 php -l {}'
		@echo
		# Linted PHP files

file-perms:
	@echo
	# Setting proper file permissions
	sudo find . -type d -exec chmod 775 {} \;
	sudo find . -type f -exec chmod 664 {} \;
	@echo
	# File permissions set

composer-install:
	@echo
	# Installing packages from composer.json
	@docker run --rm \
		--volume $(PWD):/app \
		--workdir /app \
		$(COMPOSER_IMAGE):$(COMPOSER_VERSION) \
			composer install -o
	@echo
	# Finished install packages

composer-update:
	@echo
	# Installing packages from composer.json
	@docker run --rm \
		--volume $(PWD):/app \
		--workdir /app \
		$(COMPOSER_IMAGE):$(COMPOSER_VERSION) \
			composer update -o
	@echo
	# Finished install packages
