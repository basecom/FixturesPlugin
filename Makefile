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

## Run a command within a php docker environment
docker:
	docker run -it --rm --user root -v "${PWD}:/var/www/shopware" -v "${HOME}/.composer:/root/.composer" -v "${PWD}/Makefile_Container:/var/www/shopware/Makefile" registry.gitlab.com/basecom-gmbh/shopware/v6/customer-projects/docker/php-cron-build:3 ${COMMAND}

## Start a bash shell within a php docker environment
shell:
	make docker COMMAND="bash"

############
# Building #
############

## Install all composer prod and dev dependencies
build-composer-dev:
	make docker COMMAND="composer install --no-interaction --optimize-autoloader --no-suggest"

###########
# Linting #
###########

## Run all linting and static code analysis tools
lint:
	make lint-cs-fixer
	make lint-phpstan
	make lint-psalm

## PHP CS fixer
lint-cs-fixer:
	make docker COMMAND="./vendor/bin/php-cs-fixer fix --allow-risky=yes --config=.php_cs.php"

## PHPStan
lint-phpstan:
	make docker COMMAND="./vendor/bin/phpstan analyse src/ --memory-limit=1G --level 8"

## Psaml
lint-psalm:
	make docker COMMAND="./vendor/bin/psalm --show-info=false"

########
# Test #
########

## Run all tests
test:
	make test-spec

## Run all phpspec tests
test-spec:
	make docker COMMAND="vendor/bin/phpspec run -vvv"

## Run phpspec with code coverage
test-coverage-no-infections:
	make docker COMMAND="phpdbg -qrr vendor/bin/phpspec --config=phpspec_coverage.yml run"

## Run phpspec with code coverage and infection
test-coverage:
	make docker COMMAND="phpdbg -qrr vendor/bin/phpspec --config=phpspec_coverage.yml run"
	make docker COMMAND="vendor/bin/infection --test-framework=phpspec --coverage=coverage --only-covered --min-msi=100  --threads=${INFECTION_THREAD_COUNT} --show-mutations $$INFECTION_FILTER"
