#!/bin/bash

source .env

echo $ENV

if [ $ENV = "PROD" ]
then
    docomp='docker-compose -f docker-compose-prod.yml -f docker-compose.yml '
    symfony="$docomp exec php php bin/console --env=prod "
    symfonyAdmin="$docomp exec php php bin/consoleAdmin --env=prod "
    symfonyWriter="$docomp exec php php bin/consoleWriter --env=prod "
else
    docomp='docker-compose -f docker-compose-preprod.yml -f docker-compose.yml '
    symfony="$docomp exec php php bin/console --env=dev "
    symfonyAdmin="$docomp exec php php bin/consoleAdmin --env=dev "
    symfonyWriter="$docomp exec php php bin/consoleWriter --env=dev "
fi


$docomp exec php composer install
$symfony doctrine:migrations:migrate --no-interaction
$symfonyAdmin ckeditor:install --clear=skip
$symfony assets:install --symlink
$symfonyAdmin assets:install --symlink
$symfonyWriter assets:install --symlink
$symfony cache:clear --no-warmup
$symfonyAdmin cache:clear --no-warmup
$symfonyWriter cache:clear --no-warmup