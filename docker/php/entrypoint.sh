#!/bin/bash

set -e

cp /var/www/html/.env.example .env

composer install

php artisan passport:keys

php artisan key:generate

php artisan migrate

exec php-fpm

exec "$@"
