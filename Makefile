#!/bin/bash

OS = $(shell uname)
UID = $(shell id -u)
PHP = practica-final-php
NGINX = practica-final-nginx
MYSQL = practica-final-mysql

help: ## Show this help message
	@echo 'usage: make [target]'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'

start: ## Start the containers
	docker network create network || true
	U_ID=${UID} docker-compose up -d

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

uploads-permissions:
	U_ID=${UID} docker exec -it --user ${UID} ${PHP} chmod o+rwx /code/app/public/uploads

mysql-container:
	U_ID=${UID} docker exec -it --user ${UID} ${MYSQL} mysql -u root -proot database -h localhost

nginx-container:
	U_ID=${UID} docker exec -it --user ${UID} ${NGINX} /bin/bash
