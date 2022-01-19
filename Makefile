.PHONY: help build build-pull build-nocache create start restart stop down

docker_bin := $(shell command -v docker 2> /dev/null)
dc_bin := $(shell command -v docker-compose 2> /dev/null)
cwd = $(shell pwd)

CURRENT_USER = $(shell id -u):$(shell id -g)
DOCKER_COMPOSE=$(dc_bin)

## Help
help:
	@printf "${COLOR_COMMENT}Usage:${COLOR_RESET}\n"
	@printf " make [target]\n\n"
	@printf "${COLOR_COMMENT}Available targets:${COLOR_RESET}\n"
	@awk '/^[a-zA-Z\-_0-9\.@]+:/ { \
		helpMessage = match(lastLine, /^## (.*)/); \
		if (helpMessage) { \
			helpCommand = substr($$1, 0, index($$1, ":")); \
			helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
			printf " ${COLOR_INFO}%-16s${COLOR_RESET} %s\n", helpCommand, helpMessage; \
		} \
	} \
{ lastLine = $$0 }' $(MAKEFILE_LIST)

define print_block
	printf " \e[30;48;5;82m  %s  \033[0m\n" $1
endef

#######################
# DOCKER TASKS
#######################

## Re-build docker containers
build:
	$(DOCKER_COMPOSE) build

## Build with --pull flag
build-pull:
	$(DOCKER_COMPOSE) build --pull

## Re-build docker containers without using cache
build-nocache:
	$(DOCKER_COMPOSE) build --no-cache --pull

## Create docker containers, volumes and network, but do not start the services
create:
	$(DOCKER_COMPOSE) up --no-start 2>/dev/null

## Start the docker containers
start:
	$(DOCKER_COMPOSE) up -d
	$(call print_block, 'Navigate your browser to ⇒ http://127.0.0.1:8001')

## Restart the docker containers
restart:
	$(DOCKER_COMPOSE) up -d --force-recreate

## Stop the docker containers
stop:
	$(DOCKER_COMPOSE) stop

## Stop and remove the docker containers, volumes, networks and images
down:
	$(DOCKER_COMPOSE) down --volumes

test:
	$(DOCKER_COMPOSE) --file=docker-compose.test.yaml up -d
	$(DOCKER_COMPOSE) --file=docker-compose.test.yaml exec -T app_test /bin/bash -c 'bin/console doctrine:migrations:migrate -n'
	$(DOCKER_COMPOSE) --file=docker-compose.test.yaml exec -T app_test /bin/bash -c 'php ./bin/phpunit'

build-test:
	$(DOCKER_COMPOSE) --file=docker-compose.test.yaml build --no-cache
