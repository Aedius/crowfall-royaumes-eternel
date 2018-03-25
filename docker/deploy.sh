#!/bin/bash

source .env

echo "start deploy on $ENV"

bash backup.sh

git fetch --all
git reset --hard origin/master

bash install.sh

echo "deploy on $ENV done"
