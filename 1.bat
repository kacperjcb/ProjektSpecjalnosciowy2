@echo off
git clone https://github.com/kacperjcb/ProjektSpecjalnosciowy2
cd ProjektSpecjalnosciowy2
echo y | composer install
echo y | php bin/console doctrine:database:create
echo y | php bin/console make:migration
echo y | php bin/console doctrine:migrations:migrate
symfony serve -d
