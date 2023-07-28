#!/usr/bin/.env bash
cp -n .env.example .env
docker-compose -f ./docker-compose.yml build --no-cache