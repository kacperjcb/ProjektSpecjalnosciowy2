#!/bin/bash
docker-compose up -d && sleep 15 && docker-compose exec php composer install

