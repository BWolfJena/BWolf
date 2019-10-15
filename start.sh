#!/bin/sh

# Start all services

set -e

cd "$(dirname "$0")/"

sudo docker-compose up -d
sleep 1
sudo docker-compose exec web composer install
sudo docker-compose exec web php artisan october:up
sudo docker-compose exec web php artisan october:mirror public/
