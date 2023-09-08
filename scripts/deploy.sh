#!/usr/bin/env bash

cd /var/www/html/ShowBegins-API

php artisan down

git pull

composer install --no-dev

php artisan migrate --force

php artisan db:seed --force

php artisan config:cache

php artisan route:cache

php artisan up