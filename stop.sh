#!/bin/sh

# Start all services

set -e

cd "$(dirname "$0")/laradock"

sudo docker-compose stop