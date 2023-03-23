UID := $(shell id -u)
GID := $(shell id -g)

export UID
export GID

DOCKER_CMD := docker-compose
ifeq ($(OS),Windows_NT)
  DOCKER_CMD := $(DOCKER_CMD) -f "%cd%\docker\docker-compose.yml"
else
  DOCKER_CMD := $(DOCKER_CMD) -f $$(pwd)/docker/docker-compose.yml
endif

PARAMS=$(filter-out $@,$(MAKECMDGOALS))

DOCKER_CMD_PHP_CLI := $(DOCKER_CMD) exec php-fpm
DOCKER_CMD_NODE_CLI := $(DOCKER_CMD) run --rm node

set-env:
	cp -v ./docker/.env.example ./docker/.env && \
	cp -v ./docker/.env.example ./frontend/.env
nginx-console:
	$(DOCKER_CMD) exec nginx sh
mysql-console:
	$(DOCKER_CMD) exec mysql bash
node-console:
	$(DOCKER_CMD) run node bash
php-console:
	$(DOCKER_CMD_PHP_CLI) bash
up:
	$(DOCKER_CMD) up
start:
	$(DOCKER_CMD) start
stop:
	$(DOCKER_CMD) stop
down:
	$(DOCKER_CMD) down --remove-orphans
rm:
	$(DOCKER_CMD) rm
build:
	$(DOCKER_CMD) up -d --force-recreate --build --remove-orphans
composer-install:
	$(DOCKER_CMD_PHP_CLI) composer install
npm-install:
	$(DOCKER_CMD_NODE_CLI) npm install
npm-run-dev:
	$(DOCKER_CMD_NODE_CLI) npm run dev
npm-run-prod:
	$(DOCKER_CMD_NODE_CLI) npm run build
composer-update:
	$(DOCKER_CMD_PHP_CLI) composer update
docker-add-user:
	sudo usermod -aG docker ${USER} & \
	su ${USER}
init-dev: set-env build composer-install npm-run-dev
init-prod: build composer-install npm-run-prod
php-tests:
	$(DOCKER_CMD_PHP_CLI) php vendor/bin/codecept run --steps
docker-logs:
	$(DOCKER_CMD) logs $(PARAMS)
docker-config:
	$(DOCKER_CMD) config

%:
	@:
