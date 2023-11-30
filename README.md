# JobTime

[![CI](https://github.com/kamilkem/jobtime-api/actions/workflows/ci.yml/badge.svg)](https://github.com/kamilkem/jobtime-api/actions/workflows/ci.yml)

## Setup

1. Run compose `docker compose up --wait`
2. Open php container `docker exec -it jobtime-api-php-1 sh`
3. Load/Reload database `./db_reload.sh`
4. Generate JWT keypair `bin/console lexik:jwt:generate-keypair`
5. Open https://localhost/docs

## Static tests

Run in php container `composer ci:check`

## API tests

- Open php container
- Load/Reload test database `./test_db_reload.sh`
- Run behat `./vendor/bin/behat`
