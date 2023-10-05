#!/bin/bash
git clone -b DockerCompose https://github.com/kacperjcb/ProjektSpecjalnosciowy2 && cd ProjektSpecjalnosciowy2 && docker-compose up -d && sleep 15 && docker-compose exec php composer install

