PHP_IMAGE	:= wpengine/php
COMPOSER_IMAGE := skypress/wp-composer

default: lint

lint: lint-php
clean: file-perms
install: composer-install
update: composer-update

lint-php:
		@echo
		# Running php -l
		@docker run --rm \
			--volume $(PWD):/workspace \
			--workdir /workspace \
			$(PHP_IMAGE):7.2 \
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
