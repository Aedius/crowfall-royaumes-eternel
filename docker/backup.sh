#!/bin/bash

source .env

echo "start backup for $ENV"

if [ $ENV = "PROD" ]
then
    docomp='/usr/local/bin/docker-compose -f docker-compose-prod.yml -f docker-compose.yml '
    symfonyAdmin="$docomp exec php php bin/consoleAdmin --env=prod "
else
    docomp='/usr/local/bin/docker-compose -f docker-compose-preprod.yml -f docker-compose.yml '
    symfonyAdmin="$docomp exec php php bin/consoleAdmin --env=dev "
fi

$symfonyAdmin app:backup


echo "backup on $ENV done"