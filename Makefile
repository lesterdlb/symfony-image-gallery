#!/bin/bash

OS = $(shell uname)
UID = $(shell id -u)
PHP = image-gallery-php
NGINX = image-gallery-nginx
MYSQL = image-gallery-mysql
REDIS = image-gallery-redis

help: ## Show this help message
	@echo 'usage: make [target]'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'

start: ## Start the containers
	docker network create network || true
	U_ID=${UID} docker-compose up -d

install: ## Install composer dependencies & run migrations
	U_ID=${UID} docker exec -it --user ${UID} -w /code/app/ ${PHP} bash -c "composer install -n && bin/console d:m:m -n"

stop: ## Stop the containers
	U_ID=${UID} docker-compose stop

kill: ## Stop the containers
	U_ID=${UID} docker-compose kill

remove: ## Stop the containers
	U_ID=${UID} docker-compose rm

restart: ## Restart the containers
	$(MAKE) stop && $(MAKE) start

build: ## Rebuilds all the containers
	docker network create network || true
	U_ID=${UID} docker-compose build

php-container:
	U_ID=${UID} docker exec -it --user ${UID} -w /code/app/ ${PHP} bash

start-messenger-consumer:
	U_ID=${UID} docker exec -it --user ${UID} -w /code/app/ ${PHP} php bin/console messenger:consume async

uploads-permissions:
	U_ID=${UID} docker exec -it --user ${UID} ${PHP} chmod o+rwx /code/app/public/uploads

mysql-container:
	U_ID=${UID} docker exec -it --user ${UID} ${MYSQL} mysql -u user -ppassword db -h localhost

nginx-container:
	U_ID=${UID} docker exec -it --user ${UID} ${NGINX} /bin/bash

redis-container:
	U_ID=${UID} docker exec -it --user ${UID} ${REDIS} redis-cli