#!/bin/bash

source .env

echo "start deploy on $ENV"

./backup.sh

git fetch --all
git reset --hard origin/master

chmod +x ./backup.sh
chmod +x ./install.sh
chmod +x ./deploy.sh


./install.sh

echo "deploy on $ENV done"