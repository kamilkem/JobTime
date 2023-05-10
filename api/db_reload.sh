#!/usr/bin/env sh

./bin/console doctrine:database:drop --force --if-exists &&
./bin/console doctrine:database:create &&
./bin/console doctrine:migrations:migrate --no-interaction
./bin/console doctrine:fixtures:load --no-interaction
