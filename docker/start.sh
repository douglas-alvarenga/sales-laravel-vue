#!/usr/bin/env bash

set -e

role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}

if [ "$env" != "local" ]; then
    echo "Caching configuration"
    (cd /var/www/html && php artisan config:cache && php artisan route:cache && php artisan view:cache)
fi

if [ "$role" = "app" ]; then
    tail -f /var/www/html/storage/logs/laravel.log &
    exec apache2-foreground
elif [ "$role" = "all-in-one" ]; then
    echo "Rodando aplicação com scheduler e queue"
    tail -f /var/www/html/storage/logs/laravel.log &
    exec apache2-foreground &
    php /var/www/html/artisan queue:work --tries=10 --max-time=3600 --backoff=5 &
    while [ true ]; do
        php /var/www/html/artisan schedule:run --verbose --no-interaction &
        sleep 60
    done
else
    echo "Could not match the container role \"$role\""
    exit 1
fi
