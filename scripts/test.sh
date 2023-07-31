#!/usr/bin/.env bash
cp -n .env.example .env
docker-compose exec -it php sh -c ./vendor/bin/phpunit