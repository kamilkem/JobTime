#!/usr/bin/env sh

APP_ENV=test ./bin/console doctrine:database:drop --force --if-exists &&
APP_ENV=test ./bin/console doctrine:database:create &&
APP_ENV=test ./bin/console doctrine:migrations:migrate --no-interaction
APP_ENV=test ./bin/console doctrine:fixtures:load --no-interaction
