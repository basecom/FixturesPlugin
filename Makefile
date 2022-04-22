.SILENT:
.PHONY: build

## Colors
COLOR_RESET   = \033[0m
COLOR_INFO    = \033[32m
COLOR_COMMENT = \033[33m

## Show Help
help:
	printf "${COLOR_COMMENT}Usage:${COLOR_RESET}\n"
	printf " make [target]\n\n"
	printf "${COLOR_COMMENT}Available targets:${COLOR_RESET}\n"
	awk '/^[a-zA-Z-]+:/ { \
		helpMessage = match(lastLine, /^## (.*)/); \
		if (helpMessage) { \
			helpCommand = substr($$1, 0, index($$1, ":")); \
			helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
			printf " ${COLOR_INFO}%-16s${COLOR_RESET} %s\n", helpCommand, helpMessage; \
		} \
	} \
	{ lastLine = $$0 }' $(MAKEFILE_LIST)

##########
# Docker #
##########
## Run a command within the docker container (plugin directory)
docker:
	docker compose exec -w "/var/www/html/custom/static-plugins/plugin" shopware ${COMMAND}

## Run a command within the docker container (base directory)
docker-base:
	docker compose exec shopware ${COMMAND}


## Start a bash shell within a php docker environment
shell:
	make docker-base COMMAND="bash"

############
# Building #
############

## Install all dependencies
dependencies:
	make docker COMMAND="composer install --no-interaction --optimize-autoloader --no-suggest"
	make docker COMMAND="npm ci"

## Install all dependencies and prepare everything
install:
	make dependencies
	make docker-base COMMAND="composer config minimum-stability dev"
	make docker-base COMMAND="composer require basecom/sw6-fixtures-plugin:*"

###########
# Linting #
###########

## Run all linting and static code analysis tools
lint:
	make lint-php-cs-fixer
	make lint-phpstan
	make lint-psalm
	make lint-prettier

## PHP CS fixer
lint-php-cs-fixer:
	make docker COMMAND="./vendor/bin/php-cs-fixer fix"

## PHPStan
lint-phpstan:
	make docker COMMAND="./vendor/bin/phpstan analyse --memory-limit=1G"

## Psaml
lint-psalm:
	make docker COMMAND="./vendor/bin/psalm --show-info=false"

## Prettier
lint-prettier:
	make docker COMMAND="./node_modules/.bin/prettier --write \"src/**/*.{js,scss,yaml,yml,json,md,ts}\""
