#!/usr/bin/env bash

set -e

echo "Caching configuration..."
cd /srv && php artisan config:cache && php artisan route:cache && php artisan view:cache

exec apache2-foreground

echo "Running the queue..."
php /srv/artisan queue:work --verbose --tries=3 --timeout=90

while [ true ]
    do
      php /srv/artisan schedule:run --verbose --no-interaction &
      sleep 60
    done
