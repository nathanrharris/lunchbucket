# ----------------
# Make help script
# ----------------

# Usage:
# Add help text after target name starting with '\#\#'
# A category can be added with @category. Team defaults:
#   dev-environment
#   docker
#   drush


# Output colors
GREEN  := $(shell tput -Txterm setaf 2)
WHITE  := $(shell tput -Txterm setaf 7)
YELLOW := $(shell tput -Txterm setaf 3)
RESET  := $(shell tput -Txterm sgr0)

CURRENT_BRANCH := $(shell git branch | grep \* | cut -d ' ' -f2)

TIMESTAMP := $(shell date +"%Y-%m-%d_%H:%M:%S")

# Script
HELP_FUN = \
  %help; \
  while(<>) { push @{$$help{$$2 // 'options'}}, [$$1, $$3] if /^([a-zA-Z\-]+)\s*:.*\#\#(?:@([a-zA-Z\-]+))?\s(.*)$$/ }; \
  print "usage: make [target]\n\n"; \
  print "see makefile for additional commands\n\n"; \
  for (sort keys %help) { \
  print "${WHITE}$$_:${RESET}\n"; \
  for (@{$$help{$$_}}) { \
  $$sep = " " x (32 - length $$_->[0]); \
  print "  ${YELLOW}$$_->[0]${RESET}$$sep${GREEN}$$_->[1]${RESET}\n"; \
  }; \
  print "\n"; }

help: ## Show help (same if no target is specified).
	@perl -e '$(HELP_FUN)' $(MAKEFILE_LIST) $(filter-out $@,$(MAKECMDGOALS))

#
# Dev Environment settings
#
include .env

.PHONY: 	composer-update							\
					composer-install						\
					composer-require						\
					start												\
					stop												\
					prune												\
					ps													\
					shell												\
					shell-mysql									\
					drush												\
					logs												\
					cr													\
					uli													\
					cim													\
					cex													\
					updb												\
					entup												\
					sql-dump										\
					sqlsan											\
					help												\
					install-theme-dependencies	\
					theme-build									\
					theme-watch									\
					init												\
					fetch-build									\
					deploy-build								\
					build												\
					build-dev										\
					deploy											\
					deploy-code									\
					deploy-data									\
					deploy-assets								\
					fetch												\
					fetch-code									\
					fetch-data									\
					fetch-assets



default: start

DRUPAL_ROOT ?= /var/www/html/web

#
# Workflow
#

init: ##@workflow Removes docker project and rebuilds it from scratch
	make stop
	make prune
	make start
	sleep 30
	make fetch-build

fetch-build: ##@workflow Fetch from dev then build local environment.
	make fetch
	make build

deploy-build: ##@workflow Deploy site to dev and then build on dev.
	make deploy
	make build-dev

build: ##@workflow Build local environment.
	make composer-install
	make cim
	make updb
	make entup
	make install-theme-dependencies
	make theme-build
	make cr
	make uli

build-dev: ##@workflow Build development environment.
	ssh lb "cd $(DEV_DOCROOT); composer install; ./drush cim; ./drush updb; ./drush entup; ./drush cr;"

deploy: ##@workflow Deploy code, data, and assets to dev server.
	make deploy-code
	make deploy-data
	make deploy-assets

deploy-code: ##@workflow Deploy code to dev server.
	@echo "Deploying code to dev"
	ssh lb "cd $(DEV_DOCROOT); git checkout develop; git pull origin develop; ./drush cr"

deploy-data: ##@workflow Deploy data to dev server.
	$(eval LOCAL_FILENAME = 'local-data.$(shell date +"%Y-%m-%d_%H:%M:%S").sql')
	$(eval DEV_FILENAME = 'dev-data.$(shell date +"%Y-%m-%d_%H:%M:%S").sql')

	$(eval LOCAL_FILE = $(LOCAL_DATA)/$(LOCAL_FILENAME))
	$(eval DEV_FILE = $(DEV_DATA)/$(DEV_FILENAME))
	$(eval IMPORT_FILE = $(DEV_DATA)/$(LOCAL_FILENAME))

	make sql-dump | gzip -c > $(LOCAL_FILE).gz

	scp $(LOCAL_FILE).gz lb:$(DEV_DATA)/

	ssh lb "cd $(DEV_DOCROOT); ./drush sql-dump | gzip -c > $(DEV_FILE).gz"

	ssh lb "gunzip $(IMPORT_FILE).gz; cd $(DEV_DOCROOT); cat $(IMPORT_FILE) | ./drush sql-cli; gzip $(IMPORT_FILE)"

	ssh lb "cd $(DEV_DOCROOT); ./drush sqlsan; ./drush cr"

deploy-assets: ##@workflow Deploy assets to dev server.
	@echo "Deploying assets to dev"
	rsync -avz --progress $(LOCAL_ASSETS)/* $(DEV_HOST):$(DEV_ASSETS)/
	ssh lb "cd $(DEV_DOCROOT); ./drush cr"

fetch: ##@workflow Fetch code, data, and assets to dev server.
	make fetch-code
	make fetch-data
	make fetch-assets

fetch-code: ##@workflow Fetch code to dev server.
	git checkout develop
	git pull origin develop
	make cr

fetch-data: ##@workflow Fetch data to dev server.

	$(eval LOCAL_FILENAME = 'local-data.$(shell date +"%Y-%m-%d_%H:%M:%S").sql')
	$(eval DEV_FILENAME = 'dev-data.$(shell date +"%Y-%m-%d_%H:%M:%S").sql')

	$(eval LOCAL_FILE = $(LOCAL_DATA)/$(LOCAL_FILENAME))
	$(eval DEV_FILE = $(DEV_DATA)/$(DEV_FILENAME))
	$(eval IMPORT_FILE = $(LOCAL_DATA)/$(DEV_FILENAME))

	make sql-dump | gzip -c > $(LOCAL_FILE).gz

	ssh lb "cd $(DEV_DOCROOT); ./drush sql-dump | gzip -c > $(DEV_FILE).gz"

	scp lb:$(DEV_FILE).gz $(LOCAL_DATA)/

	gunzip $(IMPORT_FILE).gz

	cd $(LOCAL_DOCROOT)

	pv $(IMPORT_FILE)| docker exec -i $(LOCAL_NAME)_mariadb mysql -u$(DB_USER) -p$(DB_PASSWORD) $(DB_NAME)

	gzip $(IMPORT_FILE)

	make sqlsan

	make cr

fetch-assets: ##@workflow Fetch assets to dev server.
	@echo "Fetch assets from dev"
	@rsync -avz --progress $(DEV_HOST):$(DEV_ASSETS)/* $(LOCAL_ASSETS)/
	@make cr

export-local-db: ##@workflow Export local data.
	$(eval LOCAL_FILENAME = 'local-data.$(shell date +"%Y-%m-%d_%H:%M:%S").sql')

	$(eval LOCAL_FILE = $(LOCAL_DATA)/$(LOCAL_FILENAME))

	@make sql-dump | gzip -c > $(LOCAL_FILE).gz

import-local-db: ##@workflow Export local data.
	$(eval LOCAL_FILENAME = 'local-data.sql')

	$(eval LOCAL_FILE = $(LOCAL_DATA)/$(LOCAL_FILENAME))

	@gunzip $(LOCAL_FILE).gz;

	@cd $(LOCAL_DOCROOT);

	@pv $(LOCAL_FILE)| docker exec -i $(LOCAL_NAME)_mariadb mysql -u$(DB_USER) -p$(DB_PASSWORD) $(DB_NAME)

	@gzip $(LOCAL_FILE)

	@make sqlsan

	@make cr

#
# Dev Operations
#
start: ##@docker Start containers and display status.
	@echo "Starting up containers for $(LOCAL_NAME)..."
	@docker-compose pull
	@docker-compose up -d --remove-orphans
	@docker-compose ps

stop: ##@docker Stop and remove containers.
	@echo "Stopping containers for $(LOCAL_NAME)..."
	@docker-compose stop

prune: ##@docker Remove containers for project.
	@echo "Removing containers for $(LOCAL_NAME)..."
	@docker-compose down -v

ps: ##@docker List containers.
	@docker ps --filter name='$(LOCAL_NAME)*'

shell: ##@docker Shell into the container. Specify container name.
	@docker exec -ti -e COLUMNS=$(shell tput cols) -e LINES=$(shell tput lines) $(shell docker ps --filter name='$(LOCAL_NAME)_php' --format "{{ .ID }}") sh

shell-mysql: ##@docker Shell into mysql container.
	@docker exec -ti -e COLUMNS=$(shell tput cols) -e LINES=$(shell tput lines) $(shell docker ps --filter name='$(LOCAL_NAME)_mariadb' --format "{{ .ID }}") sh

drush: ##@docker Run arbitrary drush commands.
	@docker exec $(shell docker ps --filter name='$(LOCAL_NAME)_php' --format "{{ .ID }}") drush -r $(DRUPAL_ROOT) $$cmd

logs: ##@docker Display log.
	@docker-compose logs -f $(filter-out $@,$(MAKECMDGOALS))

#
# Dev Environment build operations
#
composer-update: ##@dev-environment Run composer update.
	@docker-compose exec -T php composer update -n --prefer-dist -v

composer-install: ##@dev-environment Run composer install
	@docker-compose exec -T php composer install -n --prefer-dist -v

composer-require: ##@dev-environment Run composer require (make composer-require package=drupal/foo)
	@docker-compose exec -T php composer require $(package) -n --prefer-dist -v

composer-remove: ##@dev-environment Run composer remove (make composer-require package=drupal/foo)
	@docker-compose exec -T php composer remove $(package) -v

#
# Drush
#
cr: ##@drush Rebuild Drupal cache.
	@docker exec $(shell docker ps --filter name='$(LOCAL_NAME)_php' --format "{{ .ID }}") drush -r $(DRUPAL_ROOT) cr

uli: ##@drush Generate login link.
	@docker exec $(shell docker ps --filter name='$(LOCAL_NAME)_php' --format "{{ .ID }}") drush -r $(DRUPAL_ROOT) uli

cim: ##@drush Drush import configuration.
	@docker exec $(shell docker ps --filter name='$(LOCAL_NAME)_php' --format "{{ .ID }}") drush -r $(DRUPAL_ROOT) config-import

cex: ##@drush Drush export configuration.
	@docker exec $(shell docker ps --filter name='$(LOCAL_NAME)_php' --format "{{ .ID }}") drush -r $(DRUPAL_ROOT) config-export

updb: ##@drush run database updates.
	@docker exec $(shell docker ps --filter name='$(LOCAL_NAME)_php' --format "{{ .ID }}") drush -r $(DRUPAL_ROOT) updb -v

entup: ##@drush run entity updates.
	@docker exec $(shell docker ps --filter name='$(LOCAL_NAME)_php' --format "{{ .ID }}") drush -r $(DRUPAL_ROOT) devel-entity-updates

sql-dump: ##@drush export database.
	@docker exec $(shell docker ps --filter name='$(LOCAL_NAME)_php' --format "{{ .ID }}") drush -r $(DRUPAL_ROOT) sql-dump

sqlsan: ##@drush Drush sanatize database.
	@docker exec $(shell docker ps --filter name='$(LOCAL_NAME)_php' --format "{{ .ID }}") drush -r $(DRUPAL_ROOT) sqlsan

#
# Theme commands
#
install-theme-dependencies: ##@theme Installs npm dependencies for custom theme.
	@cd web/themes/custom/${THEME} && npm install
theme-build: ##@theme build theme.
	@cd web/themes/custom/${THEME} && gulp
theme-watch: ##@theme watch for updates to them, and trigger a build.
	@cd web/themes/custom/${THEME} && gulp watch
