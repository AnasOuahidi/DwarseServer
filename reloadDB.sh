#!/usr/bin/env bash
if [ $1 = "test" ];
then
    php bin/console doctrine:database:drop --env=test --force
    php bin/console doctrine:database:create --env=test
    php bin/console doctrine:schema:create --env=test
    php bin/console doctrine:fixtures:load --env=test -n
elif [ $1 = "prod" ];
then
    php bin/console doctrine:database:drop --env=prod --force
    php bin/console doctrine:database:create --env=prod
    php bin/console doctrine:schema:create --env=prod
    php bin/console doctrine:fixtures:load --env=prod -n
else
    php bin/console doctrine:database:drop --force
    php bin/console doctrine:database:create
    php bin/console doctrine:schema:create
    php bin/console doctrine:fixtures:load -n
fi
